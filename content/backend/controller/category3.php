<?php

class controller_category extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Category';
        $this->controllerName = 'category';
	}

	public function items($object = "", $params = array()){
		
		$content = array();
		
        $checker = new Checker('Parameters', 'Category');
		list($params, $object) = $checker->Get($params, $object);
        if (!$params instanceof Parameters){
        	$params = new Parameters();
		} 
		
		if($object)
			$params->where->parent_ID = $object->ID;
		
		$params->smart = 1;

		$content = Category::GetList('Category', $params);

		$level = ((int)$content["items"][1]["level"]) ? $content["items"][1]["level"] : 1;
			
		$params = array("order" => $content["select"]["order"], "orderDirection" => $content["select"]["orderDirection"]);
			
		$content = Category::GetCategoriesTree($params, $content, $level);
			
		$content = array_merge($content, Category::GetRelationsList('Category'));
		
		if($object)
			$content["object"] = $object->ID;
		
		Controller::AssignActions($this, $content);
		
		return $content;
		
	}

	public function edit(&$object){
		
		$content = array();

        $content = parent::edit($object);

		$content["paramgroups_array"] = (trim($content["paramgroups"]) !== "") ? explode(",", $content["paramgroups"]) : array();

		return $content;
		
	}

	public static function category_update_cache()
	{
		$dir = Model::$conf->cachePath.'/categories.'.Application::$language->name.'.cache';

		$params = new Parameters();
		$params->order = "pos";
		$params->ne->block = 1;

		$content = Category::GetList("Category", $params);
		
		$multiSelect = new Parameters();
		$multiSelect->categories->whereThis->parent_ID = 'ID';
		$multiSelect->categories->ne->block = 1;
		$multiSelect->categories->order = "pos";

		$multiSelect->categories2->whereThis->parent_ID = 'ID';
		$multiSelect->categories2->ne->block = 1;
		$multiSelect->categories2->order = "pos";
		
		$content = Category::GetListTree("Category", $params, $content, 'items', $multiSelect);

		foreach($content["items"] as $key => $value){
			$content["items"][$key]["_itemsTree"]["count"] = 0;
			foreach($content["items"][$key]["_itemsTree"]["items"] as $value2){
				$content["items"][$key]["_itemsTree"]["count"] += 1 + $value2["_itemsTree"]["count"];
			}
		}
		
		// Write cache
		file_put_contents($dir, serialize($content));

		$content = array("mess" => lang("Кэш успешно обновлён"));

        return array_merge(self::items("", array()), $content);
    }

	
	public function fast_edit_open($object, $field)
	{
		$content = array();
		
		$type = Category::GetColumnType("Category", $field);
		
		if($type == "VARCHAR" or $type == "TEXT" or $type == "DOUBLE"){
			$content["type"] = $type = "String";	
		}elseif($type == "INT"){
			$content["type"] = $type = "Int";	
		}if($type == "BOOL"){
			$content["type"] = $type = "Bool";	
		}
	
		$value = Category::GetObjectColumn("Category", $object, $field);
		if($value !== false){
			$content["value"] = $value;
		}

		$content["value"] = htmlspecialchars($content["value"]);

 		$content["object"] = $object;
        $content["fieldname"] = $field;

		return $content;
	}
	
	public function fast_edit_save($object, $field, $value)
	{
		$content = array();
		
		$type = Category::GetColumnType("Category", $field);
		
		$content["type"] = "Default";

		if($type == "BOOL"){
			$value = ($value == "true") ? 1 : 0;
			$content["type"] = "Bool";
		}
		if($field == "block"){
			$value = ($value == "true") ? 0 : 1;
			$content["type"] = "Bool";
		}
			
		Category::UpdateObjectColumn("Category", $object, $field, $value);
		
		$value = Category::GetObjectColumn("Category", $object, $field);
		
		if($value !== false){
			$content["value"] = $value;
		}

 		$content["object"] = $object;
        $content["fieldname"] = $field;

		return $content;
	}

}