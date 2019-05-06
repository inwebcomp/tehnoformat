<?php

class controller_scent extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Scent';
        $this->controllerName = 'scent';
	}
}