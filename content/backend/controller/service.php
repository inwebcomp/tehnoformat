<?php

class controller_service extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Service';
        $this->controllerName = 'service';
	}
}