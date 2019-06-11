<?php

use Hex\App\Entity;

class Language extends DatabaseObject
{
	static $languages = array();
	static $languagesArr = array();
	static $lang_conf = array();
	static $default_language = "ru";
	static $words_to_add = array();
	static $words_data = array();
	
	public function __construct($ID)
	{
		$this->modelName = 'Language';
        $this->controllerName = 'languages';
		
		self::$default_language = Model::$conf->default_language;
		
		parent::__construct($ID);
	}

	public static function setLanguageLocales(){
	
		$lang = array();

		self::GetLanguages();
		
		$lang_is = (Model::$session->info['admin_content_texts_lang_is']) ? Model::$session->info['admin_content_texts_lang_is'] : Application::$language->name;
		
		$lang_is = (Application::$section !== 'backend') ? Application::$language->name : $lang_is;
		 
		if(!array_key_exists($lang_is, self::$languagesArr)) { $lang_is = self::$default_language; }
		
		$lang_conf['lang'] = $lang_is;
		$lang_conf['domain'] = 'site';
		$lang_conf['locale_path'] = Model::$conf->languagePath;
		
		if(file_exists($lang_conf['locale_path']."/".$lang_conf['lang']."/".$lang_conf['domain'].".php")){
			eval(file_get_contents($lang_conf['locale_path']."/".$lang_conf['lang']."/".$lang_conf['domain'].".php"));
		}
		
		Language::$languages = $lang;
		Language::$lang_conf = $lang_conf;
		
		return $languages;

	}

	public static function GetBlock(&$block){
		$block = (trim($block) == "") ? Application::$section : $block;
	}

	public static function lang($text, $block = ""){
		
		$getWords = Model::$conf->translate_words;
		
		$lang_conf = Language::$lang_conf;
		
		self::GetBlock($block);
		
		if((int)$getWords !== 0){
			if(!isset(Language::$languages[$block][$text])){
				Language::$languages[$block][$text] = "";
				Language::$words_to_add[] = array($text, "", $block);
			}
		}
		
		if(Language::$languages[$block][$text] == ''){
			$result = (Language::$languages[($block == 'frontend' ? 'backend' : 'frontend')][$text] == '') ? $text : Language::$languages[($block == 'frontend' ? 'backend' : 'frontend')][$text];
		}else{
			$result = Language::$languages[$block][$text];
		}
		
		return $result;
	
	}
	
	public static function CreateLine($text, $value = "", $block = ""){
		
		return (string)('$lang["'.addslashes($block).'"]["'.addslashes($text).'"] = "'.addslashes($value).'";'."\n"); 
	
	}
	
	public static function AddMissingWords(){
	
		if(count(self::$words_to_add) > 0){
			
			$file = self::$lang_conf['locale_path']."/".self::$lang_conf['lang']."/".self::$lang_conf['domain'].".php";
			
			$current = file_get_contents($file);
			
			foreach(self::$words_to_add as $line){
				list($text, $value, $block) = $line;
				$current .= self::CreateLine($text, $value, $block);
			}
	
			file_put_contents($file, $current);
			
		}
		
	}
	
	public static function GetLanguages(Language $selected = NULL)
    {
    	if (count(self::$languagesArr) == 0)
    	{
    		$res = self::$db->Query("SELECT * FROM `Language`");
			while ($arr = self::$db->Fetch($res))
			{
				if ($selected && $selected->ID == $arr['ID'])
					$arr['selected'] = 1;
				self::$languagesArr[$arr['name']] = $arr;
        	}
        }

    	return self::$languagesArr;
    }
	
	
	public static function GetLanguageValues($name){
		
		$content = array();
		
		$checker = new Checker('Language');
		list($object) = $checker->Get($name);
		
		if($object){
			
			$file = self::$lang_conf['locale_path']."/".$object->name."/".self::$lang_conf['domain'].".php";
			
			if(file_exists($file)){
				eval(file_get_contents($file));
			}
			
			$n = 1;
			if(is_array($lang)){
				foreach($lang as $block => $blocks){
					foreach($blocks as $key => $value){
						$arr['key'] = $n;
						$arr['name'] = $key;
						$arr['value'] = $value;
						$arr['block'] = $block;
						$content[$n] = $arr;
						$n++;
					}
				}
			}
		}
		
		return $content;
		
	}
	
