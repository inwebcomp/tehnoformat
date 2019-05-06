<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

include("../kernel/database.php");
include("../kernel/settings.php");

$db = Database::DataBaseConnect();
$conf = KernelSettings::GetInstance();
		/*
require_once($conf->classesPath."/Parser/Parser.php");

$objects = array();

for($n = 1; $n <= 3; $n++){
		
	$url = "http://arpelcom.md/ru/catalog/items/remer_rubinetterie_italy:smesiteli_rr/".$n."/";
	
	// Создаем поток
	$opts = array(
	  'http'=>array(
		'header'=>"Accept-Encoding: UTF-8\n" .
				  "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4\n"
	  )
	);
	$context = stream_context_create($opts);
	
	$html = file_get_html($url, false, $context);
	if($html){
		$items = $html->find(".right .catalog .item"); 
		if(is_array($items)){
			foreach($items as $item){
				$a = $item->find(".title a", 0);
				if($a){
					$href = $a->href;
					$series	= $a->plaintext;
					
					$series_tmp = explode(" ", $series);
					if($series_tmp[0] == "Серия" and !preg_match('/^\d+$/', $series_tmp[1])){
						$series = implode(" ", array_slice($series_tmp, 1, 10));
					}
					
					if((bool)$href and (bool)$series){
						$series_html = file_get_html("http://arpelcom.md".$href, false, $context);
						if($series_html){
							$item_html = $series_html->find(".right .catalog .item a[href=javascript:void(0)]");
							if(count($item_html) > 0){
								for($i = 1; $i <= count($item_html); $i++){
									$object = array();
									
									$refs = $series_html->find("#imgcode_".$i, 0)->plaintext; 
									if(trim($refs) !== ""){
										$description = $series_html->find("#imgcontent_".$i, 0)->plaintext; 
										//if(trim($description) !== ""){
											$refferences = explode(" ", $refs);	
										//}else{
										//	$refferences = array($refs);	
										//}
									}
									
									$append = array();
									
									foreach($refferences as $ref){
										$ref = trim($ref);
										
										if($ref == "SERIE" or $ref == "" or $ref == "304") continue;
									
										$object["series"] = $series;
										
										$object["ref"] = $ref;
										
										$description_min = $series_html->find("#imgtitle_".$i, 0)->plaintext; 
										$object["description_min"] = $description_min;
										
										if(trim($description) !== ""){
											if(preg_match_all("/\d+/", $description, $match)){
												foreach($match[0] as $value){
													$append[] = (int)trim($value);	
												}
											}
										}
										$object["description"] = $description;
											
										$image = $series_html->find("#bigImage_".$i, 0)->src; 
										if(trim($image) !== ""){
											$image = "http://arpelcom.md".str_replace("&size=300x300", "", $image);
											$object["image"] = $image;
										}
										
										if(count($object))
											$objects[$object["ref"]] = $object;
									}
									
									if(count($append) > 0){
										foreach($append as $value){
											$object = array();
											$object["series"] = $series;
											$object["ref"] = $ref.$value;
											$object["description_min"] = $description_min;
											$object["description"] = $description;
											$object["image"] = $image;
											
											$objects[$object["ref"]] = $object;
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}

}

$result = array();
$images_dir = "images";
$n = 1;
foreach($objects as $ref => $object){
	$headers = @get_headers($object["image"]);
	if(strpos('404', $headers[0]) > -1) continue;
	
	$image_name = $object["ref"].".jpg";
	
	//if(copy($object["image"], $images_dir."/".$image_name)){
		$results[] = array(
			"series" => $object["series"],
			"title" => $object["series"]." ".$object["ref"],
			"article" => $object["ref"],
			"description_min" => $object["description_min"],
			"description" => $object["description_min"],
			"image" => $image_name,
			"comment" => $object["description"]
		);
	//}
	$n++;
}
exit();

// Вывод в XLS
*/
$results = unserialize(file_get_contents("items.txt"));

ini_set('memory_limit', '1024M');
$content = array();
$content[] = array(
					"ID",
					"Название товара",
					"Артикул",
					"Ссылка на редактирование товара"
				);

$content = array_merge($content, $results);

require_once($conf->classesPath."/PHPExcel/PHPExcel.php");

$phpexcel = new PHPExcel();

$page = $phpexcel->setActiveSheetIndex(0);

$n = 1;
if(is_array($content)){
	foreach($content as $row){
		if(count($row)){
			$k = 0;
			$letters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
			
			foreach($row as $value){
				$page->setCellValue($letters[$k].$n, (string)$value);
				$k++;
			}
		}
		$n++;
	}
}

$page->setTitle("Items");

$objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');

$objWriter->save("list.xls");