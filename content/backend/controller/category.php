<?php

class controller_category extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Category';
        $this->controllerName = 'category';
	}

}