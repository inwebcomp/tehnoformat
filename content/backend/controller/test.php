<?php

class controller_test extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Test';
        $this->controllerName = 'test';
	}

}