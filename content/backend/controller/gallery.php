<?php

class controller_gallery extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Gallery';
        $this->controllerName = 'gallery';

        // Addons
        $this->addons = new stdClass();
        $this->addons->images = true;
	}
}