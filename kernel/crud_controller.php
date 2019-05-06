<?php
abstract class crud_controller
{
	public $modelName;
	public $controllerName;

	public function index()
	{
    	$content = array();
		
        return $content;
	}

	public function items($params)
	{
		$content = array();

        $checker = new Checker('Parameters');
		list($params) = $checker->Get($params);

        if (!$params instanceof Parameters)
        	$params = new Parameters();
			
        $params->smart = 1;

        eval('$content = ' . $this->modelName . '::GetList($this->modelName, $params);');

        $content = array_merge($content, DatabaseObject::GetRelationsList($this->modelName, array(), $params));

		Controller::AssignActions($this, $content);
		
		//Controller::AssignAddons($this, $content);

		return $content;
	}

	public function edit(&$object)
	{
		$content = array();

        $checker = new Checker($this->modelName);
		list($object) = $checker->Get($object);
		
		if ($object instanceof DatabaseObject)
		{
        	$content = $object->GetInfoUnEscape(CMF_RELATION_SIMPLE_LIST);

			if($this->addons->images){
				$content["images"] = self::images($object);	
			}
			if($this->addons->param_values){
				eval('$content["param_values"] = $this->param_values($object);');	
			}
			if($this->addons->multiitem){
				eval('$content["multiitem"] = $this->multiitem($object);');	
			}
			if($this->addons->similar_items){
				eval('$content["similar_items"] = $this->similar_items($object->ID);');	
			}
		}
		else
		{
        	$content = DatabaseObject::GetRelationsList($this->modelName);

        	$tableColumns = DatabaseObject::GetTableColumns($this->modelName);
        	if(in_array('pos', $tableColumns))
        		$content['pos'] = DatabaseObject::GetMaxPos($this->modelName);

		}
		
		if(count($this->addons->file) > 0){
			foreach($this->addons->file as $group => $title){
				$content["cd_file_".$group] = $this->controllerName."/file/".$group."/".$title."/".$object->ID." files_".$group;
			}
		}

		$content = array_merge($content, DatabaseObject::GetSmartParameters($this->modelName));
		
        $recursiveRelations = DatabaseObject::GetRecursiveRelations($this->modelName);
		
        if (count($recursiveRelations) > 0)
        {
        	foreach ($recursiveRelations as $relation)
        	{
		        if (isset($content[$relation['name']]))
		        {
					$content['parent_select'] = $this->controllerName . '/parent_select/' . $content[$relation['name']];
					$content['parent_category_select'] = $this->controllerName . '/parent_category_select/' . $object->category_ID;
		        }
		        elseif (isset($content['where'][$relation['name']]))
		        {
		        	$content['parent_select'] = $this->controllerName . '/parent_select/' . $content['where'][$relation['name']];

					$content['parent_category_select'] = $this->controllerName . '/parent_category_select/' . $object->category_ID;
		        }
		        else
		        {
		        	$content['parent_select'] = $this->controllerName . '/parent_select/0';
					$content['parent_category_select'] = $this->controllerName . '/parent_category_select/0';
		        }
        	}
        }

		$content = array_merge($content, DatabaseObject::GetFields($this->modelName, ($object ? "edit" : "create")));

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
        	else{
				$content['mess'] = lang('Сохранение прошло успешно');
				
				$content["backhref"] = "/backend/".Application::$language->name."/index/link/".$this->controllerName."/items/";
			}
		}
		else
		{ 
        	eval('$object = ' . $this->modelName . '::Create($this->modelName, "create", $params, $errors);');  
			   
        	if (!$object)
          		$content = $errors->GetInfoUnEscape(); 
        	else{
				$content['mess'] = lang('Добавление прошло успешно');
				$content["backhref"] = "/backend/".Application::$language->name."/index/link/".$this->controllerName."/items/";
			}
				
				
		}

