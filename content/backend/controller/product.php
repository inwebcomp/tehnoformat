<?php

class controller_product extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Product';
        $this->controllerName = 'product';

        // Addons
        $this->addons = new stdClass();
        $this->addons->images = true;
	}
}