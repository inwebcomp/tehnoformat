<?php

class controller_location extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Location';
        $this->controllerName = 'location';
	}

}