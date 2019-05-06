<?php

class controller_help extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Help';
        $this->controllerName = 'help';
		
		// Действия
		$this->actions["fast_add"] = true;
		$this->actions["fast_save"] = true;
		$this->actions["fast_block"] = false;
		$this->actions["fast_unblock"] = false;
		$this->actions["fast_delete"] = false;
	}

}

?>