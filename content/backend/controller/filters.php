<?php
class controller_filters extends crud_controller_tree {
	
	public function __construct()
	{
		$this->modelName = 'Filters';
		$this->controllerName = 'filters';
	}


	public function items($object, $params = array())
	{
       	$content = array();
		
        $checker = new Checker('Category', 'Parameters');
		list($object, $params) = $checker->Get($object, $params);
		
        if (!$params instanceof Parameters)
        	$params = new Parameters();

		if(!$object){
			$object = new Category(1);
		}
			

		if($object){
			$params->order = "pos";
			$params->in->category_ID = "(".Category::GetParentsAs($object->ID ,'String').")";
			
			$params->freeWhere = " AND (Filters.not_in_children != 1 OR category_ID = '".$object->ID."')";
			
			$content = Filters::GetList('Filters', $params);
			
			if($content['count'] > 0){
				foreach($content['items'] as $key => $value){
					$type = "";
					
					if($value["type"] == "list")
						$type = lang("Флажки");
					elseif($value["type"] == "radio")
						$type = lang("Переключатель");
					elseif($value["type"] == "interval")
						$type = lang("Интервал");
					elseif($value["type"] == "slider")
						$type = lang("Слайдер");
						
					$content['items'][$key]['type'] = $type;
				}
			}

			$content["object"] = $object->ID;
			
			$content["_fast_add_value"] = "0/".$object->ID;
	
			if($object->ID !== "1"){
				$content["header"]["title"] = lang("Фильтры категории");
				$content["header"]["sub_title"] = $object->GetMetaCategory();
			}else{
				$content["header"]["title"] = lang("Фильтры всех категорий");
			}
		}else{
			$content['items'] = array();
			$content['select'] = array();
		}
		
		$content = array_merge($content, DatabaseObject::GetRelationsList($this->modelName));
		
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
			$catID = $object->category_ID;	
		}
		
		$checker = new Checker('Category');
		list($category) = $checker->Get($catID);
		
		$params = new Parameters();
		$params->where->paramgroup_ID = $category->paramgroup_ID;
		$params->order = "title";
		$params->ne->block = 1;
		
		if($object){
			$content = array_merge($content, self::get_val_list($content['param'], $content['category_ID']));
		}
		


		$paramList = Param::GetList('Param', $params);

		$content['params'] = array();

		if (count($paramList['items'])) {
			foreach ($paramList['items'] as $param) {
				$content['params'][] = array(
					'title' => $param['title'],
					'param' => 'p_ml.'.$param['name']
				);
			}
		}
		
		// Custom params
		$content['params'][] = array(
			'title' => lang('Категория'),
			'param' => 'i.category_ID'
		);
		$content['params'][] = array(
			'title' => lang('Город'),
			'param' => 'i.location_ID'
		);
		$content['params'][] = array(
			'title' => lang('Район'),
			'param' => 'i.district_ID'
		);
		$content['params'][] = array(
			'title' => lang('Длительность'),
			'param' => 'i.duration'
		);
		$content['params'][] = array(
			'title' => lang('Цена'),
			'param' => 'i.price'
		);
		


		$content['category_ID'] = $content["_object_back_value"] = ($category) ? $category->ID : $content['category_ID'];

		return $content;
	}
	
	public function get_param_name($param, $category)
	{
       	$content = array();

        $checker = new Checker('Param', 'Category');
		list($param, $category) = $checker->Get($param, $category);
		
		if($param && $category){

			$params = new Parameters();
			$params->where->category_ID = $category->ID;
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