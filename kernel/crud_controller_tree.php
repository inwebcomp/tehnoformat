<?php
abstract class crud_controller_tree extends crud_controller
{
	public function parent_select($parent_ID)
	{
		$content = array();

		$checker = new Checker($this->modelName);
		list($object) = $checker->Get($parent_ID);
		
		if($object){
			$content['path'] = $object->GetPathFilter();
			$content['parent_ID'] = $object->ID;
		}

		return $content;
	}
	
	public function parent_category_select($object)
	{
		$content = array();

		$object = (!$object and Model::$session->info["cmf_Item_default_where"]["category_ID"]) ? Model::$session->info["cmf_Item_default_where"]["category_ID"] : $object;
		
		$checker = new Checker("Category");
		list($object) = $checker->Get($object);
		
		if(!$object){
			list($object) = $checker->Get(1);	
		}
		
		if($object){
			$content['path'] = $object->GetPathFilter();
			$content['category_ID'] = $object->ID;
		}

		return $content;
	}
	
	public function parent_category_select_accessory($object, $ID)
	{
		$content = array();

		$ID = ($object) ? $object : $ID;
		$table = ($object) ? "ob_accessory" : "Category_Accessory";

		$checker = new Checker($table);
		list($item) = $checker->Get($ID);

		$result = ($object) ? $item->category_ID : $item->ID;
		
		$checker = new Checker("Category_Accessory");
		list($model) = $checker->Get($result);
		if($model){
			$content['path'] = $model->GetPathFilter();
			$content['category_ID'] = $model->ID;
		}

		return $content;
	}

	public function items($params)
	{
		$content = array();

        $checker = new Checker('Parameters');
		list($params) = $checker->Get($params);

        if (!$params instanceof Parameters)
        	$params = new Parameters();

  		return parent::items($params);
  	}
}

?>