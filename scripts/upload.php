<?php


include_once "../kernel/settings.php";
include_once "../kernel/database.php";

$conf = KernelSettings::GetInstance();
$db = Database::DataBaseConnect();

$files = $_FILES["params"];

$file = array();

$result = 0;

foreach($files as $k => $v){
	$file[0][$k] = $v;
}

if(!$_REQUEST['all']){
	if(is_array($file)){
		foreach($file as $key => $value){ 
			if(isset($file[$key]["error"]["base_image"]) && $file[$key]["error"]["base_image"] == 0)
			{
				$md5 = md5_file($value["tmp_name"]["base_image"]);
		
				if(move_uploaded_file($file[$key]["tmp_name"]["base_image"], $conf->documentroot . "/tmp/" . $md5)){
					chmod($conf->documentroot . "/tmp/" . $md5, 0777);
					$db->Query("INSERT INTO Uploads SET name = '" . addslashes($file[$key]["name"]["base_image"]) . "',
													 size = " . $file[$key]["size"]["base_image"] . ",
													 md5 = '" . $md5 . "'");
					
					$result = 1;								 
				}
			}
		}
	}
}else{
	if(is_array($file)){
		foreach($file as $key => $value){
			if(isset($file[$key]["error"]["file"]) && $file[$key]["error"]["file"] == 0)
			{
				$md5 = md5_file($value["tmp_name"]["file"]);
			
				if(move_uploaded_file($file[$key]["tmp_name"]["file"], $conf->documentroot . "/tmp/" . $md5)){
				
					$db->Query("INSERT INTO Uploads SET name = '" . addslashes($file[$key]["name"]["file"]) . "',
													 size = " . $file[$key]["size"]["file"] . ",
													 md5 = '" . $md5 . "'");
					
					$result = 1;								 
				}
			}
		}
	}
}

echo json_encode(array('result' => $result));