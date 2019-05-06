<?php
class controller_languages extends crud_controller
{
	public function __construct()
	{
		$this->modelName = "Language";
		$this->controllerName = "languages";
	}
	
	public function edit(&$object)
	{
		$content = array();
		$content = parent::edit($object);

		if($object){
			$content['values'] = Language::GetLanguageValues($content['name']);
			$content['values'] = Language::MakeBlocks($content['values']);
		}

		return $content;
	}
	
	public function index_phrases($object, $overwrite = false)
	{
		$content = self::edit($object);

		if(Language::WriteWordsInLocaleFile($content["name"], $overwrite)){
			$content["mess"] = lang("Фразы успешно проиндексированы");
			
			$content['values'] = Language::GetLanguageValues($content['name']);
			$content['values'] = Language::MakeBlocks($content['values']);	
		}
		
		return $content;
	}
	
	
	public function language_values($name){
		
		$content['values'] = Language::GetLanguageValues($name);
		
		$content['values'] = Language::MakeBlocks($content['values']);	
		
		$content['name'] = $name;
		
		return $content;
		
	}
	
	public function fast_values_save($elements, $object){
		
		$content = array();
		
		$checker = new Checker('Language');
		list($object) = $checker->Get($object);
		
		if($object){

			$values = Language::GetLanguageValues($object->name);
			
			foreach($elements as $key => $value){
				$block = $key;	
			}
			
			$words = array();
			
			foreach($elements[$block] as $key => $value){
				$words[$values[$key]['name']]['value'] = $value;
				$words[$values[$key]['name']]['block'] = $block;
				$words[$values[$key]['name']]['name'] = $values[$key]['name'];
			}
			
			if(Language::UpdateTranslations($object->name, $words, $block)){
				$content["mess"] = lang("Сохранение прошло успешно");	
			}else{
				$content["mess"] = lang("Произошла ошибка при сохранении");	
				$content["err"] = 1;
			}
			
			$content['values'] = Language::GetLanguageValues($object->name);
			$content['values'] = Language::MakeBlocks($content['values']);	
			
			$content['name'] = $object->name;
		}
		
		return $content;
		
	}
	
}
?>