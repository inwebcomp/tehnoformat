<?php

class controller_admmenu extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Admmenu';
		$this->controllerName = 'admmenu';
	}

	public function left()
	{
		$content = array();

		$params = new Parameters();
		$params->onPage = 1000;
		$params->ne->block = 1;
		$params->where->fast = 1;
		$params->where->level = 1;

		$content = Admmenu::GetList('Admmenu', $params);
		
		foreach($content['items'] as $key => $value){
			if(!class_exists('controller_'.$value['controller'])){
				if(file_exists(Model::$conf->controllerBackendPath . '/' . $value['controller'] . '.php')){
					include_once(Model::$conf->controllerBackendPath . '/' . $value['controller'] . '.php');
				}
			}
			if(class_exists('controller_'.$value['controller'])){
				eval('$class = new controller_'.$value['controller'].'();');
				$className = "controller_".$value['controller'];
				if($class->notifications){
					eval("\$content['items'][\$key]['notification'] = ".$className."::_notification();");
				}
			}
		}
		
		$multiSelect = new Parameters();
		$multiSelect->admmenu->whereThis->parent_ID = 'ID';
		$multiSelect->admmenu->ne->block = 1;
		$multiSelect->admmenu->where->fast = 1;
		
        $content = Admmenu::GetListTree("Admmenu", $params, $content, 'items', $multiSelect);
		
		$noLangArr = explode("/", $_SERVER['REQUEST_URI']);
		$content['pageController'] = $noLangArr[5];
		$content['login'] = Model::$session->info['user']['login'];

		return $content;
	}
	
	public function top()
	{
		$content = array();
		
		$content = Admmenu::GetPagePath();
		
		$content["languages"] = Language::$languagesArr;
		
		$mail_count = Model::$db->Value("SELECT COUNT(ID) FROM Mail WHERE `read` = 0");
		
		if((int)$mail_count > 0){
			$content["mail_count"] = ($mail_count > 99) ? "99+" : $mail_count;
			
			$content["mail"] = Model::$db->ArrayValuesQ("SELECT * FROM Mail WHERE `read` = 0 ORDER BY created DESC LIMIT 3");
		}
		
		return $content;
	}

	public function items($params) {
		
		$content = array();
        $checker = new Checker('Parameters');
		list($params) = $checker->Get($params);
        if (!$params instanceof Parameters){
        	$params = new Parameters();
		}
		
		$params->smart = 1;
		
		$content = Admmenu::GetList("Admmenu", $params);
		
		$content = array_merge($content, DatabaseObject::GetRelationsList('Admmenu', array(), $params));
		
		Controller::AssignActions($this, $content);
		
		return $content;
		
	}	
	
	public function menu_state_set($state) {
		
		$content = array();
		
		$state = ((int)$state) ? $state : 0;
		
		Model::$session->info['menu_state'] = $state;
		Model::$session->Save();
	
		return $content;
		
	}

}

?>