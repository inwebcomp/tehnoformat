<?php

class controller_visits extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Visits';
        $this->controllerName = 'visits';
		
		// Действия
		$this->actions["fast_save"] = false;
	}

	public function items($params){
		
		$params["order"] = "updated"; 
		$params["orderDirection"] = "DESC"; 
		
		return parent::items($params);
			
	}

}

?>