		return array_merge($this->edit($object), $content);
	}

	public function fast_save($params, $rqparams = NULL)
	{
		$content = array();

		$checker = new Checker('Parameters');
		list($params) = $checker->Get($params);
		$errors = new Parameters();

		eval($this->modelName . '::EditFast($this->modelName, "edit", $params, $errors);');
		$content['mess'] = lang('Сохранение прошло успешно');
		
		self::AssignRequestParams($rqparams);
		
		eval("\$result = array_merge(\$this->items(".$rqparams."), \$content);");
		
		return $result;
	}

	public function fast_delete($elements, $rqparams = NULL)
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

		self::AssignRequestParams($rqparams);
		
		eval("\$result = array_merge(\$this->items(".$rqparams."), \$content);");
		
		return $result;
	}

	public function fast_block($elements, $rqparams = NULL)
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
					$object->Block();
				}
			}

			$content['mess'] = lang('Элементы заблокированы успешно');
		}
		else
		{
			$content['err'] = 1;
			$content['mess'] = lang('Не выбрано ни одного элемента');
		}

		self::AssignRequestParams($rqparams);
		
		eval("\$result = array_merge(\$this->items(".$rqparams."), \$content);");
		
		return $result;
	}

	public function fast_unblock($elements, $rqparams = NULL)
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
					$object->UnBlock();
				}
			}

			$content['mess'] = lang('Элементы разблокированы успешно');
		}
		else
		{
			$content['err'] = 1;
			$content['mess'] = lang('Не выбрано ни одного элемента');
		}

		self::AssignRequestParams($rqparams);
		
		eval("\$result = array_merge(\$this->items(".$rqparams."), \$content);");
		
		return $result;
	}

	public function delete($object, $rqparams = NULL)
	{
		$content = array();

		$checker = new Checker($this->modelName);
		list($object) = $checker->Get($object);

		if ($object instanceof DatabaseObject)
		{
			eval($this->modelName . '::Delete($this->modelName, $object);');
			$content['mess'] = lang('Удаление прошло успешно');
		}

		self::AssignRequestParams($rqparams);
		
		eval("\$result = array_merge(\$this->items(".$rqparams."), \$content);");
		
		return $result;
	}

	public function delete_all($rqparams = NULL)
	{
		$content = array();
		
		eval($this->modelName . '::DeleteAllSimple($this->modelName);');
		
		self::AssignRequestParams($rqparams);
		
		eval("\$result = array_merge(\$this->items(".$rqparams."), \$content);");
		
		return $result;
	}

	public function images(&$object)
	{
		$content = array();

        $checker = new Checker($this->modelName);
		list($object) = $checker->Get($object);

        if($object)
        {
        	$content['ID'] = $object->ID;
        	$content['modelName'] = $this->modelName;
        	$content['base_image'] = $object->base_image;
        	$content['items'] = $object->GetImages();
        }

		return $content;
	}
	
	public function images_list(&$object)
	{
		
		$images = $this->images($object);
		
		return array("images" => $images, "base_image" => $images["base_image"]);
		
	}	

	public function save_image($object, $file)
	{
		$checker = new Checker($this->modelName, 'File');
		list($object, $file) = $checker->Get($object, $file);

		if ($object and $file){
			$content = $object->SaveImage($file);
			$content['mess'] = lang("Изображение добавлено успешно.");
		}else{
			$content['err'] = 1;
			$content['mess'] = lang("Не выбрано изображение.");
		}

		return $content;
	}
	
	public function save_images($object, $file)
	{
		$checker = new Checker($this->modelName, 'File');
		list($object, $file) = $checker->Get($object, $file);

		if ($object and $file){
			$content = $object->SaveImages($file);
			$content['mess'] = lang("Изображения успешно добавлены");
		}else{
			$content['err'] = 1;
			$content['mess'] = lang("Не выбраны изображения");
		}

		return $content;
	}

	public function save_images_positions($images, $object)
	{
		$content = array();
		
		$checker = new Checker($this->modelName);
		list($object) = $checker->Get($object);
	
		if(count($images) > 0 and $object){
			foreach($images as $key => $value){
				Model::$db->Query("UPDATE `Image` SET pos = ".$value." WHERE ID = '".$key."'");
			}
			$content["mess"] = lang("Позиция изменена");
			$content['base_image'] = $object->base_image;
		}else{
			$content["err"] = 1;
			$content["mess"] = lang("Не найдены элементы");
		}
		
		return array_merge(array("images" => $this->images($object)), $content);
	}

	public function delete_image($object, $name)
	{
		$content = array();

        $checker = new Checker($this->modelName, 'String');
		list($object, $name) = $checker->Get($object, $name);

		if ($object && $name)
		{
			$object->DeleteImage($name);
			list($object, $name) = $checker->Get($object, $name);
			$content["base_image"] = $object->base_image;
			$content['mess'] = lang("Изображение удалено успешно");
		}
		else
		{
			$content['err'] = 1;
			$content['mess'] = lang("Не выбрано изображение");
		}

		return array_merge(array("images" => $this->images($object)), $content);
	}
	
	public function delete_all_images($object)
	{
		$content = array();

        $checker = new Checker($this->modelName);
		list($object) = $checker->Get($object);

		if ($object)
		{
			$object->DeleteAllImages();
			$content['mess'] = lang("Удаление прошло успешно");
		}
		else
		{
			$content['err'] = 1;
			$content['mess'] = lang("Произошла ошибка при удалении");
		}

		return $content;
	}
	
	public function set_base_image($object, $name)
	{
		$content = array();

		$checker = new Checker($this->modelName, 'String');
		list($object, $name) = $checker->Get($object, $name);

		if ($object && $name)
		{
			$object->SetbaseImage($name);
			$content['mess'] = lang("Главное изображение установлено");
			$content['base_image'] = $name;
		}

		return array_merge(array("images" => $this->images($object)), $content);
	}
	
	public function file($group, $title, &$object)
	{
		$content = array();

		$title = str_replace("__", " ", $title);

        $checker = new Checker($this->modelName, "String", "String");
		list($object, $group, $title) = $checker->Get($object, $group, $title);
		
        if($object and $group)
        {
        	$content['ID'] = $object->ID;
        	$content['modelName'] = $this->modelName;
        	$content['items'] = $object->GetFiles($group);
        }
		
		$content['group'] = $group;
		$content['title'] = $title;

		return $content;
	}
	
	public function files($group, $title, &$object)
	{
		$content = array();

        $checker = new Checker($this->modelName, "String", "String");
		list($object, $group, $title) = $checker->Get($object, $group, $title);

        if($object and $group)
        {
        	$content['ID'] = $object->ID;
        	$content['modelName'] = $this->modelName;
        	$content['items'] = $object->GetFiles($group);
        }
		
		$content['group'] = $group;
		$content['title'] = $title;

		return $content;
	}
	
	
	public function files_list($group, $title, &$object)
	{
		return $this->files($group, $title, $object);
	}	

	public function save_file($object, $group, $file)
	{
		$checker = new Checker($this->modelName, "String", "File");
		list($object, $group, $file) = $checker->Get($object, $group, $file);

		if ($object and $group and $file){
			$content = $object->SaveFile($file, $group);
			$content['mess'] = lang("Файл успешно добавлен");
		}else{
			$content['err'] = 1;
			$content['mess'] = lang("Не выбран файл.");
		}

		return $content;
	}
	
	public function delete_file($object, $name, $group)
	{
		$content = array();

        $checker = new Checker($this->modelName, 'String', 'String');
		list($object, $name, $group) = $checker->Get($object, $name, $group);

		if ($object && $name && $group)
		{
			$object->DeleteFile($name, $group);
			$content['mess'] = lang("Файл успешно удалён");
		}
		else
		{
			$content['err'] = 1;
			$content['mess'] = lang("Не выбран файл");
		}

		return array_merge(array("images" => $this->images($object)), $content);
	}
	
	public function delete_all_files($object, $group)
	{
		$content = array();

        $checker = new Checker($this->modelName, 'String');
		list($object, $group) = $checker->Get($object, $group);

		if ($object && $group)
		{
			$object->DeleteAllFiles($group);
			$content['mess'] = lang("Удаление прошло успешно");
		}
		else
		{
			$content['err'] = 1;
			$content['mess'] = lang("Произошла ошибка при удалении");
		}

		return $content;
	}
	
	
	public function upload_banner($object, $file)
	{
		$content = array();

		$checker = new Checker('Banners', 'File');
		list($object, $file) = $checker->Get($object, $file);

		$new = false;

		if(!$object){
			$maxID = DatabaseObject::GetMaxColumnValue($this->modelName, 'ID');
			$object = new Banners($maxID);
			$new = true;
		}

		if ($file)
		{
			$object->SaveImages($file, $new);
			$content['mess'] = lang("Изображение добавлено успешно.");
		}
		else
		{
			$content['err'] = 1;
			$content['mess'] = lang("Не выбрано изображение.");
		}

		$content['imageName'] = $file->name;

		return $content;
	}

	public function delete_banner($elements)
	{
		$content = array();
		
		if(count($elements) > 0){
			foreach($elements as $key => $value){
			
				$checker = new Checker('Banners');
				list($object) = $checker->Get($value);
				
				if ($object)
				{
					$parent_ID = $object->parent_ID;
					$object->DeleteImage($object->banner);
					$object->DeleteBanner();
					$content['mess'] = lang("Изображение удалено успешно.");
				}
				else
				{
					$content['err'] = 1;
					$content['mess'] = lang("Не выбраны изображения.");
				}
				
			}
		}else{
			$content['mess'] = lang("Не выбраны изображения.");
		}
		
		$content['banners'] = $this->modelName."/banners/".$parent_ID." banners";
		
		return $content;
	}

	public function save_images_params($object, $params)
	{
		$content = array();

		$checker = new Checker($this->modelName);
		list($object) = $checker->Get($object);

		if ($object && is_array($params))
		{
			foreach ($params as $key => $value)
			{
				Model::$db->Query("UPDATE `Image` SET color = '" . Checker::Escape($value['color']) . "' WHERE ID = '" . $key . "'");
			}
		}

        $content['mess'] = 'Цвета сохранены успешно';
		return array_merge($this->images($object), $content);
	}
	
	public function AssignRequestParams(&$params){
		
		if(count($params) > 0){
			$params = implode(", ", $params);
		}else{
			$params = "NULL";
		}
		
	}
	
	public function export($elements, $rqparams = NULL, $model = false, $export_fields = array(), $encode_fields = NULL, $not_save_fields = NULL)
	{
		$content = array();

		if (is_array($elements) && count($elements) > 0)
		{	
			$currentModel = ($model) ? $model : $this->modelName;
			$result = Export::Export($elements, $currentModel, $export_fields, $encode_fields, $not_save_fields);
			if($result and trim($result) !== ""){
				$content["href"] = $result;
				$content['mess'] = lang('Загрузка начнётся через несколько секунд');
			}else{
				$content["err"] = 1;
				$content['mess'] = lang('Произошла ошибка при экспорте');
			}	
		}
		else
		{
			$content['err'] = 1;
			$content['mess'] = lang('Не выбрано ни одного элемента');
		}

		self::AssignRequestParams($rqparams);
		
		eval("\$result = array_merge(\$this->items(".$rqparams."), \$content);");
		
		return $result;
	}
	
	public function copy($object)
	{
		$content = array();
		
		$checker = new Checker($this->modelName);
		list($object) = $checker->Get($object);
		
		if($object){
			$content = $object->CopyObject($this->modelName, $object);
		}
		
		return $content;
	}

}

?>