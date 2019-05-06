<?php

class controller_product_groups extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'ProductGroups';
        $this->controllerName = 'product_groups';
	}
	
	public function edit(&$object){
		
		$content = parent::edit($object);
		
		if($object)
			$category_ID = ProductGroups::GetItemsCategory($content);
		
		$content["select_item"] = "product_groups/select_items/".(isset($category_ID) ? $category_ID : 1).($content["ID"] ? "/".$content["ID"] : "");

		return $content;
		
	}
	
	public function select_items($category_ID, $object)
	{
		$content = array();

		$checker = new Checker("Category");
		list($category) = $checker->Get($category_ID);

		if($category){
			$content["path"] = $category->GetPathFilter();
			$content["parent_ID"] = $category->ID;
			if ($category->level == 2)
			{
				$params = new Parameters();
				$params->onPage = 100000;
				$params->where->category_ID = $category->ID;
				$content["products"] = Item::GetList("Item", $params);
			}
		}
	
		$content["object_ID"] = ((int)$object) ? (int)$object : NULL;
		
		$checker = new Checker("ProductGroups");
		list($group) = $checker->Get($object);
		if($group)
			$content["items_array"] = (trim($group->items) !== "") ? explode(",", $group->items) : array();

		return $content;
	}
	
}

?>