<?php
class controller_paramgroups extends crud_controller
{
	public function __construct()
	{
		$this->modelName = 'Paramgroup';
		$this->controllerName = 'paramgroups';
	}

	public function edit(&$object)
	{
		$content = array();
		$content = parent::edit($object);

		if ($object)
		{
			$content['params_controller'] = 'paramgroups/params/' . $object->ID . ' params';
			$content['param_edit'] = 'paramgroups/params_edit/' . $object->ID . ' params_edit';
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
			$oldName = $object->name;
			if (!$object->Edit($this->modelName, 'edit', $params, $errors))
          		$content = $errors->GetInfoUnEscape();
        	else{
				if ($oldName != $object->name)
				{
					$oldTableName = 'Paramvalue_' . $oldName;
					$tableName = 'Paramvalue_' . $object->name;

					Model::$db->Query('RENAME TABLE `' . $oldTableName . '` TO `' . $tableName . '`');

					$langTable = $tableName . '_ml';
					$oldLanguageTableName = $oldTableName . '_ml';
					Model::$db->Query('RENAME TABLE `' . $oldLanguageTableName . '` TO `' . $langTable . '`');

                }

				$content['mess'] = lang('Сохранение прошло успешно');
			}
		}
		else
		{
        	eval('$object = ' . $this->modelName . '::Create($this->modelName, "create", $params, $errors);');

			if (!$object)
          		$content = $errors->GetInfoUnEscape();
        	else
        	{
				$tableName = 'Paramvalue_' . $object->name;
                $langTable = $tableName . '_ml';
				
				Model::$db->Query('CREATE TABLE `' . $tableName . '` (ID INT PRIMARY KEY AUTO_INCREMENT, model VARCHAR(255), object_ID INT)');
				Model::$db->Query('ALTER TABLE `' . $tableName . '` ADD UNIQUE ( `model` , `object_ID` )');


				Model::$db->Query('CREATE TABLE `' . $langTable . '` (ID INT NOT NULL, lang_ID INT NOT NULL)');
				Model::$db->Query('ALTER TABLE `' . $langTable . '` ADD INDEX (`ID`)');
				Model::$db->Query('ALTER TABLE `' . $langTable . '` ADD FOREIGN KEY (`ID`) REFERENCES `' . $tableName . '` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE');
				Model::$db->Query('ALTER TABLE `' . $langTable . '` ADD UNIQUE ( `ID` , `lang_ID` )');
				Model::$db->Query('ALTER TABLE `' . $langTable . '` ADD FOREIGN KEY (`lang_ID`) REFERENCES `Language` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE');

				$content['mess'] = lang('Сохранение прошло успешно');
			}
		}

		return array_merge($this->edit($object), $content);
	}

