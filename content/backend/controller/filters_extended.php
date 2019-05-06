<?php
class controller_filters_extended extends crud_controller_tree {
	
	public function __construct()
	{
		$this->modelName = 'FiltersExtended';
		$this->controllerName = 'filters_extended';
	}


	public function items($object, $params)
	{
       	$content = array();
		
        $checker = new Checker('Category', 'Parameters');
		list($object, $params) = $checker->Get($object, $params);
		
        if (!$params instanceof Parameters)
        	$params = new Parameters();

		if(!$object)
			$object = new Category(1);

		if($object){
			$params->order = "param_name";
			$params->group = "param";
			$params->in->category = "(".Category::GetParentsAs($object->ID ,'String').")";
			
			$content = Filters::GetList('Filters', $params);
			
			if($content['count'] > 0){
		
				foreach($content['items'] as $key => $value){
					$newParams = new Parameters();
					$newParams->order = "pos";
					$newParams->where->param = $value['param'];
					$content['items'][$key]['_itemsTree'] = Filters::GetList('Filters', $newParams);
				}
			
			}

			$content['object'] = $object->ID;
			
		}else{
			$content['items'] = array();
			$content['select'] = array();
		}
		
		Controller::AssignActions($this, $content);
		
		return $content;
	}
	
	public function edit($object, $sec_object = '')
	{
       	$content = array();

        $checker = new Checker('Filters', 'Category');
		list($object, $sec_object) = $checker->Get($object, $sec_object);
		
		if($object)
			$content = $object->GetInfoUnEscape();
		
		if (!$object instanceof DatabaseObject)
		{
        	$tableColumns = DatabaseObject::GetTableColumns($this->modelName);
        	if (in_array('pos', $tableColumns))
        		$content['pos'] = DatabaseObject::GetMaxPos($this->modelName);
		}
		
		$catID = $sec_object->ID;
		
		if($catID == ""){
			$catID = $object->category;	
		}
		
		$checker = new Checker('Category');
		list($category) = $checker->Get($catID);
		
		$params = new Parameters();
		$params->where->paramgroup_ID = $category->paramgroup_ID;
		$params->order = "title";
		
		if($object){
			$content = array_merge($content, self::get_val_list($content['param'], $content['category']));
		}
		
		$content['param_list'] = Param::GetList('Param', $params);
		
		$content['category'] = ($category->ID) ? $category->ID : $content['category'];

		return $content;
	}
	
	public function get_param_name($param, $category)
	{
       	$content = array();

        $checker = new Checker('Param', 'Category');
		list($param, $category) = $checker->Get($param, $category);
		
		if($param && $category){

			$params = new Parameters();
			$params->where->category = $category->ID;
			$params->where->param = $param->name;
			$params->group = "param";

			$content = Filters::GetList('Filters', $params);
			
			$content['param_name'] = $content['items'][1]['param_name'];
			
		}else{
			$content['param_name'] = "";	
		}

		return $content;
	}
	
	public function get_val_list($param, $category)
	{
       	$content = array();

        $checker = new Checker('Param', 'Category');
		list($param, $category) = $checker->Get($param, $category);
		
		if($param && $category){

			$checker = new Checker('Paramgroup');
			list($paramgroup) = $checker->Get($category->paramgroup_ID);

			$q = Model::$db->Query("SELECT ".$param->name." FROM Paramvalue_".$paramgroup->name."_ml WHERE lang_ID = '".Application::$language->ID."' GROUP BY ".$param->name); 
			
			while($arr = Model::$db->Fetch($q)){
				
				$content['val_list'][]['val'] = $arr[$param->name];
				
			} 
		}

		return $content;
	}

}

?>