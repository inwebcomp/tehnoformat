<?php

class controller_shares extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Shares';
        $this->controllerName = 'shares';
	}
	
	public function items($params){
		
		$content = parent::items($params);
       
		foreach($content["items"] as &$value){
			$value["time"] = Utils::SecondsToString(strtotime($value["created"]) + $value["time"] * 3600 - time());
		}
		
		return $content;
		
	}
	
	public function edit(&$object){
		
		$content = parent::edit($object);
		
		$item = Shares::GetItem($content["object_ID"]);
		
		$content["select_item"] = "item/select_item/".($item ? $item->category_ID : 1).($item ? "/".$item->ID : "");
			
		return $content;
		
	}
	
	public function save($object, $params){
		
		if($params["type"] !== "gift"){
			$params["object_ID"] = "0";
		}
		
		return parent::save($object, $params);
		
	}
	
	public function shares($object){
		
		$content = array();
		
		$params = new Parameters();
		$params->order = "pos";
		
		$content = Shares::GetList("Shares", $params);
		
		$share = Shares::GetShares($object, false, true, true);
		if($share){
			$content["share_ID"] = $share->ID;	
		}
		
		$content["ID"] = $object;
		
		return $content;
		
	}
	
	public function shares_save($object_ID, $share_ID){
		
		$content = array();
	
		Shares::AddShareToItem($object_ID, $share_ID);
		
		return array_merge($content, self::shares($object_ID));
		
	}

}

?>