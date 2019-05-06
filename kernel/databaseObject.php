<?php

abstract class DatabaseObject extends StaticDatabaseObject
{
	protected $modelName;
	protected $info;

	public function __construct($ID = '')
	{
        $ID = trim($ID);
        
		if (!preg_match('#^[0-9]+$#isu', $ID)){
			if(!self::isMultilangColumn($this->modelName, "name") or $this->modelName == "Language"){
        		$ID = Model::$db->Value("SELECT ID FROM `" . $this->modelName . "` WHERE name = '" . $ID . "'");
			}else{
				$ID = Model::$db->Value("SELECT ID FROM `" . $this->modelName . "_ml` WHERE name = '" . $ID . "' AND lang_ID = '".Application::$language->ID."'");
			}
			
			if($this->modelName == "User"){
				$ID = Model::$db->Value("SELECT ID FROM `" . $this->modelName . "` WHERE login = '" . $ID . "'");
			}
		}
			
        if(!self::IfMultilangTable($this->modelName))
        { 
        	$this->info = Model::$db->Row("SELECT * FROM `" . $this->modelName . "` WHERE ID = '" . $ID . "'"); 
        }
        else
        {
        	$currentLanguage_ID = Application::$language->ID;

        	$this->info = Model::$db->Row("SELECT `" . $this->modelName . "`.*, `" . $this->modelName . "_ml`.* FROM `" . $this->modelName . "` LEFT JOIN `" . $this->modelName . "_ml` ON `" . $this->modelName . "`.ID = `" . $this->modelName . "_ml`.ID AND `" . $this->modelName . "_ml`.lang_ID = '" . $currentLanguage_ID . "' WHERE `" . $this->modelName . "`.ID = '" . $ID . "'");

        }

		if (!is_array($this->info))
		{
			throw new Exception(lang('Объект не зарегистрирован.') . '[model: ' . $this->modelName . ', ID: ' . $ID . ']');
        }
		
	}

	public function __get($name)
    { 
		// if ($name == 'modelName' and $this->modelName === null){ echo 'GetMOdel';
		// 	return static::getEntityName();
		// }

		// if ($name == 'controllerName' and $this->controllerName === null){echo 'GetcontrollerName';
		// 	return static::getControllerName();
		// }

		return (isset($this->info[$name])) ? $this->info[$name] : NULL;
    }
	

	public function Block()
	{
		self::$db->Query("UPDATE `" . $this->modelName . "` SET block = 1 WHERE ID = '" . $this->info['ID'] . "'");
		return true;
	}

	public function UnBlock()
	{
		self::$db->Query("UPDATE `" . $this->modelName . "` SET block = 0 WHERE ID = '" . $this->info['ID'] . "'");
		return true;
	}
	
	
	
	public function GetMaxColumnValue($table, $column, $where = '')
	{	
		$where = ($where !== '') ? " WHERE ".$where : ''; 
		$result = self::$db->fetch(self::$db->query("SELECT MAX(".$column.") as max FROM `" . $table . "`".$where));
		return $result['max'];
	}
	
	public function GetMinColumnValue($table, $column, $where = '')
	{	
		$where = ($where !== '') ? " WHERE ".$where : ''; 
		$result = self::$db->fetch(self::$db->query("SELECT MIN(".$column.") as min FROM `" . $table . "`".$where));
		return $result['min'];
	}

	public function GetInfo($relationsFlag = CMF_RELATION_NO, $ignore_guarded = false)
	{
		$content = array();

		if (is_array($this->guarded) and $ignore_guarded == false) {
			foreach ($this->guarded as $field) {
				if (isset($this->info[$field]))
					unset($this->info[$field]);
			}
		}

		if ($relationsFlag == CMF_RELATION_NO)
			return $this->info;

		elseif ($relationsFlag == CMF_RELATION_SIMPLE)
		{
			$relations = self::GetRelations($this->modelName);
			if (count($relations) > 0)
			{
				foreach ($relations as $arr)
				{
					if ($this->info[$arr['name']])
					{
						$relModel = $arr['table'];
						$relation = new $relModel($this->info[$arr['name']]);
						
						$content[$arr['name'] . '_block'] = $relation->GetInfo(CMF_RELATION_NO);
					}
				}
			}
		}
		elseif ($relationsFlag == CMF_RELATION_SIMPLE_LIST)
		{ 
			$relations = self::GetRelations($this->modelName);
			if (count($relations) > 0)
			{
				foreach ($relations as $arr)
				{
					$relModel = $arr['table'];
					$params = new Parameters();
					$params->onPage = 100000;
					$params->rel = 1;

					eval('$content[$arr["name"] . "_block"] = ' . $relModel . '::GetList($relModel, $params);');
				}
			}
		}

		$content = array_merge($content, $this->info);
		
		return $content;
	}
	
	public function GetInfoUnEscape($relationsFlag = CMF_RELATION_NO)
	{
		$content = array();

		if(is_array($this->info)){
			if(self::LoadFieldsInfo($this->modelName, 'edit'))
				$fields = self::$fieldsInfo[$this->modelName]['edit'];
			else
				$fields = array();
			
			if(is_array($this->info)){
				foreach($this->info as $field => &$value){
					$value = ($fields[$field]["type"] == "Html" or $fields[$field]["type"] == "ArrayValue") ? $value : htmlspecialchars(htmlspecialchars_decode($value));	
				} 
			}
			if($relationsFlag == CMF_RELATION_NO) return $this->info;
		}

		return $this->GetInfo($relationsFlag);
	}

	public static function Delete($modelName, $object)
	{  
		if (self::IsTree($modelName))
		{ 
			$IDs = self::GetChildrenAs($object->ID, 'String', $modelName);

			self::$db->Query('DELETE FROM `' . $modelName . '` WHERE ID IN ('.$IDs.')');
			if(self::IfMultilangTable($modelName)){
				self::$db->Query('DELETE FROM `' . $modelName . '_ml` WHERE ID IN ('.$IDs.')');
			}
			
			$checker = new Checker($modelName);
			foreach(explode(",", $IDs) as $key => $value){
				list($element) = $checker->get($value);
				if($element){
					$element->DeleteAllImages();	
				}
			}
		}
		else
		{ 
			self::$db->Query('DELETE FROM `' . $modelName . '` WHERE ID = ' . (int) $object->ID);
			if(self::IfMultilangTable($modelName)){
				self::$db->Query('DELETE FROM `' . $modelName . '_ml` WHERE ID = ' . (int) $object->ID);
			}
			$object->DeleteAllImages();
		}
		
		eval($modelName . '::JoinDelete($object);');

		return true;
	}
	
