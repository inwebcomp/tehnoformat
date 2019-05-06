<?php

class controller_video extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Video';
		$this->controllerName = 'video';
	}

}