	public static function GetLanguageTranslations($name){
		
		$content = array();
		
		$checker = new Checker('Language');
		list($object) = $checker->Get($name);
		
		if($object){
			
			$file = self::$lang_conf['locale_path']."/".$object->name."/".self::$lang_conf['domain'].".php";
			
			if(file_exists($file)){
				eval(file_get_contents($file));
			}
			
			Language::$languages = $lang;
		}
		
		return $content;
		
	}
	
	public static function CreateValuesText($arr, $lang){
		
		self::GetLanguageTranslations($lang);

		$file = self::$lang_conf['locale_path']."/".$lang."/".self::$lang_conf['domain'].".php";
		
		$content = file_get_contents($file);

		foreach($arr as $key => $value){
			if(!isset(Language::$languages[$value['block']][$value['value']])){
				$content .= self::CreateLine($value['value'], "", $value['block']);
			}
		}
		
		return $content;
		
	}
	
	public static function CreateValuesTextOverwrite($arr, $lang){
		
		$content = '';
		
		self::GetLanguageTranslations($lang);
		
		foreach($arr as $key => $value){
			if(isset(Language::$languages[$value['block']][$value['value']])){
				$translation = Language::$languages[$value['block']][$value['value']];
			}else{
				$translation = "";	
			}
			$content .= self::CreateLine($value["value"], $translation, $value['block']);
		}
		
		return $content;
		
	}
	
	public static function CreateTranslationsTextFromArr($arr, $lang, $block){
		
		$content = '';
		
		foreach($arr as $key => $value){
			$content .= self::CreateLine($key, $value, $block);
		}
		
		return $content;
		
	}
	
	public static function SetValues($lang, $text){
		self::setLanguageLocales();
		$file = Language::$lang_conf['locale_path']."/".$lang."/".self::$lang_conf['domain'].".php";
			
		if(file_exists($file)){
			file_put_contents($file, $text);
			return true;
		}
		
		return false;
		
	}
	
	public static function UpdateTranslations($lang, $arr, $block){
		
		self::GetLanguageTranslations($lang);
		
		unset(Language::$languages[$block]);
		
		foreach(Language::$languages as $key => $block_arr){
			$content = self::CreateTranslationsTextFromArr($block_arr, $lang, $key);
		}
		
		foreach($arr as $key => $value){
			$content .= self::CreateLine($value["name"], $value["value"], $block);
		}
		
		if(self::SetValues($lang, $content)){
			self::GetLanguageTranslations($lang);
			return true;
		}
		
		return false;
		
	}
	
