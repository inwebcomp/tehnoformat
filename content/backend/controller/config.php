<?php
class controller_config extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Config';
		$this->controllerName = 'config';
		
		// Addons
		$this->addons->file["Mediafiles"] = lang("Файл");
		
		// Titles
		$this->pageTitle = lang("Конфигурация");
	}
	
	public function items($object = 0, $params = array())
	{
		if(!is_array($params))
			$params = array();
		
		$checker = new Checker('Configgroups');
		list($object) = $checker->Get($object);
		
		$params["where"]["group_ID"] = ($object) ? $object->ID : _NULL;
			
		$content = parent::items($params);

		$checker2 = new Checker('Config');
		foreach($content['items'] as $key => $value){
			list($group) = $checker->Get($value['group_ID']);
			$content['items'][$key]['group'] = $group->title;
			
			list($file) = $checker2->Get($value['ID']);
			$files = $file->GetFiles("Mediafiles");
			if(count($files) > 0){
				$content['items'][$key]['file'] = $files[0];
			}
		}
		
		if($object)
			$content["group_ID"] = $object->ID;
		
		Controller::AssignActions($this, $content);
		
		return $content;
	}
	
	public function edit(&$object)
	{
		$content = parent::edit($object);

		switch($content["name"]){
			case "default_language":
				foreach(Language::GetLanguages() as $value){
					$content["list"][] = array(
						"title" => $value["title"],
						"value" => $value["name"]
					);
				}
			break;
			case "default_timezone":
				$timezones = DateTimeZone::listIdentifiers();
				foreach($timezones as $value){
					$content["list"][] = array(
						"title" => $value,
						"value" => $value
					);
				}
			break;	
		}
		
		if(strpos($content["name"], "animation_effect") !== false){
			$content["list"] = Utils::GetAnimationsList();
		}

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
			if(!$params->value->Val()){
				$params->value = "0";	
			}else if($params->value->Val() == "on"){
				$params->value = 1;	
			}
			
			if (!$object->Edit($this->modelName, 'edit', $params, $errors))
          	{
   				$content = $errors->GetInfoUnEscape();
        	}
        	else{
				$content['mess'] = lang('Сохранение прошло успешно');
				
				$content["backhref"] = "/backend/".Application::$language->name."/index/link/".$this->controllerName."/items/".$errors->group_ID->Val();
			}
		}
		else
		{ 
        	eval('$object = ' . $this->modelName . '::Create($this->modelName, "create", $params, $errors);');  
			  
        	if (!$object)
          		$content = $errors->GetInfoUnEscape(); 
        	else{
				$content['mess'] = lang('Добавление прошло успешно');
				$content["backhref"] = "/backend/".Application::$language->name."/index/link/".$this->controllerName."/items/".$errors->group_ID->Val();
			}
		}

		return array_merge($this->edit($object), $content);
	}
	
}

?>