<?php

class controller_blog_category extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'BlogCategory';
        $this->controllerName = 'blog_category';
	}
}