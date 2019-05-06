<?php

class controller_category extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Category';
        $this->controllerName = 'category';
		
		// Addons
		$this->addons->file["Icons"] = lang("Иконка");
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
	
		$content = array_merge($content, Category::GetRelationsList('Category'));
		
		if($object)
			$content["object"] = $object->ID;
		
		Controller::AssignActions($this, $content);
		
		return $content;
		
	}


}

?>