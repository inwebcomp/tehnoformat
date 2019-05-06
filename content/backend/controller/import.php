<?php

class controller_import extends crud_controller_tree {

	public static $parts = array();

	public function __construct()
	{
		$this->modelName = 'Import';
        $this->controllerName = 'import';

		$i[1] = array(
			"ID" => 1,
			"function" => "price",
			"title" => lang("Обновление цен"),
			"description" => lang("Можно обновлять не только цены")
		);
		
		$i[2] = array(
			"ID" => 2,
			"function" => "items",
			"title" => lang("Товары"),
			"description" => lang("Импорт товаров"),
			"has_images" => 1
		);
		
		self::$parts = $i;
	}
	
	public function items(){
		
		$content = array();
		
		$content["items"] = self::$parts;
		$content["select"] = array("num" => count($content["items"]));
		
		return $content;
		
	}	
	
	public function edit($object){
		
		$content = array();
		
		$object = ((int)$object) ? $object : false;
		
		if($object){	
			$content["subtitle"] = self::$parts[$object]["title"];
			$content["has_images"] = self::$parts[$object]["has_images"];
			
			$content["object"] = $object;
			
			if($object == 2){
				$content["columns_id"] = 1;	
			}
			if($object == 1){
				$content["columns_id"] = 1;	
				$content["skip_rows"] = 1;	
			}
			
			eval("\$content = array_merge(\$content, self::function_".self::$parts[$object]["function"]."());");
		}
		
		return $content;
		
	}	
	
	public function function_items(){
		
		$content = array();
		
		$modelName = "Item";
		
		if(isset($_POST["submit"])){
		
			list($files, $skip, $columns_id, $separator) = Import::AssignParams($_POST["params"]);
			
			$content["images_dir"] = (trim($_POST["params"]["images_dir"]) !== "") ? trim($_POST["params"]["images_dir"]) : "/import/";
			
			if(isset($files) and $files){
				
				$file = array_shift(Utils::FILEStoNormalArray($files));
				$values = Import::GetFileLines($file, 0, false, false, $separator);
				
				Import::AssignSelectedColumns($content, $values, $columns_id);
				
				Import::SkipRows($values, $skip);
				
				$content["csv_items"] = $values = Import::NormalizeValues($values, $content);
	
				Import::GetAllTableColumns($content, $modelName);
				
			}
		
		}elseif(isset($_POST["upload"])){
			
			$content = Import::Insert($modelName, "title");
			
		}
		
		return $content;
		
	}
	
	
	public function function_price(){
		
		$content = array();
		
		$modelName = "Item";
		
		if(isset($_POST["submit"])){
		
			list($files, $skip, $columns_id, $separator) = Import::AssignParams($_POST["params"]);
			
			$content["images_dir"] = (trim($_POST["params"]["images_dir"]) !== "") ? trim($_POST["params"]["images_dir"]) : "/import/";

			$content["link_images"] = (trim($_POST["params"]["link_images"]) !== "") ? 1 : 0;
	
			if(isset($files) and $files){
				$files = Utils::FILEStoNormalArray($files);
				$file = array_shift($files);
				$values = Import::GetFileLines($file, 0, false, false, $separator);
				
				Import::AssignSelectedColumns($content, $values, $columns_id);
				
				Import::SkipRows($values, $skip);
				
				$content["csv_items"] = $values = Import::NormalizeValues($values, $content);
	
				Import::GetAllTableColumns($content, $modelName);
			}
		
		}elseif(isset($_POST["upload"])){

			$content = Import::Upload($modelName, "article");
			
		}
		
		return $content;
		
	}

}