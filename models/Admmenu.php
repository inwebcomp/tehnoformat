<?php

class Admmenu extends databaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Admmenu';
        $this->controllerName = 'admmenu';

		parent::__construct($ID);
	}

	public function GetByController($controller){
		
		if($controller = Checker::Escape($controller) and $controller !== ""){
			$ID = Model::$db->Value("SELECT ID FROM `Admmenu` WHERE controller = '".$controller."' LIMIT 1");
			
			if((int)$ID > 0){
				$admmenu = new Admmenu($ID);
				return $admmenu;
			}
		}
		
		return false;
			
	}
	
	public function GetPagePath(){
		
		$controller = Model::$session->info["cms_controller"];
		$action = Model::$session->info["cms_action"];
		$object = Model::$session->info["cms_object"];
		
		if(!class_exists('controller_'.$controller)){
			if(file_exists(Model::$conf->controllerBackendPath . '/' . $controller . '.php')){
				include_once(Model::$conf->controllerBackendPath . '/' . $controller . '.php');
			}
		}
		if(class_exists('controller_'.$controller)){
			eval('$class = new controller_'.$controller.'();');
		}
		
		if(isset($class) and (isset($class->pageTitle) or isset($class->pageActions))){
			if(isset($class->pageTitle)){
				$content["pageTitle"] = $class->pageTitle;	
			}
			if(isset($class->pageActions) and isset($class->pageActions[$action])){
				$content["pageAction"] = $class->pageActions[$action];	
			}
		}else{
			$admmenu = Admmenu::GetByController($controller);
			
			if($admmenu){
				$content["pageTitle"] = $admmenu->title;	
			}
		}
		
		if($content["pageTitle"]){
			if(!isset($content["pageAction"])){
				if($action == "edit" and (string)$object !== ""){
					$content["pageAction"] = lang("Редактирование");
				}
			}
			if($action == "edit" and (string)$object == ""){
				$content["pageAction"] = lang("Добавление");
			}
		}

		if(isset($class->pageAction) and $class->pageAction == "NULL"){
			unset($content["pageAction"]);	
		}
		
		return $content;
	}
}

?>