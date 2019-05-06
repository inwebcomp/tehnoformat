<?php

class Alias extends DatabaseObject
{
	public function __construct($ID = NULL)
	{
		$this->modelName = 'Alias';
		$this->controllerName = 'alias';
		
		parent::__construct($ID);
	}

	public static function GetAlias($name, $lang_ID = false)
	{
		$language = ($lang_ID) ? new Language($lang_ID) : Application::$language;
		
		$object = Model::$db->Value("SELECT `Alias`.*, `Alias_ml`.* FROM `Alias` LEFT JOIN `Alias_ml` ON `Alias`.ID = `Alias_ml`.ID WHERE `Alias_ml`.lang_ID = '".$language->ID."' AND `Alias`.block = 0 AND `Alias_ml`.name = '".Database::Escape($name)."'", true);
		
		if((int)$object['ID'] and $object['root'] !== ''){
			if(strpos('/', $object['root']) === false)
				$object['root'] = '/'.$object['root'];

			$object['query'] = explode('/', $object['root']);
			$object['query'][0] = $language->name;

			return $object;
		}

		return false;
	}

	public static function GetAlternativeAlias($ID, $lang_ID = false)
	{
		$language = ($lang_ID) ? new Language($lang_ID) : Application::$language;
		
		$object = Model::$db->Value("SELECT `Alias`.*, `Alias_ml`.* FROM `Alias` LEFT JOIN `Alias_ml` ON `Alias`.ID = `Alias_ml`.ID 
		WHERE `Alias_ml`.lang_ID = '".$language->ID."' AND `Alias`.block = 0 AND `Alias_ml`.ID = '".Database::Escape($ID)."'", true);
		
		if((int)$object['ID'] and $object['root'] !== ''){
			if(strpos('/', $object['root']) === false)
				$object['root'] = '/'.$object['root'];

			$object['query'] = explode('/', $object['root']);
			$object['query'][0] = $language->name;

			return $object;
		}

		return false;
	}
}