<?php

class controller_news extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'News';
		$this->controllerName = 'news';
	}

}