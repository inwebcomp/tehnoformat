<?php
class Images extends DatabaseObject
{
	public function __construct($ID = false)
	{
        $this->modelName = 'Images';
        $this->controllerName = 'images';

		parent::__construct($ID);
	}

	public function CreateThumbnails($model = false, $ID = false, $name = false, $image_name = false)
	{	
		$content = array();
	
		try {
			$image_quality = (Model::$conf->image_quality) ? (int)Model::$conf->image_quality : 90;
			
			$where = "";
			
			if($model) $where .= " AND model = '".$model."'";
			if($name) $where .= " AND name = '".$name."'";
			
			$image_types = Model::$db->ArrayValuesQ("SELECT * FROM `Images` WHERE block = 0".$where);
			
			if(count($image_types)){
				foreach($image_types as $image_type){
					switch ($image_type["fill_type"]) {
						case 'cram':
							$fill_type = "outter";
						break;
						case 'fill':
							$fill_type = "inner";
						break;
						default:
							$fill_type = "outter";
						break;
					}
					
					$bg_color = (preg_match("/\#[A-Za-z0-9]{6}/", $image_type["bg_color"])) ? Utils::hex2RGB($image_type["bg_color"]) : null;
					
					$checker = new Checker($image_type["model"]);
					$items = Model::$db->ArrayValuesQ("SELECT ID FROM `".$image_type["model"]."`".($ID ? " WHERE ID = ".(int)$ID : ""));
					if(count($items)){					
						foreach($items as $item_arr){
							list($item) = $checker->Get($item_arr["ID"]);
				
							if($item){
								$images = $item->GetImages();
								foreach($images as $image){
									
									if($image_name and $image_name !== $image["name"]) continue;
												
									// Original
									$fileName0x0 = Model::$conf->mediaContent . "/images/" . $image["model"] . "/" . $image["object_ID"]. "/" . $image["name"];
									
									// Resized Image Path
									$fileName = Model::$conf->mediaContent . "/images/" . $image["model"] . "/" . $image["object_ID"]. "/" . $image_type["name"] . "/" . $image["name"];
									
									// Resized Directory Path
									$dirSize = Model::$conf->mediaContent . "/images/" . $image["model"] . "/" . $image["object_ID"]. "/" . $image_type["name"];
									
									$path_info = pathinfo($fileName0x0);
									$ext = strtolower($path_info["extension"]);
									
									//if(file_exists($fileName)) continue;
									
									if($ext == "png" or $ext == "jpeg" or $ext == "jpg" or $ext == "svg"){
									
										if(!is_dir($dirSize))
											mkdir($dirSize, 0777);
										
										if($ext == "svg"){
											copy($fileName0x0, $fileName);
										}else{
											$width = abs((int)$image_type["width"]);
											$height = abs((int)$image_type["height"]);
											
											list($origWidth, $origHeight) = getimagesize($fileName0x0);
											
											if($width == 0 and $height == 0){
												$width = $origWidth;
												$height = $origHeight;
											}
											
											if($width == 0 and $height !== 0)
												$width = $height * ($origWidth / $origHeight);
											if($height == 0 and $width !== 0)
												$height = $width * ($origHeight / $origWidth);

											if((bool)$image_type["watermark"]){
												$watermark = array(
													"img" => Model::$conf->filesPath.'/watermark.png',
													"top" => "center",
													"left" => "center"
												);
											}else{
												$watermark = false;
											}

//											if ($ext == 'png' and $bg_color) {
//                                                // change to jpg
//                                                $ext = 'jpg';
//                                                $fileName = preg_replace('/\.png$/', '.jpg', $fileName);
//                                            }

											Utils::ModifyImage($fileName0x0, $ext, $fileName, ($image_type['quality'] ? $image_type['quality'] : $image_quality), $width, $height, $fill_type, $bg_color, $image_type["padding"], null, $watermark);
										}

										Model::$db->Query("REPLACE `Image` SET name = '" . $image["name"] . "', model = '" . $image["model"] . "', object_ID = '" . $image["object_ID"] . "', pos = '" . $image["pos"] . "'");
										
									}
									
								}
							}
						}
					}
					
				}
			}
			
			$content["mess"] = lang("Миниатюры успешно созданы");
		}catch(Exception $ex){
			$content["mess"] = $ex->GetMessage();
			$content["err"] = 1;
		}
		
		return $content;
	}

	public function ReindexImages($model = false)
	{	
		$content = array();

		if(!$model){
			Images::ReindexImages('Category');
			Images::ReindexImages('Item');
			Images::ReindexImages('Article');
			Images::ReindexImages('Banners');
			Images::ReindexImages('Pages');
			Images::ReindexImages('Menu');
			Images::ReindexImages('Shops');

			return;
		}
	
		try {
			$basePath = Model::$conf->imgPath."/images/$model";
			$where = "";		
			$checker = new Checker($model);
			$items = Model::$db->ArrayValuesQ("SELECT ID FROM `".$model."`".($ID ? " WHERE ID = ".(int)$ID : ""));
			if(count($items)){					
				foreach($items as $item_arr){
					list($item) = $checker->Get($item_arr["ID"]);
					
					if($item){
						Model::$db->Query("DELETE FROM `Image` WHERE model = '" . $model . "' AND object_ID = '" . $item->ID . "'");
						$imgPath = $basePath.'/'.$item->ID;
						if(file_exists($imgPath)){
							$flist = scandir($imgPath);
							foreach($flist as $file){
								if(!is_file($imgPath.'/'.$file)) continue;
								
								$path_info = pathinfo($imgPath.'/'.$file);
								$ext = strtolower($path_info["extension"]);
					
								if($ext == "png" or $ext == "jpeg" or $ext == "jpg" or $ext == 'svg'){
									Model::$db->Query("REPLACE `Image` SET name = '" . $file . "', model = '" . $model . "', object_ID = '" . $item->ID . "'");
								}
							}
						}
					}
				}
			}
			
		}catch(Exception $ex){
			$err = 1;
		}
		
		return $content;
	}
	
	public function ClearAllThumbnails($model = false, $ID = false, $name = false)
	{	
		$content = array();
		
		$imgPath = Model::$conf->imgPath."/images";
		
		if($model){
			$imgPath .= "/".$model;
			$path = 1;
		}
		if($model and $ID){
			$imgPath .= "/".$ID;
			$path = 2;
		}
		if($model and $ID and $name){
			$imgPath .= "/".$name;
			$path = 3;
		}
		
		if($model and $ID and $name){
			Utils::RemoveDir($imgPath);
		}else{
			self::RecursiveRemoveThumbnail($imgPath, $path);		
		}
		return $content;
	}
	
	public static function RecursiveRemoveThumbnail($dir, $path)
	{
		if(is_dir($dir) and $path == 3){
			Utils::RemoveDir($dir);
			return;
		}
		
		if (!$dh = @opendir($dir)) return;

    	while (false !== ($obj = readdir($dh)))
    	{
			$nextPath = $path + 1;
			if ($obj == '.' || $obj == '..') continue;

			if(is_dir($dir.'/'.$obj))
				self::RecursiveRemoveThumbnail($dir . '/' . $obj, $nextPath);
    	}
		
		closedir($dh);
	}

}

?>