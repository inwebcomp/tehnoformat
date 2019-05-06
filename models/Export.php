<?php

class Export extends databaseObject
{
	public static $images_dir = "/images"; 
	
	public function __construct($ID)
	{
        $this->modelName = 'Export';
        $this->controllerName = 'export';
		
		parent::__construct($ID);
	}
	
	public static function Export($elements, $modelName = "Item", $export_fields = array(), $encode_fields = NULL, $not_save_fields = NULL)
	{
		$content = "";
		
		$images_dir = Model::$conf->exportPath.self::$images_dir;
		if(is_dir($images_dir)) Utils::RemoveDir($images_dir);
		mkdir($images_dir, 0777);
		
		if(is_null($encode_fields)) $encode_fields = array("description", "description_min", "item_description_min", "item_description_min");
		if(is_null($not_save_fields)) $not_save_fields = array("ID", "created", "updated", "creator_ID", "updater_ID", "pos");
		
		$result = true;
		
		$tableColumns = self::GetTableColumns($modelName);
	
		if(count($export_fields)){
			$tableColumnsTmp = $export_fields;	
			foreach($tableColumnsTmp as $key => $value){
				if(!in_array($value, $tableColumns)) unset($tableColumnsTmp[$key]);
			}
			$tableColumns = $tableColumnsTmp;
		}
	
		foreach($tableColumns as $key => $value){
			if((!in_array($value, $not_save_fields) and count($export_fields) == 0) or (count($export_fields) > 0 and in_array($value, $export_fields))){
				$titles[] = '"'.$value.'"';
			}
		}
		/*
		$tableColumnsMl = self::GetMultilangTableColumns($modelName);
		
		if(count($export_fields)){
			$tableColumnsMlTmp = $export_fields;	
			foreach($tableColumnsMlTmp as $key => $value){
				if(!in_array($value, $tableColumnsMl)) unset($tableColumnsMlTmp[$key]);
			}
			$tableColumnsMl = $tableColumnsMlTmp;
		}
		
		foreach($tableColumnsMl as $key => $value){
			foreach(Language::GetLanguages() as $k => $v){
				if((!in_array($value, $not_save_fields) and count($export_fields) == 0) or (count($export_fields) > 0 and in_array($value, $export_fields))){
					$titles[] = '"'.$value.'_lang_'.$v["name"].'"';
				}
			}
		}*/

		$n = 1;
		foreach ($elements as $object)
		{
			$i = array();
			
			$checker = new Checker($modelName);
			list($object) = $checker->Get($object);

			if ($object instanceof DatabaseObject)
			{
				$objectClass = $object;
				$object = $object->GetInfo();

				if($n == 1){
					$content .= implode(";", $titles)."\n";	
				}
				
				foreach($tableColumns as $key => $value){
					if(count($export_fields) > 0 and !in_array($value, $export_fields)) continue;
					if((!in_array($value, $not_save_fields) and count($export_fields) == 0) or (count($export_fields) > 0 and in_array($value, $export_fields))){
						if($value == "images"){
							$images_urls = array();
							$images = $objectClass->GetImages();
							if(is_array($images)){
								$k = 1;
								$base_image = false;
								foreach($images as $image){
									$fileName = Model::$conf->mediaContent."/images/".$modelName."/".$image["object_ID"]."/".$image["name"];
									$path_info = pathinfo($fileName);
									$ext = strtolower($path_info["extension"]);
									$newImageName = $n."_".$k."_image.".$ext;
									if(copy($fileName, $images_dir."/".$newImageName)){
										$images_urls[] = $newImageName;	
									}
									$k++;
									
									if($object["base_image"] == $image["name"]){
										$i["base_image"] = '"'.($newImageName).'"';
										$base_image = true; 
									}
									if(!$base_image and isset($i["base_image"])){ 
										$i["base_image"] = '""';
									}
								}
								$i[$value] = '"'.(implode(",", $images_urls)).'"';
							}
						}else{
							if(!in_array($value, $encode_fields)){
								$i[$value] = '"'.(str_replace(";", ".semicolon.", $object[$value])).'"';
							}else{
								$i[$value] = '"'.("base64_encoded_".base64_encode($object[$value])).'"';
							}
						}
					}
				}
				
				/*foreach($tableColumnsMl as $key => $value){
					foreach(Language::GetLanguages() as $k => $v){
						if((!in_array($value, $not_save_fields) and count($export_fields) == 0) or (count($export_fields) > 0 and in_array($value, $export_fields))){
							if(!in_array($value, $encode_fields)){
								$i[$value] = '"'.Utils::Conv(str_replace(";", ".semicolon.", $object[$value])).'"';
							}else{
								$i[$value] = '"'.Utils::Conv("base64_encoded_".base64_encode($object[$value])).'"';
							}
						}
					}
				}*/
				
				$content .= implode(";", $i)."\n";
			}else{
				return false;	
			}
			
			$n++;
		}
		
		
		if(trim($content) !== ""){
			return Export::ExportToFile($modelName."_".date("Y_m_d_H_i_s", time()), chr(239) . chr(187) . chr(191) . $content);
		}else{
			return false;	
		}
		
		return $exportFile;
	}
	
	public static function ExportToFile($fileName, $content)
	{
		$images_dir = Model::$conf->exportPath.self::$images_dir;
		$export_tmp = Model::$conf->tmpPath.'/export';

		if(file_exists(Model::$conf->exportPath."/items.zip")){
			unlink(Model::$conf->exportPath."/items.zip");	
		}

		if(file_exists($export_tmp))
			Utils::ClearDir($export_tmp);
		else 
			mkdir($export_tmp, 0777);

		# Zipping images
		Utils::ZipFolder($images_dir, $export_tmp."/images.zip");
		Utils::RemoveDir($images_dir);
		
		# Creating File
		file_put_contents($export_tmp."/".$fileName.".csv", $content);
		
		# Zipping all
		Utils::ZipFolder($export_tmp, $export_tmp."/items.zip");
		Utils::ClearDir(Model::$conf->exportPath);
		
		copy($export_tmp."/items.zip", Model::$conf->exportPath."/items.zip");
		
		/*if(is_dir($export_tmp))
			Utils::RemoveDir($export_tmp);*/
		
		return "/export/items.zip";
	}
}

?>