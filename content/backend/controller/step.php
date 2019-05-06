<?php

class controller_step extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Step';
        $this->controllerName = 'step';
	}
}