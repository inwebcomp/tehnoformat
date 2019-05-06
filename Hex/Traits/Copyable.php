<?php

namespace Hex\Traits;

use Database;
use Model;
use Checker;
use Utils;
use DatabaseObject;

trait Copyable
{
	public function copy()
	{
		$content = array();
			
		$model = $this->modelName;

		// Copy main data --------------------------------------------------------------------
		
		$objectArray = array_shift(Model::$db->ArrayValuesQ("SELECT * FROM `".$model."` WHERE `".$model."`.ID = '".$this->ID."'"));
		
		if(self::IsMultilang($model))
			$objectArray_ml = Model::$db->ArrayValuesQ("SELECT * FROM `".$model."_ml` WHERE `".$model."_ml`.ID = '".$this->ID."'");
			
		$query = $query_ml = array();
		
		if(is_array($objectArray))
			foreach($objectArray as $field => $value){
				if(!preg_match('/^\d+$/', $field) and $field !== "ID" and $field !== "pos"){
					$query[] = '`'.$field.'` = \''.Database::$db->real_escape_string($value).'\'';
				}
			}
			
		$tableColumns = DatabaseObject::GetTableColumns($model);
		if(in_array('pos', $tableColumns))
			$query[] = '`pos` = \''.DatabaseObject::GetMaxPos($model).'\'';
		
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
			Model::$db->Query("INSERT INTO `".$model."` SET ".implode(", ", $query));
			
			$new_ID = Database::$db->insert_id;
			
			if(count($query_ml)){
				foreach($query_ml as $query_ml_values)
					Model::$db->Query("INSERT INTO `".$model."_ml` SET ID = '".$new_ID."', ".implode(", ", $query_ml_values));
			}
				
			
			if((int)$new_ID){
				
				
				// Copy images --------------------------------------------------------------------
				$images = Model::$db->ArrayValuesQ("SELECT * FROM `Image` WHERE model = '".$model."' AND object_ID = '".$this->ID."'");
				
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
				
					$imagesPath = Model::$conf->imgPath."/images/".$model."/".$this->ID;
					$imagesPathNew = Model::$conf->imgPath."/images/".$model."/".$new_ID;
					
					if(is_dir($imagesPath))
						Utils::CopyDir($imagesPath, $imagesPathNew);
				}
				
				
				
				// Copy Item Parameters --------------------------------------------------------------------
				if($model == "Item" and Application::$params["parameters"]){
					$checker = new Checker("Category");
					list($relationObject) = $checker->Get($this->category_ID);
			
					if($relationObject and $relationObject->paramgroup_ID){
						$checker = new Checker("Paramgroup");
						list($paramgroup) = $checker->Get($relationObject->paramgroup_ID);
					}
					if($paramgroup and $paramgroup = $paramgroup->name){
						
						$paramsArray = Model::$db->ArrayValuesQ("SELECT * FROM `Paramvalue_".$paramgroup."` WHERE object_ID = '".$this->ID."' AND model = '".$model."'");
						
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
			}
		}
		
		return static::find($new_ID);
	}

	
	public function copyTo($model)
	{
		$content = array();

		// Copy main data --------------------------------------------------------------------
		
		$objectArray = array_shift(Model::$db->ArrayValuesQ("SELECT * FROM `".$this->modelName."` WHERE `".$this->modelName."`.ID = '".$this->ID."'"));
		
		if(self::IsMultilang($model))
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
			Model::$db->Query("INSERT INTO `".$model."` SET ".implode(", ", $query));
			
			$new_ID = Database::$db->insert_id;
			
			if(count($query_ml)){
				foreach($query_ml as $query_ml_values)
					Model::$db->Query("INSERT INTO `".$model."_ml` SET ID = '".$new_ID."', ".implode(", ", $query_ml_values));
			}
				
			
			if((int)$new_ID){
				// Copy images --------------------------------------------------------------------
				$this->copyImagesTo($new_ID, $model);
			}
		}
		
		return $model::find($new_ID);
	}

	public function copyImages($new_ID)
	{
		return $this->copyImagesTo($new_ID, $this->getModelName());
	}

	public function copyImagesTo($new_ID, $model, $clear = false)
	{
		if ($clear) {
			$object = $model::find($new_ID);
			
			if ($object and $object->real())
				$object->deleteAllImages();
		}

		$images = Database::arrayValuesQ("SELECT * FROM `Image` WHERE model = '" . $this->getModelName() . "' AND object_ID = '" . $this->ID . "'");
		
		if (is_array($images)) {
			foreach ($images as $image) {
				$query = array();
				foreach ($image as $field => $value){
					if (! preg_match('/^\d+$/', $field) and $field !== "object_ID" and $field !== "ID" and $field !== "model"){
						$query[] = '`' . $field . '` = \'' . Database::escape($value) . '\'';
					}
				}
				if (count($query))
					Database::query("INSERT INTO `Image` SET object_ID = '" . $new_ID . "', model = '" . $model . "', " . implode(", ", $query));
			}
		
			if (! is_dir(Model::$conf->imgPath . "/images/" . $model))
				mkdir(Model::$conf->imgPath . "/images/" . $model);

			$imagesPath = Model::$conf->imgPath . "/images/" . $this->getModelName() . "/" . $this->ID;
			$imagesPathNew = Model::$conf->imgPath . "/images/" . $model . "/" . $new_ID;
	
			if (is_dir($imagesPath))
				Utils::copyDir($imagesPath, $imagesPathNew);
		}

		return true;
	}
}