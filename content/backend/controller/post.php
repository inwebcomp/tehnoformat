<?php

class controller_post extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Post';
		$this->controllerName = 'post';
		
		// Addons
		$this->addons = new stdClass();
		$this->addons->images = true;
	}


	
	public function fast_edit_open($object, $field)
	{
		$content = array();
		
		if (in_array($field, array()))
			$multilang = true;
		else
			$multilang = false;

		$table = ($multilang) ? 'Post_ml' : 'Post';
		
		$type = Post::GetColumnType("Post", $field);
		
		if($type == "VARCHAR" or $type == "TEXT" or $type == "DOUBLE"){
			$content["type"] = $type = "String";	
		}elseif($type == "INT"){
			$content["type"] = $type = "Int";	
		}if($type == "BOOL"){
			$content["type"] = $type = "Bool";	
		}
		
		if($field == "category_ID"){	
			$content["type"] = $type = "Select";
			$value = Post::GetObjectColumn($table, $object, $field, ($multilang ? Application::$language->ID : false));
			$content["selected"] = $value;
			$categories = Category::GetCategoriesTree();
			foreach($categories["Posts"] as $value){
				$content["Posts"][] = array("value" => $value["ID"], "title" => $value["padding"].$value["title"]);
			}
		}else{
			$value = Post::GetObjectColumn($table, $object, $field, ($multilang ? Application::$language->ID : false));
			if($value !== false){
				$content["value"] = $value;
			}
		}

		$content["value"] = htmlspecialchars($content["value"]);

 		$content["object"] = $object;
        $content["fieldname"] = $field;

		return $content;
	}
	
	public function fast_edit_save($object, $field, $value)
	{
		$content = array();
		
		$type = Post::GetColumnType("Post", $field);
		
		$content["type"] = "Default";

		if($type == "BOOL"){
			$value = ($value == "true") ? 1 : 0;
			$content["type"] = "Bool";
		}
		if($field == "block"){
			$value = ($value == "true") ? 0 : 1;
			$content["type"] = "Bool";
		}
		
		if($field == "popular"){
			$value = ($value == 1) ? 1 : 0;
			$content["type"] = "Bool";
		}
		
		if (in_array($field, array()))
			$multilang = true;
		else
			$multilang = false;

		$table = ($multilang) ? 'Post_ml' : 'Post';

		Post::UpdateObjectColumn($table, $object, $field, $value, ($multilang ? Application::$language->ID : false));
	
		$value = Post::GetObjectColumn($table, $object, $field, ($multilang ? Application::$language->ID : false));
		
		if($field == "category_ID"){
			$checker = new Checker("Category");
			list($cat) = $checker->Get($value);
			if($cat){
				$content["value"] = $cat->title;
			}

			if(!$content["value"]){
				$content["value"] = 1;
			}
		}elseif($field == "price"){
			if($value !== false){
				$content["value"] = $value;
				$content["currency"] = Post::GetObjectColumn($table, $object, "currency");
			}
		}else{
			if($value !== false){
				$content["value"] = $value;
			}
		}

 		$content["object"] = $object;
        $content["fieldname"] = $field;

		return $content;
	}


	public function reset_popular()
	{
        Database::query("UPDATE Post SET popular = 0");

		return $this->items(array());
	}
}