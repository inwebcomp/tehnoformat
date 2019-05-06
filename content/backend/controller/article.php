<?php

class controller_article extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Article';
        $this->controllerName = 'Article';
		
		// Addons
		$this->addons = new stdClass();
		$this->addons->images = true;
	}

	public function items($params){
		
		if(!isset($params["orderDirection"]) and !isset($params["order"])){
			$params["order"] = "created"; 
			$params["orderDirection"] = "DESC"; 
		}
		
		return parent::items($params);
			
	}
	
}

?>