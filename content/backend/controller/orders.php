<?php
define("STATUS_VERIFY", 0);
define("STATUS_WAITING_FOR_PAYMENT", 1);
define("STATUS_IN_WORK", 2);
define("STATUS_DONE", 3);
define("STATUS_REFUSED", 4);

class controller_orders extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Orders';
		$this->controllerName = 'orders';
		
		// Notifications
		$this->notifications = true;
		
		// Действия
		$this->actions["fast_add"] = false;
		$this->actions["fast_save"] = false;
		$this->actions["fast_block"] = false;
		$this->actions["fast_unblock"] = false;
	}
	
	public function GetOrdersCount($type = "verify"){
		
		if($type == "verify"){
			$count = Model::$db->Value("SELECT COUNT(ID) FROM Orders WHERE status = ".STATUS_VERIFY);	
		}elseif($type == "payment"){
			$count = Model::$db->Value("SELECT COUNT(ID) FROM Orders WHERE status = ".STATUS_WAITING_FOR_PAYMENT);	
		}elseif($type == "in_work"){
			$count = Model::$db->Value("SELECT COUNT(ID) FROM Orders WHERE status = ".STATUS_IN_WORK);	
		}elseif($type == "done"){
			$count = Model::$db->Value("SELECT COUNT(ID) FROM Orders WHERE status = ".STATUS_DONE);	
		}elseif($type == "refused"){
			$count = Model::$db->Value("SELECT COUNT(ID) FROM Orders WHERE status = ".STATUS_REFUSED);	
		}
		
		return ($count) ? $count : false; 
		
	}
	
	public function items($params){
		
		if(!isset($params["order"]))
			$params["order"] = "created"; 
			
		if(!isset($params["orderDirection"]))
			$params["orderDirection"] = "DESC"; 
		
		return parent::items($params);
			
	}
	
	public function edit($object){
		
		$content = parent::edit($object);
		
		$items = unserialize(base64_decode($content["items"]));
		
		if(is_array($items))
			$content = array_merge($content, Orders::GetItems($items));
		else
			$content["items"] = array();
		
		return $content;
			
	}
}

?>