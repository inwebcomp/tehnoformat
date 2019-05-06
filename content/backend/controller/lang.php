<?php

class controller_lang extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Lang';
        $this->controllerName = 'lang';
	}
}