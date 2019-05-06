<?php

class controller_mail extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Mail';
		$this->controllerName = 'mail';
		
		// Действия
		$this->actions["fast_add"] = false;
		$this->actions["fast_block"] = false;
		$this->actions["fast_unblock"] = false;
		$this->actions["fast_save"] = false;
		
		// Notifications
		$this->notifications = true;
	}
	
	public function _notification(){
		$count = Model::$db->Value("SELECT COUNT(ID) FROM Mail WHERE `read` = 0");	
		
		return ($count > 0) ? $count : NULL; 
	}
	
	public function items($params){

		$params["order"] = "created";
		$params["orderDirection"] = "DESC";
		
		return parent::items($params);
			
	}
	
	public function edit($object){

		Mail::SetRead($object);
		
		return parent::edit($object);
			
	}
	
	public function save($object, $params){
		
		if((int)$object == 0) return array();
		
		return parent::save($object, $params);
			
	}

}

?>