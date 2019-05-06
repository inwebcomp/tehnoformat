<?php

class controller_client extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Client';
        $this->controllerName = 'client';
	}
}