<?php

class controller_expert extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Expert';
		$this->controllerName = 'expert';
		
		// Действия
		$this->actions["fast_add"] = false;
		$this->actions["fast_save"] = false;
		$this->actions["fast_block"] = false;
		$this->actions["fast_unblock"] = false;
	}

	public function edit(&$object)
	{
		$content = array();

		$content = parent::edit($object);

		$content['persons'] = Person::getAll();
		$content['posts'] = Post::getLatestPublished();

		return $content;
	}
	
}