	public static function DeleteAllSimple($modelName)
	{  
		self::$db->Query('DELETE FROM `' . $modelName . '`');
		if(self::IfMultilangTable($modelName)){
			self::$db->Query('DELETE FROM `' . $modelName . '_ml`');
		}
		
		eval($modelName . '::JoinDeleteAllSimple($object);');

		return true;
	}
	
	public static function JoinCreate($object)
	{
		return true;	
	}
	
	public static function JoinDelete($object)
	{
		return true;	
	}
	
	public static function JoinDeleteAllSimple($object)
	{
		return true;	
	}

	public function Edit($modelName, $formName, $params, &$errors)
	{ 
	
        $params->ID = $this->info['ID'];
		
		$action = $formName;
	
        $errors = self::Validate($params, $modelName, $action);

    	if ($errors->err->Val() == 1)
    	{ 
    		$errors->mess = lang('Произошли ошибки при валидации данных');
    		return false;
        }

    	$multilangColumns = self::GetMultilangTableColumns($modelName);
    	$noMultilangColumns = self::GetNoMultilangTableColumns($modelName);

    	if ($errors->IsUpdated())
    	{ 
			$query = $multilangQuery = '';

			$params = $errors->GetInfo();
			foreach ($params as $key => $value)
			{
                /*if (self::IsFile($modelName, $key))
                {
					$file = new File($value);
					if (!is_dir(Model::$conf->mediaContent . '/database_files/' . $this->modelName))
		           		mkdir(Model::$conf->mediaContent . '/database_files/' . $this->modelName);
		           	if (!is_dir(Model::$conf->mediaContent . '/database_files/' . $this->modelName . '/' . $key))
		           		mkdir(Model::$conf->mediaContent . '/database_files/' . $this->modelName . '/' . $key);

		           	$extractFolder = Model::$conf->mediaContent . '/database_files/' . $this->modelName . '/' . $key . '/' . $this->info['ID'];
		            if (!is_dir($extractFolder))
		            	mkdir($extractFolder);

                	copy($file->path, $extractFolder . '/' . $file->name);

                	$fullName = $extractFolder . '/' . $file->name;
            		$path_info = pathinfo($fullName);
    				$ext = strtolower($path_info['extension']);

                	$new_name = preg_replace('#' . $ext . '$#isu', '', $path_info['basename']);
                    $new_name = Utils::RusLat($new_name) . '.' . $ext;

					if ($extractFolder . '/' . $new_name != $fullName)
						rename($fullName, $extractFolder . '/' . $new_name);

                	$value = $new_name;
                	$file->Remove();
                }*/

       			if (in_array($key, $noMultilangColumns))
       			{
       				if ($value === '_NULL')
       				{
       					$query .= "`" . Checker::Escape($key) . "` = NULL, ";
       				}
       				else
       					$query .= "`" . Checker::Escape($key) . "` = '" . $value . "', ";

       				if ($key == 'parent_ID' && self::IsTree($modelName))
       				{
						$checker = new Checker($modelName);
						list($parent) = $checker->Get($value);
						
       					$level = $parent->level;
       		
       					$query .= " `level` = " . ($level + 1) . ", ";
					
            			if ($this->info['ID'] == $parent->ID)
                   		{
                        	$errors->err = 1;
                        	$errors->err_parent_ID = 1;
                        	$errors->err_mess_parent_ID = lang('Нарушена структура дерева');
                        	$errors->mess = lang('Произошли ошибки при валидации данных');
    						return false;
                   		}

       				}

       			}
       			elseif (in_array($key, $multilangColumns))
       			{
       				if ($value === '_NULL')
       					$multilangQuery .= Checker::Escape($key) . " = NULL, ";
       				else
       					$multilangQuery .= "`" . Checker::Escape($key) . "` = '" . $value . "', ";
                }
			
                $this->info[$key] = Checker::UnEscape($value);
			}

			if ($errors->_extends->Val() == 'Default')
				$query .= "updated = '".date("Y-m-d H:i:s")."', updater_ID = " . self::$user->ID;
            else
            	$query = preg_replace('#, $#isu', '', $query);

			$multilangQuery = preg_replace('#, $#isu', '', $multilangQuery);

			if ($query != '')
				$res = self::$db->Query('UPDATE `' . $modelName . '` SET ' . $query . ' WHERE ID = ' . $this->info['ID']);

			if ($multilangQuery != '')
			{
				self::$db->Query("UPDATE `" . $modelName . "_ml` SET " . $multilangQuery . ' WHERE lang_ID = ' . Application::$language->ID . ' AND ID = ' . $this->info['ID']);
			}

			foreach ($this->info as $key => $value)
			{
				/*if (mb_strtolower(self::GetColumnType($this->modelName, $key)) == 'datetime')
					$this->info[$key] = date(self::$conf->date_format, strtotime($value));*/
			}

			return true;
    	}

        return false;
	}
	
