<?php

class controller_currency extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Currency';
        $this->controllerName = 'currency';
	}

}

?>