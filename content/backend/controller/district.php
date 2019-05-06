<?php

class controller_district extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'District';
        $this->controllerName = 'district';
	}
}