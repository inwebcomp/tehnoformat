<?php

class controller_banners extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Banners';
		$this->controllerName = 'banners';
	}
	
	public function edit($object, $bannerplace){
		
		$content = parent::edit($object);
		
		$checker = new Checker("Bannerplace");
		list($bannerplace) = $checker->Get(($content["parent_ID"] ? $content["parent_ID"] : $bannerplace));
		if($bannerplace and $bannerplace->with_text == 1){
			$content["with_text"] = 1;	
			$content["parent_ID"] = $bannerplace->ID;
		}
		
		$content["images"] = self::images($object);	
	
		return $content;
			
	}
	
	public function save($object, $params)
	{
		$content = array();

		$checker = new Checker($this->modelName, 'Parameters');
		list($object, $params) = $checker->Get($object, $params);
		$errors = new Parameters();

		if ($object instanceof DatabaseObject)
		{
			if (!$object->Edit($this->modelName, 'edit', $params, $errors))
          	{
   				$content = $errors->GetInfoUnEscape();
        	}
        	else
				$content['mess'] = lang('Сохранение прошло успешно');
		}
		else
		{ 
        	eval('$object = ' . $this->modelName . '::Create($this->modelName, "create", $params, $errors);');  
			   
        	if (!$object)
          		$content = $errors->GetInfoUnEscape(); 
        	else
				$content['mess'] = lang('Добавление прошло успешно');
		}

		return array_merge($this->edit($object, $content["parent_ID"]), $content);
	}
	
	public function items($object, $params = array())
	{
		$content = array();

        $checker = new Checker('Parameters', 'Bannerplace');
		list($params, $object) = $checker->Get($params, $object);

		if($object){
			if (!$params instanceof Parameters)
				$params = new Parameters();
			
			$params->where->parent_ID = $object->ID;
			
			$params->smart = 1;
	
			eval('$content = ' . $this->modelName . '::GetList($this->modelName, $params);');

			$content = array_merge($content, DatabaseObject::GetRelationsList($this->modelName, array(), $params));
			
			$content["bannerplace"] = $object->ID;
		}

		Controller::AssignActions($this, $content);
		
		return $content;
	}
}

?>