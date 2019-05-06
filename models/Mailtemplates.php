<?php

class Mailtemplates extends databaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Mailtemplates';
        $this->controllerName = 'mailtemplates';
		
		parent::__construct($ID);
	}
	
	public static function CreateLetter($template, $params){
		
		$checker = new Checker("Mailtemplates");
		list($template) = $checker->Get($template);
		
		if($template){
			
			foreach($params as $key => $value){
				$templatevars[] = "{".$key."}";
				$vars[] = $value;
			}
			
			$subject = str_replace($templatevars, $vars, $template->subject);
			
			$file = Model::$conf->mailtemplatesPath.'/'.$template->name.'_'.Application::$language->name.'.html';
			
			if(file_exists($file))
				$template_text = file_get_contents($file);
			else
				$template_text = $template->text;

			$text = str_replace($templatevars, $vars, $template_text);

			return array($subject, $text); 
		
		}
		
		return false;
		
	}
}

?>