	public static function Create($modelName, $formName, $params, &$errors, $return = false)
	{
        $errors = self::Validate($params, $modelName, $formName);

    	if ($errors->err->Val() == 1)
    	{
    		$errors->mess = lang('Произошли ошибки при валидации данных');
			if($return == false){
    			return false;
			}else{
				return $errors->GetInfo();	
			}
        }

    	$multilangColumns = self::GetMultilangTableColumns($modelName);
    	$noMultilangColumns = self::GetNoMultilangTableColumns($modelName);

    	if ($errors->IsUpdated())
    	{
			$query = $multilangQuery = '';
			$params = $errors->GetInfo();

			foreach ($params as $key => $value)
			{
       			if (in_array($key, $noMultilangColumns))
       			{
       				if ($value === '_NULL')
       					$query .= Checker::Escape($key) . " = NULL, ";
       				else
       					$query .= "`" . Checker::Escape($key) . "` = '" . $value . "', ";

       				if ($key == 'parent_ID' && self::IsTree($modelName))
       				{
						$checker = new Checker($modelName);
						list($parent) = $checker->Get($value);
       					$right_key = $parent->right_key;
       					$level = $parent->level;
       			
       					$query .= " level = " . ($level + 1) . ", ";
       				}
       			}
       			elseif (in_array($key, $multilangColumns))
       			{
       				if ($value === '_NULL')
       					$multilangQuery .= Checker::Escape($key) . " = NULL, ";
       				else
       					$multilangQuery .= "`" . Checker::Escape($key) . "` = '" . $value . "', ";
				}
			}

			
			$actingUser = (self::$user->ID) ? self::$user->ID : 'NULL';
			
			if ($errors->_extends->Val() == 'Default')
				$query .= "created = '".date("Y-m-d H:i:s")."', updated = '".date("Y-m-d H:i:s")."', creator_ID = " . $actingUser . ', updater_ID = ' . $actingUser;
            else
            	$query = preg_replace('#, $#isu', '', $query);

			$multilangQuery = preg_replace('#, $#isu', '', $multilangQuery);
			
			eval("\$ID = ".$modelName."::GetNextID(\$modelName);");
			 
			$query .= ", ID = ".$ID;

			if ($query != '')
				$res = self::$db->Query('INSERT INTO `' . $modelName . '` SET ' . $query);
            else
            	$res = self::$db->Query('INSERT INTO `' . $modelName . '` SET ID = NULL');


			if ($multilangQuery != '')
			{
				$languagesArr = Language::GetLanguages();
				
				foreach ($languagesArr as $key => $value)
				{
					self::$db->Query("INSERT INTO `" . $modelName . "_ml` SET lang_ID = " . $value['ID'] . ", " . $multilangQuery . ', ID = ' . $ID);
				}
			}

    	}
    	else
    	{
    		$res = self::$db->Query('INSERT INTO `' . $modelName . '` SET ID = NULL');
            eval("\$ID = ".$modelName."::GetNextID(\$modelName);");
    	}

		$checker = new Checker($modelName);
		list($object) = $checker->Get($ID);
		
		if ($object)
			eval($modelName . '::JoinCreate($object);');

		return $object;
	}

    public function GetPath(Node $afterNode = NULL)
	{
		
		$content = array();
        static $after = true;
        static $firstIter = true;
        $first = ($firstIter) ? true : false;
        $firstIter = false;

        if ($this->ID != 1)
        {
			if ($after && $afterNode instanceof DatabaseObject && $afterNode->ID == $this->ID)
        		$after = false;

			if ($after)
        		$content[] = $this->GetInfo();

			$checker = new Checker($this->modelName);
			list($parentNode) = $checker->Get($this->parent_ID);

       		$content = array_merge($parentNode->GetPath($afterNode), $content);
        }
        else
        {
			if ($after)
				array_unshift($content, array('name' => 'root', 'last_level' => 0, 'title' => lang('Корень'), 'ID' => 1, 'level' => '0'));
        }

        return ($first && $after && $afterNode instanceof DatabaseObject) ? array() : $content;
	}

	public function GetPathFilter($last_level = true)
	{
		$arr = array();
		$arr['name'] = 'parent_ID';
		$arr['selNode'] = $this->ID;
		$arr['path'] = $this->GetPath();
		$arr['pathCount'] = count($arr['path']) - 1;
		$arr['maxLevel'] = self::GetMaxColumnValue($this->modelName, "level");

		$params2 = new Parameters();
		$params2->where->parent_ID = $this->ID;
		$params2->onPage = 100000;
        $params2->order = 'title';
        $params2->orderDirection = 'ASC';

		//if ($last_level)
		//	$params2->ne->last_level = 1;

		$arr['nodes'] = $this->GetList($this->modelName, $params2);
		return $arr;
	}

	/********************************** Изображения ***********************************/
	public function GetImages($only_visible = false)
	{
		$content = array();

		if (is_dir(self::$conf->mediaContent . '/images/' . $this->modelName . '/' . $this->info['ID']))
		{
			$res = self::$db->Query("SELECT * FROM `Image` WHERE model = '" . $this->modelName . "' AND object_ID = '" . $this->info['ID'] . "'".($only_visible ? ' AND block = 0' : '')." ORDER BY pos ASC");

			while ($arr = self::$db->Fetch($res))
			{
				$image = array();
				if (!is_file(self::$conf->mediaContent . '/images/' . $this->modelName . '/' . $this->info['ID'] . '/' . $arr['name'])) continue;

				$content[] = $arr;
			}
		}

		return $content;
	}
	
	public function SaveImage(File $image, $set_base = true)
	{
		$content = array();
		
		$folder = $this->info["ID"];
		
		$this->DeleteAllImages();

		$modelFolder = Model::$conf->mediaContent.'/images/'.$this->modelName;
		
		if(!is_dir($modelFolder)){
			mkdir($modelFolder);
		}

		$extractFolder = Model::$conf->mediaContent.'/images/'.$this->modelName.'/'.$folder;
		
		if(!is_dir($extractFolder)){
			mkdir($extractFolder);
		}	

		$ext = end(explode('.', $image->name));

		if($this->nameImagesAsObject) {
			$image->name = ($this->nameImagesAsObject === true) ? $this->info['name'] : $this->info[$this->nameImagesAsObject];
		}

		$name = preg_replace('#' . $ext . '$#isu', '', $image->name);
		
		$image->name = Utils::RusLat($name).".".$ext;
	
		copy($image->path, $extractFolder.'/'.$image->name);
		
		if($set_base) $this->SetBaseImage($image->name);

		if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'svg' || $ext == 'gif' || $ext == 'webp'){
			$position = (int) Database::value("SELECT MAX(`pos`) FROM `Image` WHERE `model` = '" . 	$this->modelName . "' AND `object_ID` = '" . $this->info['ID'] . "'") + 10;
			
			self::$db->Query("REPLACE `Image` SET name = '" . $image->name . "', model = '" . $this->modelName . "', object_ID = '" . $this->info['ID'] . "', pos = '" . (int) $position . "'");
			$content['file_name'] = $image->name;
			
			Images::CreateThumbnails($this->modelName, $this->info["ID"], false, $image->name);
		}else{
			$this->DeleteAllImages();
		}
		
