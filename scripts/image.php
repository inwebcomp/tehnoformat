<?php
include_once("../kernel/utils.php");
include_once("../kernel/settings.php");

error_reporting(0);
// Init settings
try{
	$conf = KernelSettings::GetInstance(false);
}catch(Exception $ex){
	print $ex->getMessage();
}

// Get variables
if(isset($_COOKIE["image_settings"])) $image_settings = unserialize($_COOKIE["image_settings"]);
else $image_settings = array();

if(isset($_COOKIE["retina"])) $retina = $_COOKIE["retina"];
else $retina = false;

if (isset($_REQUEST["name"])) $name = $_REQUEST["name"];
else $name = NULL;

if (isset($_REQUEST["model"])) $model = $_REQUEST["model"];
else $model = NULL;

if (isset($_REQUEST["ID"])) $ID = $_REQUEST["ID"];
else $ID = NULL;

if (isset($_REQUEST["size"])) $size = $_REQUEST["size"];
else $size = NULL;

if (isset($_REQUEST["resize"])) $type = $_REQUEST["resize"];
else $type = "outter";

if (isset($_REQUEST["color"]) and trim($_REQUEST["color"]) !== "") $color = "#".preg_replace("#[\.\\\/]+#isu", "", $_REQUEST["color"]);
else $color = "#FFFFFF";

if(isset($_REQUEST["zoomfromborder"]))
	$zoomfromborder = $_REQUEST["zoomfromborder"];
else
	$zoomfromborder = 0;

$color = Utils::hex2RGB($color);


// Config
$config["retina"] = (int)$image_settings["retina"];
$config["recreate_images"] = (int)$image_settings["recreate_images"];
$config["image_quality"] = ((int)$image_settings["image_quality"]) ? (int)$image_settings["image_quality"] : 90;
$config["cache_images"] = (int)$image_settings["cache_images"];

// Cache Control
header('Cache-Control: "max-age=2592000"');

function checkHash($fileName){
	global $config;
	if((int)$config["cache_images"] == 0) return true;
	
	$hash = filemtime($fileName) . '-' . md5($fileName);
	header("Etag: \"" . $hash . "\"");
	if(isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $hash !== "" && stripslashes($_SERVER["HTTP_IF_NONE_MATCH"]) == '"' . $hash . '"')
	{
		header("HTTP/1.0 304 Not Modified");
		header("Content-Length: 0");
		exit();
	}	
}


// Set retina size
if(($retina == 2 or $retina == 3 or $retina == 4) and $config["retina"] == 1){
	$tmpsize = explode("x", $size);
	$tmpsizex = (int)$tmpsize[0] * $retina;
	$tmpsizey = (int)$tmpsize[1] * $retina;
	$size = $tmpsizex."x".$tmpsizey;
} 


// Get image
if($model){
   
    if($type !== "outter")
    	$fileName = $conf->mediaContent . "/images/" . $model . "/" . $ID . "/" . $size . "x" . $type . "/" . $name;
    else
    	$fileName = $conf->mediaContent . "/images/" . $model . "/" . $ID. "/" . $size . "/" . $name;
		
	$fileName0x0 = $conf->mediaContent . "/images/" . $model . "/" . $ID. "/" . $name;

	if($size){
		if($type !== "outter")
			$dirSize = $conf->mediaContent . "/images/" . $model . "/" . $ID . "/" . $size . "x" . $type;
		else
			$dirSize = $conf->mediaContent . "/images/" . $model . "/" . $ID . "/" . $size;

		if($type !== "outter")
			$fileNameSize = $conf->mediaContent . "/images/" . $model . "/" . $ID . "/" . $size . "x" . $type . "/" . $name;
		else
			$fileNameSize = $conf->mediaContent . "/images/" . $model . "/" . $ID . "/" . $size . "/" . $name;
	}

	if (is_file($fileName0x0)) 
	{
		checkHash($fileName0x0);
		
		$path_info = pathinfo($fileName);
    	$ext = strtolower($path_info["extension"]);

		if ($ext == "svg") {
			header("Content-type: image/svg+xml");
			print file_get_contents($fileName0x0);
			exit();
		}

		if ($size == "0x0"){ // Original image
		
			if ($ext == "gif")
			   header("Content-type: image/gif");
            elseif($ext == "png")
            	header("Content-type: image/png");
            elseif($ext == "jpg" || $ext == "jpeg")
            	header("Content-type: image/jpeg");
			
			print file_get_contents($fileName0x0);
			
		}elseif($ext == "png" && $size !== "0x0"){ // PNG image

			header("Content-type: image/png");
			if(is_file($fileNameSize) and $config["recreate_images"] == 0)
				print file_get_contents($fileNameSize);
			else{
				list($x, $y, $resize) = explode("x", $size);
				if(!$resize) $resize = $type;
				if(!is_dir($dirSize))
                	mkdir($dirSize);

				Utils::ModifyImage($fileName0x0, "png", $fileNameSize, $config["image_quality"], $x, $y, $resize, $color, $zoomfromborder);

				print file_get_contents($fileNameSize);
			}
			
		}else{ 
			header("Content-type: image/jpeg");
			if(is_file($fileNameSize) and $config["recreate_images"] == 0)
				print file_get_contents($fileNameSize);
			else{
				list($x, $y, $resize) = explode("x", $size);
                if(!$resize) $resize = $type;
                if(!is_dir($dirSize))
                	mkdir($dirSize);

				Utils::ModifyImage($fileName0x0, "jpg", $fileNameSize, $config["image_quality"], $x, $y, $resize, $color, $zoomfromborder);

				print file_get_contents($fileNameSize);
			}
			
		}
	}else{
		list($x, $y, $resize) = explode("x", $size);
		if (!$resize) $resize = $type;
        if (!is_dir($conf->mediaContent . "/images/nophoto/" . $size))
           	mkdir($conf->mediaContent . "/images/nophoto/" . $size);
		
		checkHash($conf->mediaContent . "/images/nophoto/" . $size . "/nophoto.jpg");
		
		if(!file_exists($conf->mediaContent . "/images/nophoto/" . $size . "/nophoto.jpg"))
			Utils::ModifyImage($conf->mediaContent . "/images/nophoto/nophoto.jpg", "jpg", $conf->mediaContent . "/images/nophoto/" . $size . "/nophoto.jpg", $config["image_quality"], $x, $y, $resize, $color);
		
		header("Content-type: image/jpeg");
		
		print file_get_contents($conf->mediaContent . "/images/nophoto/" . $size . "/nophoto.jpg");
	}
}
else{
	checkHash($conf->mediaContent . "/nophoto/150x150/nophoto.jpg");
	
	header("Content-type: image/jpeg");
	print file_get_contents($conf->mediaContent . "/nophoto/150x150/nophoto.jpg"); 
}

?>