	public function delete($object)
	{
		$content = array();

		$checker = new Checker($this->modelName);
		list($object) = $checker->Get($object);

		if ($object instanceof DatabaseObject)
		{
			$tableName = 'Paramvalue_' . $object->name;
			$langTable = $tableName . '_ml';
			Model::$db->Query('DROP TABLE `' . $langTable . '`');

			Model::$db->Query('DROP TABLE `' . $tableName . '`');
			
			Model::$db->Query("DELETE Param_ml
				FROM Param_ml
				INNER JOIN Param ON Param_ml.ID = Param.ID
				WHERE Param.paramgroup_ID = '".$object->ID."'");
				
			Model::$db->Query("DELETE FROM Param WHERE paramgroup_ID = '".$object->ID."'");
			
			eval($this->modelName . '::Delete($this->modelName, $object);');

			$content['mess'] = lang('Удаление прошло успешно');
		}

		return array_merge($this->items(NULL), $content);
	}

	public function fast_delete($elements)
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
					$tableName = 'Paramvalue_' . $object->name;
					$langTable = $tableName . '_ml';

					Model::$db->Query('DROP TABLE `' . $langTable . '`');

					Model::$db->Query('DROP TABLE `' . $tableName . '`');
				
					Model::$db->Query("DELETE Param_ml
						FROM Param_ml
						INNER JOIN Param ON Param_ml.ID = Param.ID
						WHERE Param.paramgroup_ID = '".$object->ID."'");
						
					Model::$db->Query("DELETE FROM Param WHERE paramgroup_ID = '".$object->ID."'");

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

		return array_merge($this->items(NULL), $content);
	}


	public function params($object)
	{
		$content = array();

        $checker = new Checker($this->modelName);
		list($object) = $checker->Get($object);

        if ($object)
        {
        	$params = new Parameters();
        	$params->onPage = 10000;
            $params->eq->paramgroup_ID = $object->ID;

        	$content['paramgroup_ID'] = $object->ID;
        	$content['params'] = $object->GetParams();
        }

		return $content;
	}
	
	public function params_edit($object, $paramID)
	{
		$content = array();

        $checker = new Checker($this->modelName, "Param");
		list($object, $param) = $checker->Get($object, $paramID);

        if($object)
        { 
			if($param){
				$content = $param->GetInfo();
				$content['isset_item'] = 1;
				$content['paramID'] = $param->ID;
			}else{
				$content['pos'] = DatabaseObject::GetMaxColumnValue("Param", "pos", " paramgroup_ID='".$object->ID."'") + 10;
			}
        }
		
		$content['paramgroup_ID'] = $object->ID;

		return $content;
	}

	public function save_param($object, $params, $paramID)
	{
		$content = array();

        $checker = new Checker($this->modelName, 'Parameters', 'Param');
		list($object, $params, $paramID) = $checker->Get($object, $params, $paramID);


        if ($object)
        {
			
			if($paramID){
				
				$errors = new Parameters();
				
				$type = $paramID->type;
	
				$checker = new Checker('SqlTypeByDataType');
				list($type) = $checker->Get($type);
				
				$oldParamName = $paramID->name;

				if($oldParamName !== $params->name->Val()){
					//$q = Model::$db->Query("ALTER TABLE `Paramvalue_".$object->name."_ml` CHANGE COLUMN `".$oldParamName."` `".$params->name->Val()."` ".$type);
				}else{
					$eq = true;	
				}
				//if($q or $eq){
					if(!$paramID->Edit('Param', 'edit', $params, $errors)){
						$content["err"] = 1;
						$content['mess'] = lang('Произашла ошибка при сохранении');
					}else{ 
						if($oldParamName !== $errors->name->Val()){
							$q = Model::$db->Query("ALTER TABLE `Paramvalue_".$object->name."_ml` CHANGE COLUMN `".$oldParamName."` `".$errors->name->Val()."` ".$type);
						}

						$content['mess'] = lang('Сохранение прошло успешно');
					}
				/*}else{
					$content["err"] = 1;
					$content['mess'] = lang('Произашла ошибка при сохранении');
				}*/
				
			}else{
			
			
				$errors = new Parameters();
				$paramObj = Param::Create('Param', "create", $params, $errors);

				if (!$paramObj)
				{
					$content = $errors->GetInfoUnEscape();
					$content["err"] = 1;
					$content['mess'] = lang('Не заполнены обязательные поля');
				}
				else
				{
					$tableName = 'Paramvalue_' . $object->name;
					$langTable = $tableName . '_ml';
	
					$type = $paramObj->type;
	
					$checker = new Checker('SqlTypeByDataType');
					list($type) = $checker->Get($type);
					if ($type)
					{
						Model::$db->Query('ALTER TABLE `' . $langTable . '` ADD `' . $paramObj->name . '` ' . $type);
	
						//Param::Add($params, "User");
	
						$content['mess'] = lang('Добавление прошло успешно');
					}
					else
					{
						$errors->err = 1;
						$errors->err_type = 1;
						$content = $errors->GetInfoUnEscape();
						Param::Delete('Param', $paramObj);
						$paramObj = NULL;
						$content["err"] = 1;
						$content['mess'] = lang('Не заполнены обязательные поля');
					}
	
				}
				
			}
        }

		$content['_param'] = 1;
		return array_merge($this->params($object), $content);
	}


	public function fast_params_delete($object, $elements)
	{
		$content = array();

        $checker = new Checker($this->modelName);
		list($object) = $checker->Get($object);

		if (is_array($elements) && count($elements) > 0)
		{
			foreach ($elements as $param)
			{
				$checker = new Checker('Param');
				list($param) = $checker->Get($param);

				if ($param instanceof DatabaseObject)
				{
					$tableName = 'Paramvalue_' . $object->name;
					$langTable = $tableName . '_ml';
					Model::$db->Query('ALTER TABLE  `' . $langTable . '` DROP `' . $param->name . '`');
					
					Model::$db->Query("DELETE Param_ml
						FROM Param_ml
						INNER JOIN Param ON Param_ml.ID = Param.ID
						WHERE Param.name = '".$param->name."'");
						
					Model::$db->Query("DELETE FROM Param WHERE name = '".$param->name."'");

					Param::Delete('Param', $param);

				}
			}
		}
		$content['mess'] = lang('Удаление прошло успешно');

		return array_merge($this->params($object), $content);
	}

	public function fast_params_save($object, $params)
	{
		$content = array();

        $checker = new Checker('Parameters');
		list($params) = $checker->Get($params);
		$errors = new Parameters();

		Param::EditFast('Param', "edit_fast", $params, $errors);
		if($errors->err !== 1){
			$content['mess'] = lang('Сохранение прошло успешно');
		}else{
			$content['mess'] = lang('Произошла ошибка валидации данных');
		}
		
		return array_merge($this->params($object), $content);
	}

}

?>