		$image->Remove();

		return $content;
	}
	
	public function SaveImages(File $image, $set_base = true)
	{
		$content = array();
		
		$folder = $this->info["ID"];

		$modelFolder = Model::$conf->mediaContent.'/images/'.$this->modelName;
		
		if(!is_dir($modelFolder)){
			mkdir($modelFolder);
		}

		$extractFolder = Model::$conf->mediaContent.'/images/'.$this->modelName.'/'.$folder;
		
		if(!is_dir($extractFolder)){
			mkdir($extractFolder);
		}	
		
		$ext = strtolower(end(explode('.', $image->name)));

		if($this->nameImagesAsObject) {
			$image->name = ($this->nameImagesAsObject === true) ? $this->info['name'] : $this->info[$this->nameImagesAsObject];
		}

		$name = $basename = preg_replace('#' . $ext . '$#isu', '', $image->name);

		$n = 0;
		while(is_file($extractFolder.'/'.Utils::RusLat($name).".".$ext)){
			$n++;
			if($n > 0){
				$name = $basename."_".$n;	
			}
		}
		
		$image->name = Utils::RusLat($name).".".$ext;

		copy($image->path, $extractFolder.'/'.$image->name);
		
		if(trim($this->info['base_image']) == ''){
			if($set_base)
				$this->SetBaseImage($image->name);
		}

		if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'svg' || $ext == 'gif' || $ext == 'webp'){
			$position = (int) Database::value("SELECT MAX(`pos`) FROM `Image` WHERE `model` = '" . $this->modelName . "' AND `object_ID` = '" . $this->info['ID'] . "'") + 10;

			self::$db->Query("REPLACE `Image` SET name = '" . $image->name . "', model = '" . $this->modelName . "', object_ID = '" . $this->info['ID'] . "', pos = '" . (int) $position . "'");
			$content['file_name'] = $image->name;
			
			Images::CreateThumbnails($this->modelName, $this->info["ID"], false, $image->name);
		}else{
			$this->DeleteImage($image->name);
		}
		
		$image->Remove();

		return $content;
	}

	public function DeleteImage($name)
	{
        $folder = Model::$conf->mediaContent . '/images/' . $this->modelName . '/' . $this->info['ID'];

        if (is_file($folder . '/' . $name))
        	Utils::RecursiveRemoveFile($folder, $name);

		self::$db->Query("DELETE FROM `Image` WHERE name = '" . $name . "' AND model = '" . $this->modelName . "' AND object_ID = '" . $this->info['ID'] . "'");
		
		if (isset($this->info['base_image']) and $this->info['base_image'] == $name){
			$base_image = Model::$db->Value("SELECT name FROM Image WHERE model = 'Item' AND object_ID = '".$this->info['ID']."' ORDER BY ID DESC LIMIT 1");
			if($base_image !== ''){
				$this->SetBaseImage($base_image);
			}else{
				$this->SetBaseImage(NULL);
				$this->SetBaseImage(NULL, true);
			}
		}

		return true;
	}
	
	public function DeleteAllImages()
	{
        $folder = Model::$conf->mediaContent . '/images/' . $this->modelName . '/' . $this->info['ID'];

		Utils::RemoveDir($folder);

		self::$db->Query("DELETE FROM `Image` WHERE model = '" . $this->modelName . "' AND object_ID = '" . $this->info['ID'] . "'");

		$this->SetBaseImage(NULL);

		return true;
	}

	public function BlockImage($ID, $block = 1)
	{
		self::$db->Query("UPDATE `Image` SET block = '".$block."' WHERE ID = '" . $ID . "'");

		return true;
	}
	
	public function SetBaseImage($name = NULL, $auto = false)
	{
		$tableColumns = DatabaseObject::GetNoMultilangTableColumns($this->modelName);
		$tableColumnsMultilang = DatabaseObject::GetMultilangTableColumns($this->modelName);
		if(in_array('base_image', $tableColumns)){
			$this->info['base_image'] = $name;
			if($auto == false){ 
				if($name){ 
					self::$db->Query("UPDATE `" . $this->modelName . "` SET base_image = '" . $name . "' WHERE ID = '" . $this->info['ID'] . "'");
				}else{
					self::$db->Query("UPDATE `" . $this->modelName . "` SET base_image = NULL WHERE ID = '" . $this->info['ID'] . "'");
				}
			}else{
				$images = array_reverse($this->GetImages());
				$this->SetBaseImage($images[0]["name"]);
			}
		}elseif(in_array('base_image', $tableColumnsMultilang)){
			$this->info['base_image'] = $name;
			if($auto == false){
				if($name){
					self::$db->Query("UPDATE `" . $this->modelName . "_ml` SET base_image = '" . $name . "' WHERE ID = '" . $this->info['ID'] . "' AND lang_ID = '".Application::$language->ID."'");
				}else{
					self::$db->Query("UPDATE `" . $this->modelName . "_ml` SET base_image = NULL WHERE ID = '" . $this->info['ID'] . "' AND lang_ID = '".Application::$language->ID."'");
				}
			}else{
				$images = array_reverse($this->GetImages());
				$this->SetBaseImage($images[0]["name"]);
			}
		}
		return true;
	}
	
	/********************************** end Изображения ***********************************/


	
	/********************************** Файлы ***********************************/
	public function GetFiles($group)
	{
		$content = array();

		if (is_dir(self::$conf->filesPath . '/' . $group . '/' . $this->modelName . '/' . $this->info['ID']))
		{
			$res = self::$db->Query("SELECT * FROM `Files` WHERE `group` = '" . $group . "' AND `model` = '" . $this->modelName . "' AND `object_ID` = '" . $this->info['ID'] . "' ORDER BY pos");

			while ($arr = self::$db->Fetch($res))
			{
				if (!is_file(self::$conf->filesPath . '/' . $group . '/' . $this->modelName . '/' . $this->info['ID'] . '/' . $arr['name']))
					continue;
				
				$img_ext = array("png", "jpg", "jpeg", "svg", "webp");
				$arr["ext"] = pathinfo($arr["name"], PATHINFO_EXTENSION);
				$arr["type"] = (in_array(strtolower($arr["ext"]), $img_ext)) ? "image" : "file";	
				$content[] = $arr;
			}
		}
		
		return $content;

	}

	public function SaveFile(File $file, $group)
	{
		$content = array();
		
		$folder = $this->info["ID"];
		
		$this->DeleteAllFiles($group);

		$groupFolder = self::$conf->filesPath . '/' . $group;

		if(!is_dir($groupFolder)){
			mkdir($groupFolder);
		}

		$modelFolder = self::$conf->filesPath . '/' . $group . '/' .$this->modelName;
		
		if(!is_dir($modelFolder)){
			mkdir($modelFolder);
		}

		$extractFolder = self::$conf->filesPath . '/' . $group . '/' . $this->modelName . '/' . $folder;
		
		if(!is_dir($extractFolder)){
			mkdir($extractFolder);
		}	
		
		$ext = end(explode('.', $file->name));
		$name = preg_replace('#' . $ext . '$#isu', '', $file->name);

		$title = substr($name, 0, strlen($name) - 1);
		
		$file->name = Utils::RusLat($name).".".$ext;
	
		copy($file->path, $extractFolder.'/'.$file->name);

		self::$db->Query("REPLACE `Files` SET `name` = '" . $file->name . "', `group` = '" . $group . "', `model` = '" . $this->modelName . "', `object_ID` = '" . $this->info['ID'] . "', `title` = '" . $title . "'");
		$content['file_name'] = $file->name;
		
		$file->Remove();

		return $content;

	}

	public function DeleteFile($name, $group)
	{
        $folder = self::$conf->filesPath . '/' . $group . '/' . $this->modelName . '/' . $this->info['ID'];

        if (is_file($folder . '/' . $name))
        	Utils::RecursiveRemoveFile($folder, $name);

		self::$db->Query("DELETE FROM `Files` WHERE name = '" . $name . "' AND `group` = '" . $group . "' AND model = '" . $this->modelName . "' AND object_ID = '" . $this->info['ID'] . "'");

		return true;
	}
	
	public function DeleteAllFiles($group)
	{
        $folder = self::$conf->filesPath . '/' . $group . '/' . $this->modelName . '/' . $this->info['ID'];
		
		Utils::RemoveDir($folder);

		self::$db->Query("DELETE FROM `Files` WHERE `group` = '" . $group . "' AND model = '" . $this->modelName . "' AND object_ID = '" . $this->info['ID'] . "'");

		return true;
	}
	
	/********************************** end Файлы ***********************************/
	
	

    /*public function GetParamsAndValues($params = array())
	{
		$content = array();

		if(!$key){
			$checker = new Checker("Category");
			list($relationObject) = $checker->Get($this->info['category_ID']);

			if($relationObject) $relationObject->paramgroup_ID =  Paramgroup::GetByCategory($relationObject);
		}else{
			$checker = new Checker("Category");
			list($relationObject) = $checker->Get(1);
			
			if($relationObject) $relationObject->paramgroup_ID =  $this->info[$key];
		}
		
		if ($relationObject && $relationObject->paramgroup_ID)
		{
			$paramgroup = new Paramgroup($relationObject->paramgroup_ID);

			$e = NULL;
			if ($i)
				$e = ' AND Param.filter = 1';




			$res = Model::$db->Query("SELECT Param.*, Param_ml.* FROM Param
		    						INNER JOIN Param_ml ON Param_ml.ID = Param.ID AND Param_ml.lang_ID = '" . Application::$language->ID . "'
		    						WHERE Param.paramgroup_ID = " . $relationObject->paramgroup_ID . $e . " ORDER BY Param.pos");

			$values = Model::$db->Row('SELECT `Paramvalue_' . $paramgroup->name . '`.*, `Paramvalue_' . $paramgroup->name . '_ml`.* FROM `Paramvalue_' . $paramgroup->name . '` INNER JOIN `Paramvalue_' . $paramgroup->name . '_ml` ON `Paramvalue_' . $paramgroup->name . '`.ID = `Paramvalue_' . $paramgroup->name . '_ml`.ID AND `Paramvalue_' . $paramgroup->name . '_ml`.lang_ID = ' . Application::$language->ID . ' WHERE `Paramvalue_' . $paramgroup->name . '`.model = "'.$model.'" AND `Paramvalue_' . $paramgroup->name . '`.object_ID = ' . $this->info['ID']);


			
			while ($arr = Model::$db->Fetch($res))
			{
				$arr['items'] = array();
				$res2 = Model::$db->Query('SELECT DISTINCT `Paramvalue_' . $paramgroup->name . '_ml`.' . $arr['name'] . ' AS value FROM `Paramvalue_' . $paramgroup->name . '` INNER JOIN `Paramvalue_' . $paramgroup->name . '_ml` ON `Paramvalue_' . $paramgroup->name . '`.ID = `Paramvalue_' . $paramgroup->name . '_ml`.ID AND `Paramvalue_' . $paramgroup->name . '_ml`.lang_ID = ' . Application::$language->ID . ' WHERE `Paramvalue_' . $paramgroup->name . '`.model = "'.$model.'" AND `Paramvalue_' . $paramgroup->name . '_ml`.' . $arr['name'] . ' IS NOT NULL ORDER BY value');

				while ($arr2 = Model::$db->Fetch($res2))
				{
					$arr['items'][] = array('value' => $arr2['value'], 'num_in' => $arr2['num_in']);
				}


				if (!$empty && $values[$arr['name']])
				{
					$arr['value'] = htmlspecialchars($values[$arr['name']]);
					$content[] = $arr;
				}
				elseif($empty)
				{
					$arr['value'] = htmlspecialchars($values[$arr['name']]);
					$content[] = $arr;
				}
				
			}

		}

		return $content;
	}*/
	
	public function GetParamsAndValuesWithIDs($key = false, $model = 'Item', $empty = true, $i = false)
	{
		$content = array();

		if(!$key){
			$checker = new Checker("Category");
			list($relationObject) = $checker->Get($this->info['category_ID']);

			if($relationObject) $relationObject->paramgroup_ID =  Paramgroup::GetByCategory($relationObject);
		}else{
			$checker = new Checker("Category");
			list($relationObject) = $checker->Get(1);
			if($relationObject) $relationObject->paramgroup_ID =  $this->info[$key];
		}
		
		if ($relationObject && $relationObject->paramgroup_ID)
		{
			$paramgroup = new Paramgroup($relationObject->paramgroup_ID);

			$e = NULL;
			if ($i)
				$e = ' AND Param.filter = 1';




			$res = Model::$db->Query("SELECT Param.*, Param_ml.* FROM Param
		    						INNER JOIN Param_ml ON Param_ml.ID = Param.ID AND Param_ml.lang_ID = '" . Application::$language->ID . "'
		    						WHERE Param.paramgroup_ID = " . $relationObject->paramgroup_ID . $e . " ORDER BY Param.pos");

			$values = Model::$db->Row('SELECT `Paramvalue_' . $paramgroup->name . '`.*, `Paramvalue_' . $paramgroup->name . '_ml`.* FROM `Paramvalue_' . $paramgroup->name . '` INNER JOIN `Paramvalue_' . $paramgroup->name . '_ml` ON `Paramvalue_' . $paramgroup->name . '`.ID = `Paramvalue_' . $paramgroup->name . '_ml`.ID AND `Paramvalue_' . $paramgroup->name . '_ml`.lang_ID = ' . Application::$language->ID . ' WHERE `Paramvalue_' . $paramgroup->name . '`.model = "'.$model.'" AND `Paramvalue_' . $paramgroup->name . '`.object_ID = ' . $this->info['ID']);


			
			while ($arr = Model::$db->Fetch($res))
			{
				$arr['items'] = array();
				$res2 = Model::$db->Query('SELECT DISTINCT `Paramvalue_' . $paramgroup->name . '_ml`.' . $arr['name'] . ' AS value FROM `Paramvalue_' . $paramgroup->name . '` INNER JOIN `Paramvalue_' . $paramgroup->name . '_ml` ON `Paramvalue_' . $paramgroup->name . '`.ID = `Paramvalue_' . $paramgroup->name . '_ml`.ID AND `Paramvalue_' . $paramgroup->name . '_ml`.lang_ID = ' . Application::$language->ID . ' WHERE `Paramvalue_' . $paramgroup->name . '`.model = "'.$model.'" AND `Paramvalue_' . $paramgroup->name . '_ml`.' . $arr['name'] . ' IS NOT NULL');
				while ($arr2 = Model::$db->Fetch($res2))
				{
					$arr['items'][$arr2['ID']] = array('value' => $arr2['value']);
				}


				if (!$empty && $values[$arr['name']])
				{
					$arr['value'] = htmlspecialchars($values[$arr['name']]);
					$content[$arr['ID']] = $arr;
				}
				elseif($empty)
				{
					$arr['value'] = htmlspecialchars($values[$arr['name']]);
					$content[$arr['ID']] = $arr;
				}
				
			}

		}

		return $content;
	}
	
	public function GetParamValues($key = false, $model = 'Item', $empty = true, $i = false)
	{
		$content = array();

		if(!$key){
       		$checker = new Checker("Category");
			list($relationObject) = $checker->Get($this->info['category_ID']);

			if($relationObject) $relationObject->paramgroup_ID =  Paramgroup::GetByCategory($relationObject);
		}else{
			$checker = new Checker("Category");
			list($relationObject) = $checker->Get(1);
			if($relationObject) $relationObject->paramgroup_ID =  $this->info[$key];
		}
		
		if ($relationObject && $relationObject->paramgroup_ID)
		{
			$paramgroup = new Paramgroup($relationObject->paramgroup_ID);

			$e = NULL;
			if ($i)
				$e = ' AND Param.filter = 1';


			$res = Model::$db->Query("SELECT Param.*, Param_ml.* FROM Param
		    						INNER JOIN Param_ml ON Param_ml.ID = Param.ID AND Param_ml.lang_ID = '" . Application::$language->ID . "'
		    						WHERE Param.paramgroup_ID = " . $relationObject->paramgroup_ID . $e . " ORDER BY Param.pos");

			$values = Model::$db->Row('SELECT `Paramvalue_' . $paramgroup->name . '`.*, `Paramvalue_' . $paramgroup->name . '_ml`.* FROM `Paramvalue_' . $paramgroup->name . '` INNER JOIN `Paramvalue_' . $paramgroup->name . '_ml` ON `Paramvalue_' . $paramgroup->name . '`.ID = `Paramvalue_' . $paramgroup->name . '_ml`.ID AND `Paramvalue_' . $paramgroup->name . '_ml`.lang_ID = ' . Application::$language->ID . ' WHERE `Paramvalue_' . $paramgroup->name . '`.model = "'.$model.'" AND `Paramvalue_' . $paramgroup->name . '`.object_ID = ' . $this->info['ID']);

	

			while ($arr = Model::$db->Fetch($res))
			{
				$arr['items'] = array();
				$res2 = Model::$db->Query('SELECT DISTINCT `Paramvalue_' . $paramgroup->name . '_ml`.' . $arr['name'] . ' AS value FROM `Paramvalue_' . $paramgroup->name . '` INNER JOIN `Paramvalue_' . $paramgroup->name . '_ml` ON `Paramvalue_' . $paramgroup->name . '`.ID = `Paramvalue_' . $paramgroup->name . '_ml`.ID AND `Paramvalue_' . $paramgroup->name . '_ml`.lang_ID = ' . Application::$language->ID . ' WHERE `Paramvalue_' . $paramgroup->name . '`.model = "'.$model.'" AND `Paramvalue_' . $paramgroup->name . '_ml`.' . $arr['name'] . ' IS NOT NULL');
				while ($arr2 = Model::$db->Fetch($res2))
				{
					$arr['items'][$arr2['name']] = array('value' => $arr2['value']);
				}


				if (!$empty && $values[$arr['name']])
				{
					$content[$arr['name']] = $values[$arr['name']];
				}
				elseif($empty)
				{
					$content[$arr['name']] = $values[$arr['name']];
				}
			}

		}

		return $content;
	}

	public function SaveParamValues($params, &$errors, $key = false, $model = "Item")
	{ 
		$params = $params->GetInfo();
		
        if (count($params) > 0)
        {
			if(!$key){
        		$checker = new Checker("Category");
				list($category) = $checker->Get($this->info['category_ID']);

				$category->paramgroup_ID = Paramgroup::GetByCategory($category);
				$key = $category->paramgroup_ID;
			}else{
				$key = $this->info[$key];
			}
			
        	$paramgroup = new Paramgroup($key);

			$tableName = 'Paramvalue_' . $paramgroup->name;
			
			$param_val_ID = Model::$db->Value('SELECT ID FROM `' . $tableName . '` WHERE model = "'.$model.'" AND `object_ID` = "' .  $this->info['ID'] . '"');
			if (!$param_val_ID)
			{
				$param_val_ID = self::GetMaxColumnValue($tableName, 'ID') + 1;
				$res = self::$db->Query('INSERT INTO `' . $tableName . '` SET ID = "'.$param_val_ID.'", model = "'.$model.'", object_ID = ' . $this->info['ID']);
			}
			
			$paramgroupParams = $paramgroup->GetParams();
			
			$paramgroup = new Paramgroup($key);




			$ru = array('года', 'год', 'лет');
			$ro = array('ani', 'an', 'ani');




        	$multilangQuery = '';
			$updateQuery = array();

        	for ($i = 0; $i < count($paramgroupParams); $i++)
        	{
        		if (isset($params[$paramgroupParams[$i]['name']]))
        		{
        			$value = NULL;
        			$checker = new Checker($paramgroupParams[$i]['type']);
        			list($value) = $checker->Get($params[$paramgroupParams[$i]['name']]);
					
                    if(!$value){
                    	$multilangQuery .= $paramgroupParams[$i]['name'] . ' = NULL, ';
					}else{
        				$multilangQuery .= $paramgroupParams[$i]['name'] . ' = "' . $value . '", ';
					}

					if($paramgroupParams[$i]['name'] == 'pol' or $paramgroupParams[$i]['name'] == 'age' or $paramgroupParams[$i]['name'] == 'size'){
						if(trim($value) !== ''){
							if($paramgroupParams[$i]['name'] == 'size'){
								$newValue = $value;
							}elseif($paramgroupParams[$i]['name'] == 'age'){
								$newValue = (Application::$language->name == 'ru') ? str_replace($ru, $ro, $value) : str_replace($ro, $ru, $value);
							}elseif($paramgroupParams[$i]['name'] == 'pol'){
								if($value == Filters::$names['male']['ru'])
									$newValue = Filters::$names['male']['ro'];
								elseif($value == Filters::$names['male']['ro'])
									$newValue = Filters::$names['male']['ru'];

								if($value == Filters::$names['female']['ru'])
									$newValue = Filters::$names['female']['ro'];
								elseif($value == Filters::$names['female']['ro'])
									$newValue = Filters::$names['female']['ru'];

								if($value == Filters::$names['unisex']['ru'])
									$newValue = Filters::$names['unisex']['ro'];
								elseif($value == Filters::$names['unisex']['ro'])
									$newValue = Filters::$names['unisex']['ru'];
							}

							$updateQuery[] = $paramgroupParams[$i]['name'] . " = '" . Database::Escape($newValue) . "'";
						}
					}
        		}
        	}
        	$multilangQuery = preg_replace('#, $#isu', '', $multilangQuery);

        	$langTable = $tableName . '_ml';

       		$languages = Language::GetLanguages();
			
			foreach ($languages as $key => $value)
			{
				if (Application::$language->ID == $value['ID'])
				{

					self::$db->Query('INSERT INTO `' . $langTable . '` SET ' . $multilangQuery . ', ID = ' . $param_val_ID . ', lang_ID = ' . $value['ID'] . ' ON DUPLICATE KEY UPDATE ' . $multilangQuery);
				}
				else
				{
					self::$db->Query('INSERT IGNORE INTO `' . $langTable . '` SET ' . $multilangQuery . ', lang_ID = ' . $value['ID'] . ', ID = ' . $param_val_ID . '');
				}
			}

			if(count($updateQuery) > 0){
				self::$db->Query('UPDATE `' . $langTable . '` SET ' . implode(', ', $updateQuery) . ' WHERE ID = ' . $param_val_ID . ' AND lang_ID = ' . (Application::$language->name == 'ru' ? 2 : 1) . '');
			}
		}

		return true;
	}

	public function GetFields($modelName, $type)
	{
		$content = array();
		
		if(!isset(self::$fieldsInfo[$modelName][$type])){
			self::LoadFieldsInfo($modelName, $type);
		}
	
		$fields = self::$fieldsInfo[$modelName][$type];
		
		foreach($fields as $field => $value){
			foreach($value as $attr => $value){
				$content["_field_".$field."_".$attr] = $value;
			}
		}
	
		return $content;
		
	}
	
	public function CopyObject()
	{
		$content = array();
			
		// Copy main data --------------------------------------------------------------------
		
		$objectArray = array_shift(Model::$db->ArrayValuesQ("SELECT * FROM `".$this->modelName."` WHERE `".$this->modelName."`.ID = '".$this->ID."'"));
		
		if(self::IsMultilang($this->modelName))
			$objectArray_ml = Model::$db->ArrayValuesQ("SELECT * FROM `".$this->modelName."_ml` WHERE `".$this->modelName."_ml`.ID = '".$this->ID."'");
			
		$query = $query_ml = array();
		
		if(is_array($objectArray))
			foreach($objectArray as $field => $value){
				if(!preg_match('/^\d+$/', $field) and $field !== "ID" and $field !== "pos"){
					$query[] = '`'.$field.'` = \''.Database::$db->real_escape_string($value).'\'';
				}
			}
			
		$tableColumns = DatabaseObject::GetTableColumns($this->modelName);
		if(in_array('pos', $tableColumns))
			$query[] = '`pos` = \''.DatabaseObject::GetMaxPos($this->modelName).'\'';
		
		if(is_array($objectArray_ml))
			foreach($objectArray_ml as $n => $item){
				foreach($item as $field => $value){
					if(!preg_match('/^\d+$/', $field) and $field !== "ID"){
						//$t = StaticDatabaseObject::$tablesInfo["fields"]["creat"][$field]["type"];
						$query_ml[$n][] = '`'.$field.'` = \''.Database::$db->real_escape_string($value).'\'';
					}
				}
			}
		
		if(count($query)){
			Model::$db->Query("INSERT INTO `".$this->modelName."` SET ".implode(", ", $query));
			
			$new_ID = Database::$db->insert_id;
			
			if(count($query_ml)){
				foreach($query_ml as $query_ml_values)
					Model::$db->Query("INSERT INTO `".$this->modelName."_ml` SET ID = '".$new_ID."', ".implode(", ", $query_ml_values));
			}
				
			
			if((int)$new_ID){
				
				
				// Copy images --------------------------------------------------------------------
				$images = Model::$db->ArrayValuesQ("SELECT * FROM `Image` WHERE model = '".$this->modelName."' AND object_ID = '".$this->ID."'");
				
				if(is_array($images)){
					foreach($images as $image){
						$query = array();
						foreach($image as $field => $value){
							if(!preg_match('/^\d+$/', $field) and $field !== "object_ID" and $field !== "ID"){
								$query[] = '`'.$field.'` = \''.Checker::Escape($value).'\'';
							}
						}
						if(count($query))
							Model::$db->Query("INSERT INTO `Image` SET object_ID = '".$new_ID."',  ".implode(", ", $query));
					}
				
					$imagesPath = Model::$conf->imgPath."/images/".$this->modelName."/".$this->ID;
					$imagesPathNew = Model::$conf->imgPath."/images/".$this->modelName."/".$new_ID;
					
					if(is_dir($imagesPath))
						Utils::CopyDir($imagesPath, $imagesPathNew);
				}
				
				
				
				// Copy Item Parameters --------------------------------------------------------------------
				if($this->modelName == "Item" and Application::$params["parameters"]){
					$checker = new Checker("Category");
					list($relationObject) = $checker->Get($this->category_ID);
			
					if($relationObject and $relationObject->paramgroup_ID){
						$checker = new Checker("Paramgroup");
						list($paramgroup) = $checker->Get($relationObject->paramgroup_ID);
					}
					if($paramgroup and $paramgroup = $paramgroup->name){
						
						$paramsArray = Model::$db->ArrayValuesQ("SELECT * FROM `Paramvalue_".$paramgroup."` WHERE object_ID = '".$this->ID."' AND model = '".$this->modelName."'");
						
						if(is_array($paramsArray)){
							foreach($paramsArray as $param){
								
								$query = $query_ml = array();
								
								foreach($param as $field => $value){
									if(!preg_match('/^\d+$/', $field) and $field !== "ID" and $field !== "object_ID"){
										$query[] = '`'.$field.'` = \''.Checker::Escape($value).'\'';
									}
								}
							
								$paramsArray_ml = Model::$db->ArrayValuesQ("SELECT * FROM `Paramvalue_".$paramgroup."_ml` WHERE ID = '".$param["ID"]."'");
								
								if(is_array($paramsArray_ml))
									foreach($paramsArray_ml as $n => $item){
										foreach($item as $field => $value){
											if(!preg_match('/^\d+$/', $field) and $field !== "ID"){
												$query_ml[$n][] = '`'.$field.'` = \''.Checker::Escape($value).'\'';
											}
										}
									}
								
								if(count($query)){
									Model::$db->Query("INSERT INTO `Paramvalue_".$paramgroup."` SET object_ID = '".$new_ID."', ".implode(", ", $query));
									
									$new_param_ID = Database::$db->insert_id;
									
									if(count($query_ml)){
										foreach($query_ml as $query_ml_values)
											Model::$db->Query("INSERT INTO `Paramvalue_".$paramgroup."_ml` SET ID = '".$new_param_ID."', ".implode(", ", $query_ml_values));
									}
								}
							}
						}
					}
				}
				
				$content["ID"] = $new_ID;
			}
		}
		
		return $content;
	}

	public static function RecreateImagesURLID($model)
	{
		$images = $new_images = array();

		$images_db = Model::$db->ArrayValuesQ("SELECT * FROM Image WHERE model = '$model'");

		foreach($images_db as $img){
			if(isset($images[$img['object_ID']])){
				$images[$img['object_ID']][] = $img['name'];
			}else{
				$images[$img['object_ID']] = array($img['name']);
			}
		}

		$table = $model;

		if(self::IsMultilang($model)){
			$ml_table = $model.'_ml';
			
			foreach($images as $ID => $imgs){
				$arr = array_shift(Model::$db->ArrayValuesQ("SELECT t.*, tm.* FROM `$table` t LEFT JOIN `$ml_table` tm ON t.ID = tm.ID WHERE tm.lang_ID = '".Application::$language->ID."' AND t.ID = $ID"));

				if($arr['title'] !== ''){
					$n = 1;
					foreach($imgs as $key => $image){
						$ext = end(explode('.', $image));
						$new_images[$ID][$key] = Utils::RusLat($arr['title'], '_').($n > 1 ? '_'.$n : '').'.'.$ext;
						$n++;
					}
				}
			}
		}else{
			foreach($images as $ID => $image){
				$arr = array_shift(Model::$db->ArrayValuesQ("SELECT * FROM $table WHERE ID = $ID"));

				if($arr['title'] !== ''){
					$n = 1;
					foreach($imgs as $key => $image){
						$ext = end(explode('.', $image));
						$new_images[$ID][$key] = Utils::RusLat($arr['title'], '_').($n > 1 ? '_'.$n : '').'.'.$ext;
						$n++;
					}
				}
			}
		}
		
		if(count($new_images)){
			$basePath = Model::$conf->imgPath."/images/$model";

			foreach($new_images as $ID => $imgs){
				foreach($imgs as $key => $image){
					Model::$db->Query("REPLACE `Image` SET name = '$image', object_ID = $ID, model = '$model'");

					rename($basePath.'/'.$ID.'/'.$images[$ID][$key], $basePath.'/'.$ID.'/'.$image);

					if(!self::isMultilangColumn($model, "base_image")){
						Model::$db->Query("UPDATE `$model` SET base_image = '$image' WHERE ID = $ID AND base_image = '".$images[$ID][$key]."'");
					}else{
						Model::$db->Query("UPDATE `{$model}_ml` SET base_image = '$image' WHERE ID = $ID AND base_image = '".$images[$ID][$key]."'");
					}
				}
			}
		}
	}

}


?>