	public static function WriteWordsInLocaleFile($lang, $overwrite = false){
		
		if($overwrite == "true"){
			$text = self::CreateValuesTextOverwrite(self::GetNames(), $lang);
		}else{
			$text = self::CreateValuesText(self::GetNames(), $lang);
		}
		
		self::SetValues($lang, $text);
		
		self::GetLanguageTranslations($lang);
		
		return true;
		
	}

	
	public static function GetNames(){

		$folders = array('kernel', 'content', 'cache', 'models', 'scripts', 'meta', 'Hex');
		$folders_not = array('view.php');

		$dir_begin = $_SERVER['DOCUMENT_ROOT'];
		
		self::$words_data = array();
		
		function GetDirList($dir, $folders, $folders_not){
			
			$words = array();
			
			$files = Utils::ListDirectory($dir."/");
			
			foreach($files as $key => $value){
			
				if(strpos($value['full_dir'], $folders[0]) > 0 and strpos($value['full_dir'], $folders_not[0]) == false){
					
					$type = ($value['type'] == "") ? "dir" : "file";
					
					if($type == "dir"){ 
						$words = array_merge(GetDirList($value['full_dir'], $folders, $folders_not), $words);
						/*if(is_array($words2)){ 
							if(strpos($value['full_dir'], "frontend") > 0){
								$block = "frontend";
							}else{
								$block = "backend";
							}
							foreach($words2 as $key => $value2){
								$words[] = $value2;
								Language::$words_data[] = array("value" => $value2, "block" => $block, "file" => $value['full_dir']);
							}
						}*/
					}else{  
						$words_arr = GetWords($value['full_dir']); 
						if(!empty($words_arr)){
							//$words_tmp[] = $words_arr;
							if(strpos($value['full_dir'], "frontend") > 0){
								$block = "frontend";
							}else{
								$block = "backend";
							} 
							foreach($words_arr as $key => $value2){
								$words[] = $value2;
								Language::$words_data[((string)$value2)."_".$block] = array("value" => $value2, "block" => $block, "file" => $value['full_dir']);
							}
						}
					}
					
				}
							
			}
		
			return $words;
			
		}
		
		function GetWords($file){
			
			$words = array();
			
			$content = file_get_contents($file);
			
			preg_match_all("/lang\(['\"]([\s\S]{1,}?)['\"]\)/", $content, $matches);
			
			//if($file == 'D:/Server/data/htdocs/www/content/frontend/controller/category.php')

			foreach($matches[1] as $key => $value){
				if(trim($value) !== "" and !preg_match("/::/", $value) and !preg_match("/[$][a-zA-Z0-9]{1,}/", $value)){
					//$words[] = substr(substr($value, 0, -1), 1, strlen($value) - 1); 
					$words[] = trim($value);
				}
			}
			
			preg_match_all('/err_mess=\"([^"]{1,}?)\"/', $content, $matches2);
			if(isset($matches2[1])){
				foreach($matches2[1] as $key => $value){
					if(trim($value) !== "" and !preg_match("/::/", $value) and !preg_match("/[$][a-zA-Z0-9]{1,}/", $value)){
						$words[] = $value;
					}
				}
			}
			
			preg_match_all('/description=\"([^"]{1,}?)\"/', $content, $matches3);
			if(isset($matches3[1])){
				foreach($matches3[1] as $key => $value){
					if(trim($value) !== "" and !preg_match("/::/", $value) and !preg_match("/[$][a-zA-Z0-9]{1,}/", $value)){
						$words[] = $value;
					}
				}
			}

			preg_match_all('/{%lang ([^"]{1,}?)%}/', $content, $matches4);
			if(isset($matches4[1])){
				foreach($matches4[1] as $key => $value){
					if(trim($value) !== "" and !preg_match("/::/", $value) and !preg_match("/[$][a-zA-Z0-9]{1,}/", $value)){
						$words[] = $value;
					}
				}
			}
			
			return $words;
		}
		
		$clear_words = array();
		
		foreach($folders as $key => $value){		
	
			$words = GetDirList($dir_begin, array($value), $folders_not);

			if(is_array($words)){
				foreach($words as $key => $value){
					if(is_array($value)){
						foreach($value as $key2 => $value2){
							if(!in_array($value2, $clear_words)){
								$clear_words[] = $value2;
							}
						}	
					}else{
						if(!in_array($value, $clear_words)){
							$clear_words[] = $value;
						}
					}
				}
			}
			
		}
	
		$result = array();

		foreach($clear_words as $key => $value){
			$wordf = self::$words_data[((string)$value)."_frontend"];
			$wordb = self::$words_data[((string)$value)."_backend"];
			if(isset($wordf)){
				$result[] = array(
					"value" => $wordf["value"],
					"block" => $wordf["block"]
				);
			}elseif(isset($wordb)){
				$result[] = array(
					"value" => $wordb["value"],
					"block" => $wordb["block"]
				);
			}
		}
		
		//usort($result, function($a, $b) {return $a['block'] > $b['block'];});

		return $result;
		
	}
	
