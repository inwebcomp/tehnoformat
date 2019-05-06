<?php

class controller_catalog extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Admmenu';
		$this->controllerName = 'admmenu';
	}	
	
	public function language() {
		
		$content = array();

		$params = new Parameters();
		$params->order = "ID";

		$content = Language::GetList("Language", $params);
		
		$content["noLangRequest"] = Model::$session->info["cms_noLangRequest"];
		
		return $content;
		
	}
	
	public function language_text($type) {
		
		$content = array();

		$params = new Parameters();
		$params->order = "ID";

		$content = Language::GetList("Language", $params);
		
		$lang = Model::$session->info['cms_text_lang'];
		$lang = ($lang) ? $lang : Language::$default_language;
		$checker = new Checker('Language');
        list($lang) = $checker->Get($lang);
			
		$content['selected_lang'] = $lang->name;

		$content["noLangRequest"] = Model::$session->info["cms_noLangRequest"];
		
		return $content;
		
	}
	
	public function language_text_set($lang) {
		
		$content = array();

		$checker = new Checker('Language');
        list($lang) = $checker->Get($lang);
		
		if($lang){
		
			Model::$session->info['cms_text_lang'] = $lang->name;
			Model::$session->Save();
			
		}
		
		return $content;
		
	}

}

?>