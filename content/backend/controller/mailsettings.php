<?php

class controller_mailsettings extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Mailsettings';
		$this->controllerName = 'mailsettings';
		
		// Titles
		$this->pageTitle = lang("Настройка почты");
		$this->pageAction = "NULL";
	}
	
	public function edit($object){
		
		$object = 1;
		
		return parent::edit($object);
			
	}
	
	public function save($object, $params){
		
		if((int)$object == 0) return array();
		
		return parent::save($object, $params);
			
	}

}

?>