	public static function MakeBlocks($content){
		
		$values = array();
		
		$titles = array(
			"backend" => lang("Админ панель"),
			"frontend" => lang("Лицевая часть сайта")
		);
		$positions = array(
			"backend" => 0,
			"frontend" => 1
		);
		
		foreach($content as $key => $value){
			if(!isset($values[$value["block"]])){
				$values[$value["block"]]["title"] = $titles[$value["block"]];
				$values[$value["block"]]["name"] = $value["block"];
				$values[$value["block"]]["position"] = $positions[$value["block"]];
			}
			$values[$value["block"]]["items"][] = $value;
		}
		
		usort($values, function($a, $b) {return $a['position'] < $b['position'];});
		
		return $values;
		
	}

	public static function OptimizeTablesToNewLanguage($new_lang_ID)
    {
		$default_language = Model::$db->Value("SELECT ID FROM Language WHERE name = '".self::$default_language."'");
		
    	$result = Model::$db->Query("SHOW TABLES FROM " . Database::$db_name);
		$row = Model::$db->Fetch($result);

		while($row = Model::$db->Fetch($result)){
			$table = current($row);

			if(preg_match('/[A-Za-z0-9_]+_ml$/', $table)){
				$arr = Model::$db->ArrayValuesQ("SELECT * FROM `".$table."` WHERE lang_ID = $default_language");

                Model::$db->Query("DELETE FROM `".$table."` WHERE lang_ID = $new_lang_ID");

				foreach($arr as $item){
					$query = "REPLACE INTO `".$table."` SET ";
					foreach($item as $col => $value){
						if(!preg_match('/^\d+$/', $col) and $col !== "lang_ID")
							$query .= $col." = '".addcslashes($value, "'\\")."', ";
					}
					$query .= "lang_ID = ".$new_lang_ID;

					Model::$db->Query($query);
				}
			}
		}
	}
	




	/** 
     *  Возвращает массив с ключом и значением без выбранного элемента
     */
	public static function getListForSelect($ID = null, $keyField = 'ID', $titleField = 'title', $onlyPublished = true)
	{
		$ID = isset($ID) ? $ID : 0;
		
		$params = new Parameters();
		$params->order = "pos";

		if ($onlyPublished)
			$params->ne->block = 1;
		
		if ($ID)
			$params->where->not->ID = $ID;
		
		$items = self::getList(static::class, $params)['items'];
			
		$newItems = $items;
		$items = array();

		foreach ($newItems as $item) {
			$items[$item[$keyField]] = $item[$titleField];
		}

		return array(0 => lang('Любой')) + $items;
	}

    public static function getAlternativePath($language)
    {
        $otherLang = $language == 'ru' ? 2 : 1;
        $otherLangName = $language == "ru" ? "ro" : "ru";
        /** @var Entity $object */
        $object = Application::$mainObjectData;

        $alternative = '';

        if ($object) {
            $model = $object->getModelName();
            $model_ml = $model . '_ml';

            if ($model_ml and in_array($model, ['Category', 'Gallery', 'Pages'])) {
                $name = Model::$db->Value("SELECT `name` FROM $model_ml WHERE ID = '{$object->ID}' AND lang_ID = '$otherLang'");
                $alternative = $name;

                if ($model == 'Gallery') {
                    $pageID = Pages::pagesMap()['gallery'];
                    $pageName = Model::$db->Value("SELECT `name` FROM Pages_ml WHERE ID = '{$pageID}' AND lang_ID = '$otherLang'");
                    $alternative = $pageName . '/' . $alternative;
                }
            } else {
                $alternative = $object->name;
            }
        }

        $alternative = ($otherLangName == Model::$conf->default_language ? '/' : '/' . $otherLangName) . ($alternative ? '/' . $alternative : '');

        $alternative = '/' . trim($alternative, '/');

        return $alternative;
    }
}