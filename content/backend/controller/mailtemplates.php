<?php

class controller_mailtemplates extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Mailtemplates';
        $this->controllerName = 'mailtemplates';
		
		// Действия
		$this->actions["fast_save"] = false;
	}

}

?>