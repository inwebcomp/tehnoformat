<?php

class controller_works extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Works';
        $this->controllerName = 'works';
		
		// Addons
		$this->addons = new stdClass();
		$this->addons->images = true;
	}

}