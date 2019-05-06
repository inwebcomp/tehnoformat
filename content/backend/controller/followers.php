<?php
class controller_followers extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Followers';
		$this->controllerName = 'followers';
		
		// Notifications
		$this->notifications = true;
		
		// Действия
		$this->actions["fast_save"] = false;
	}

	public function _notification(){
		$count = Model::$db->Value("SELECT COUNT(ID) FROM Followers WHERE DATE(created) > '".date("Y-m-d", time() - 60 * 60 * 24 * 2)."'");	
		
		return ($count > 0) ? $count : NULL; 
	}
	
	public function items($params){
		
		if(!isset($params["order"]))
			$params["order"] = "created"; 
		
		return parent::items($params);
			
	}
}

?>