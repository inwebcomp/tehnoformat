<?php

class controller_rights extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Rights';
        $this->controllerName = 'rights';
	}

	public function rights($object){
		
		$content = array();
		
		$checker = new Checker('Usergroup');
		list($object) = $checker->Get($object);
		
		if($object){
			$content = $object->GetInfoUnEscape();
			
			$params = new Parameters();
			$params->where->name = $object->name;
	
			$content['rights'] = Rights::GetList('Rights', $params);
			$content['rights_add'] = array();
		}
		
		return $content;
			
	}
	
	public function rights_form($object){
		
		$content = array();
		
		$checker = new Checker('Usergroup');
		list($object) = $checker->Get($object);
		
		if($object){
			$content["ID"] = $object->ID;
			$content["name"] = $object->name;
		}
		
		return $content;
			
	}
	
	public function add_rights($params){
		
		$content = array();
		
		$checker = new Checker('Parameters');
		list($params) = $checker->Get($params);
		
		$errors = new Parameters();
		
		$object = Rights::Create($this->modelName, 'create', $params, $errors);
		
		if(!$object){
			$content = $errors->GetInfoUnEscape();	
		}else{
			$content['mess'] = lang('Добавление прошло успешно');
		}
		
		$listParams = new Parameters();
		$listParams->where->name = $params->name;
		
		$content['rights'] = Rights::GetList('Rights', $listParams);
		$content['rights_add'] = array();
		$content["ID"] = $object->group_ID;
		
		return $content;
			
	}
	
	public function fast_delete($ID, $elements)
	{
		$content = array();

		if (is_array($elements) && count($elements) > 0)
		{
			foreach ($elements as $object)
			{
				$checker = new Checker($this->modelName);
				list($object) = $checker->Get($object);

				if ($object instanceof DatabaseObject)
				{
					eval($this->modelName . '::Delete($this->modelName, $object);');
				}
			}

			$content['mess'] = lang('Удаление прошло успешно');
		}
		else
		{
			$content['err'] = 1;
			$content['mess'] = lang('Не выбраны элементы для удаления');
		}

		return array_merge($this->rights($ID), $content);
	}

	public function fast_save($ID, $params)
	{
		$content = array();

		$params = new Parameters($params);
		
		$errors = new Parameters();

		Param::EditFast('Rights', "edit", $params, $errors);
		
		if($errors->err !== 1){
			$content['mess'] = lang('Сохранение прошло успешно');
		}else{
			$content['mess'] = lang('Произошла ошибка валидации данных');
		}
		
		return array_merge($this->rights($ID), $content);
	}

}

?>