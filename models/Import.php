<?php

class Import extends databaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Import';
        $this->controllerName = 'import';

		parent::__construct($ID);
	}

	public function GetFileLines($fileName, $skip = 0, $charset = false, $url = false, $separator = ";", $link_cols = array()){

		if((isset($fileName) and $fileName['tmp_name'] !== '' and !$url) or ($fileName !== "" and $url)){
			
			if($url){
				$file = $_SERVER['DOCUMENT_ROOT'].$fileName;	
			}else{
				$file = $fileName['tmp_name'];
			} 
			
			if(file_exists($file)){ 
				$newFile = Model::$conf->tmpPath."/".$fileName["name"];
				move_uploaded_file($file, $newFile);
				$file = $newFile;
			
				switch (pathinfo($file, PATHINFO_EXTENSION)){
					case "csv":
						$result = self::GetCSVLines($newFile, $skip, $charset, true, $separator, $link_cols);
					break;
					case "xls";
						$result = self::GetXLSLines($newFile, $skip, $charset, true, $link_cols);
					break;
				}
				
				unlink($newFile);
				
				return $result;
			}else{
				return false;	
			}
			
		}else{
			return false;	
		}
			
	}
	
	public function GetXLSLines($file, $skip = 0, $charset = false, $url = false, $link_cols = array()){
		
		include(Model::$conf->classesPath.'/XLSReader/reader.php');

		if((isset($file) and isset($file['tmp_name']) and $file['tmp_name'] !== '' and !$url) or ($file !== "" and $url)){
			
			$skip_char = false;
			
			if($url){
				$file_name = $file;	
			}else{
				$file_name = $file['tmp_name'];
			} 
		
			$data = new Spreadsheet_Excel_Reader();
			$data->setUTFEncoder('iconv');
			$data->setOutputEncoding((!$charset ? "utf-8" : "windows-1251"));
			$data->read($file_name);
			
			for ($i = 0 + $skip; $i < $data->sheets[0]['numRows']; $i++){
				$link = false;
				$lval = array();
				for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++){
					$i2 = $i + 1;
					$val = $data->sheets[0]['cells'][$i2][$j];
					if(isset($link_cols[$j])){
						$link = true;
						$first_col = $link_cols[$j][0];
						$last_col = $link_cols[$j][1];
						$joiner = $link_cols[$j][2];
						$not_unique = (bool)$link_cols[$j][3];
						$not_trim = (bool)$link_cols[$j][4];
					}
					if(!$link){
						$values[$i][] = str_replace("&semicolon&", ";", $val);
					}
					if($link and $j >= $first_col and $j <= $last_col){
						if(trim($val) !== "" or $not_trim == true) $lval[] = str_replace("&semicolon&", ";", $val);
						if($j == $last_col or $j == $data->sheets[0]['numCols']){
							if($not_unique == false) $lval = array_unique($lval);
							$values[$i][] = implode($joiner, $lval);
							$link = $not_unique = $not_trim = false;
							$lval = array();	
						}
					}
				}
			}
		
			return $values;

		}else{
			return array();
		}
			
	}
	
	public function GetCSVLines($file, $skip = 0, $charset = false, $url = false, $separator = ";", $link_cols = array()){
		
		if((isset($file) and isset($file['tmp_name']) and $file['tmp_name'] !== '' and !$url) or ($file !== "" and $url)){
			
			$skip_char = false;
		
			$separator = (trim($separator) == "") ? ";" : $separator;
		
			if($url){
				$csv_lines = file($file);	
			}else{
				$csv_lines = file($file['tmp_name']);
			} 
			if(is_array($csv_lines))
			{
				$cnt = count($csv_lines);
				for($i = 0; $i < $cnt; $i++)
				{ 
					if($i < $skip) continue;
					
					$line = $csv_lines[$i];
					$line = trim($line);
					
					if(substr($line, 0, -1) !== $separator) $line .= $separator;

					$first_char = true; 

					$col_num = 0;
					$length = strlen($line);
					for($b = 0; $b < $length; $b++)
					{
						echo "---- Итерация $b \n";
						if($skip_char != true)
						{
							$process = true;
							echo "Считываю данные\n";

							if($first_char == true)
							{
								echo "Первый символ\n";
								if($line[$b] == '"')
								{
									echo "Наткнулся на ковычки, значит данные закончатся ковычками. Ещё перестаю считывать данные\n";
									$terminator = '"'.$separator;
									$process = false;
								}
								else{
									$terminator = $separator;
									echo "Не наткнулся на ковычки, значит данные закончатся точкой с запятой\n";
								}
								$first_char = false;
								echo "Уже не первый символ, хотя итерация та же\n";
							}
			  
							if($line[$b] == '"')
							{
								echo "Наткнулся на ковычки\n";
								$next_char = $line[$b + 1]; 

								if($next_char == '"'){
									echo "Следующий символ - ковычки, значит нужно пропустить символ в сделующем цикле\n";
									$skip_char = false;
								}elseif($next_char == $separator){ 
									if($terminator == '"'.$separator)
									{
										echo "Следующий символ - разделитель, а сейчас я читаю ковычку. Я прекращаю чтение данных, пропускаю этот символи и начинаю читать дальше с первого символа\n";
										$first_char = true;
										$process = false;
										$skip_char = true;
									}
								}elseif($terminator == ';'.$separator and $next_char == ''){
									echo "Следующий символ - двойной разделитель. Я перестаю читать данные\n";
									$process = false;
								}
							}
			  
							if($process == true)
							{
								if($line[$b] == ';')
								{ 
									 if($terminator == ';')
									 {
										 echo "Я читаю данные, этот символ разделитель. Я прекращаю чтение данных иначинаю читать дальше с первого символа\n";
										  $first_char = true;
										  $process = false;
									 }
								}
							}
			  
							if($process == true){
								$column .= $line[$b];
								echo "Я читаю данные и записываю символ $terminator ".$line[$b]."\n";
							}
			  
							if($b == ($length - 1))
							{
								$first_char = true;
								echo "Я читаю последний символ в строке. Я начитнаю чидать дальше с первого символа\n";
							}
			  
							if($first_char == true)
							{
								echo "Я читаю с первого символа и записываю данные ячейки в конечный массив\n";
								$values[$i][$col_num] = str_replace("&semicolon&", ";", (!$charset) ? $column : iconv("WINDOWS-1251", "UTF-8", $column));
								$column = '';
								$col_num++;
							}
						}
						else {
							$skip_char = false;
							echo "Пропускаю символ символ\n";
						}
					}
				}
				
				$values2 = array();
				for ($i = 0 + $skip; $i < count($values); $i++){
					$link = false;
					$lval = array();
					for ($j = 0; $j < count($values[$i]); $j++){
						$i2 = $i;
						$val = $values[$i2][$j];
						if(isset($link_cols[$j])){
							$link = true;
							$first_col = (int)$link_cols[$j][0];
							$last_col = (int)$link_cols[$j][1];
							$joiner = $link_cols[$j][2];
							$not_unique = (bool)$link_cols[$j][3];
							$not_trim = (bool)$link_cols[$j][4];
						}
						if(!$link){
							$values2[$i][] = str_replace("&semicolon&", ";", $val);
						}
						if($link and $j > $first_col and $j < $last_col){
							if(trim($val) !== "" or $not_trim == true) $lval[] = str_replace("&semicolon&", ";", $val);
							if($j == $last_col or $j == count($values)){
								if($not_unique == false) $lval = array_unique($lval);
								$values2[$i][] = implode($joiner, $lval);
								$link = $not_unique = $not_trim = false;
								$lval = array();	
							}
						}
					}
				}
				if(count($link_cols) and count($values))
					return $values2;
			}
			
			return $values;
		}else{
			return false;	
		}		
	}

	public function UpdateValues($fields, $items, $modelName, $main_value = "article", $type = false){

		$content = array();

		$params = array();

		foreach($fields as $fkey => $field){
			if($field == $main_value){
				$ready = true;
			}
		}

		if(!$ready)
			return array("error" => $main_value);

		foreach($items as $key => $cols){
			$item = array();
			foreach($fields as $fkey => $field){
				$item[$field] = $cols[$fkey];
			}
			$params[] = $item;
		}

		if (count($params) > 0)
        { 
        	foreach ($params as $article => $subParams)
        	{
        		//$subParams = new Parameters($subParams);

        		//$errors = self::Validate($subParams, $modelName, $formName, CMF_VALIDATE_FAST);

		    	$multilangColumns = self::GetMultilangTableColumns($modelName);
		    	$columns = self::GetNoMultilangTableColumns($modelName);

				$ID = Model::$db->Value("SELECT ID FROM ".$modelName." WHERE ".$main_value." = '".Checker::Escape($subParams[$main_value])."'");

		    	if ((int)$ID)
		    	{
					$query = $multilangQuery = '';

					$f = false;

					if(isset($subParams["sale"])){
						if(isset($subParams["price"])){
							$subParams["old_price"] = $subParams["price"] / ((100 - (float)$subParams["sale"]) / 100);
						}elseif($subParams["old_price"]){
							$subParams["price"] = $subParams["old_price"] * ((100 - (float)$subParams["sale"]) / 100);
						}
						unset($subParams["sale"]);
					}

					foreach ($subParams as $key => $value)
					{

		       			if (in_array($key, $columns))
		       				$query .= $key . " = '" . $value . "', ";
		       			elseif (in_array($key, $multilangColumns))
		       				$multilangQuery .= "`" . $key . "` = '" . $value . "', ";

					}

					$query .= "updated = NOW(), ";

					$query = preg_replace('#, $#isu', '', $query);
					$multilangQuery = preg_replace('#, $#isu', '', $multilangQuery);

					if ($query != '')
						$res = self::$db->Query('UPDATE `' . $modelName . '` SET ' . $query . ' WHERE ID = ' . $ID);

					if ($multilangQuery != '')
					{
						self::$db->Query("UPDATE `" . $modelName . "_ml` SET " . $multilangQuery . ' WHERE lang_ID = ' . Application::$language->ID . ' AND ID = ' . $ID);
					}

		    	}
        	}
        }

		return $content;

	}

	public function InsertValues($fields, $items, $modelName, $main_value = "article", $config = array()){

		$content = array();

		$params = array();

		if($main_value !== ""){
			foreach($fields as $fkey => $field){
				if($field == $main_value){
					$ready = true;
				}
			}
		}else $ready = true;

		if(!$ready)
			return array("error" => $main_value);

		foreach($items as $key => $cols){
			$item = array();
			foreach($fields as $fkey => $field){
				if(trim($field) !== "")
					$item[$field] = $cols[$fkey];
			}
			$params[] = $item;
		}

		$images_i = 0;

		if(count($params) > 0)
        {
        	foreach($params as $key => $arrParams)
        	{
				$tableColumns = DatabaseObject::GetTableColumns($modelName);
				if(in_array("pos", $tableColumns))
					$arrParams["pos"] = DatabaseObject::GetMaxPos($modelName);

        		$subParams = new Parameters($arrParams);

				$errors = new Parameters();

        		$object = DatabaseObject::Create($modelName, "create", $subParams, $errors);

				if($object){
					// Загрузка изображений
					if($config["images_dir"] !== "" and $images_dir = $config["images_dir"]){
						
						if(isset($arrParams["count_img"])){
							if($config["images_dir"] !== "" and $images_dir = $config["images_dir"]){
								if(!isset($images_list)){ 
									$images_list = Utils::GetFileList(Model::$conf->documentroot.substr($config["images_dir"], 0, -1));	
									Utils::USort($images_list, "name", "string");
								}
								$pImages = array();
								for($i = 0; $i < (int)$arrParams["count_img"]; $i++){
									$pImages[] = $images_list[$images_i + $i]['name'];
								}
								$images_i += (int)$arrParams["count_img"];
	
							}
						}else{
							$pImages = explode(",", $arrParams["images"]);
						} 
						if(count($pImages) > 0){
							$n = 1;
							foreach($pImages as $value){
								$importImage = Model::$conf->documentroot.$images_dir.$value;
								if(file_exists($importImage) and trim($value) !== ""){
									// Добавление изображения в таблицу Uploads
									$md5 = md5_file($importImage);
									$done = false;
									if(copy($importImage, Model::$conf->documentroot . "/tmp/" . $md5)){
										$done = Model::$db->Query("INSERT INTO Uploads SET name = '" . addslashes($value) . "',
																		 size = " . filesize($importImage) . ",
																		 md5 = '" . $md5 . "'");
									}
									if($done){
										$checker = new Checker("File");
										list($file) = $checker->Get($value);
										if($file){
											$content = $object->SaveImages($file);
											if($n == 1){
												$base_image = $content["file_name"];
												$base_image_url = $importImage;
											}
										}
									}
								}
								$n++;
							}
							
							$object->SetBaseImage($base_image);
						}
					}
					
				}

				if(!$object){
					$content = array_merge($content, $errors->GetInfoUnEscape());
					return $content;
				}else{
					$content['mess'] = lang('Добавление прошло успешно');
				}
        	}
        }

		return $content;

	}

	public function AssignParams($params){
		$files = $_FILES["params"];
		$skip = (isset($_POST["params"]["skip"])) ? (int)$_POST["params"]["skip"] : 1;
		$columns_id = ((int)$_POST["params"]["columns_id"]) ? (int)$_POST["params"]["columns_id"] : 0;
		$separator = (trim($_POST["params"]["separator"]) !== "") ? trim($_POST["params"]["separator"]) : ";";

		return array($files, $skip, $columns_id, $separator);
	}

	public function SkipRows(&$values, $skip = 1){
		for($i = 0; $i < $skip; $i++){
			unset($values[$i]);
		}
	}

	public function AssignSelectedColumns(&$content, $values, $columns_id = 0){

		if($columns_id > 0){
			$right_row_id = $columns_id - 1;
			$n = 1;
			foreach($values[$right_row_id] as $k => $v){
				$content["selected_columns"][$n] = $v;
				$n++;
			}
		}elseif(count($content["selected_columns"]) > 0){
			$n = 1;
			$sc = $content["selected_columns"];
			unset($content["selected_columns"]);
			foreach($sc as $k => $v){
				$content["selected_columns"][$n] = $v;
				$n++;
			}
		}

	}

	public function NormalizeValues($values, $content){
		$col_items_count = $row_items_count = array();
		if(count($values)){
			$r = 1;
			foreach($values as $key => $value){
				unset($values[$key]);
				if(!$row_items_count[$r]) $row_items_count[$r] = 0;
				$n = 1;
				foreach($value as $k => $v){
					if(!$col_items_count[$n]) $col_items_count[$n] = 0;
					$values[$key]["cols"][$n]["value"] = (strpos($v, "base64_encoded_") !== 0) ? htmlspecialchars($v) : htmlspecialchars(base64_decode(substr($v, 14)));
					$values[$key]["cols"][$n]["selected_col"] = $content["selected_columns"][$n];
					if(trim($v) !== ""){
						$col_items_count[$n]++;
						$row_items_count[$r]++;
					}
					$n++;
				}
				$r++;
			}

			Import::UnsetEmptyColumns($values, $col_items_count, $row_items_count);
		}

		return $values;
	}

	public function UnsetEmptyColumns(&$values, $col_items_count, $row_items_count = array()){
		foreach($values as $key => $value){
			foreach($col_items_count as $k => $num){
				if($num == 0)
					unset($values[$key]["cols"][$k]);
			}
		}
		if(count($row_items_count) > 0){
			foreach($row_items_count as $k => $num){
				if($num == 0)
					unset($values[$k--]);
			}
		}
	}

	public function GetAllTableColumns(&$content, $modelName){

		$tableColumns = Item::GetNoMultilangTableColumns($modelName);

		foreach($tableColumns as $key => $value){
			$content["columns"][]["value"] = $value;
		}

		$tableColumnsMl = Item::GetMultilangTableColumns($modelName);

		foreach($tableColumnsMl as $key => $value){
			/*foreach(Language::GetLanguages() as $k => $v){
				$content["columns"][]["value"] = $value.'_lang_'.$v["name"];
			}*/
			$content["columns"][]["value"] = $value;
		}

	}

	public function Upload($modelName, $main_field, $type = false){

		$fields = (count($_POST["fields"])) ? $_POST["fields"] : false;
		$items = (count($_POST["items"])) ? $_POST["items"] : false;
		$items = (isset($_POST["items_array"])) ? array_merge($items, unserialize(base64_decode($_POST["items_array"]))) : $items;

		if($fields and $items){
			$content = Import::UpdateValues($fields, $items, $modelName, $main_field, $type);
			if($content["error"]){
				if($content["error"] == $main_field){
					$content["mess"] = lang("Необходимо выбрать поле")." ".$main_field;
				}
				$content["err"] = 1;
			}else{
				$content["mess"] = lang("Изменения успешно внесены");
			}
		}

		return $content;

	}

	public function Insert($modelName, $main_field = ""){

		$fields = (count($_POST["fields"])) ? $_POST["fields"] : false;
		$items = (count($_POST["items"])) ? $_POST["items"] : false;

		$params["images_dir"] = (trim($_POST["params"]["images_dir"]) !== "") ? trim($_POST["params"]["images_dir"]) : "/import/";

		if($fields and $items){
			$content = Import::InsertValues($fields, $items, $modelName, $main_field, $params);
			if(!$content["err"]){
				$content["mess"] = lang("Записи успешно добавлены");
			}else{
				$content["import_error"] = 1;
				$errors = $content["errors"];
				$content["errors"] = array();
				foreach($errors as $field => $value){
					foreach($value as $key => $value2){
						$content["errors"][] = array("value" => $field.": ".$value2);
					}
				}
			}
		}

		return $content;

	}

}

?>