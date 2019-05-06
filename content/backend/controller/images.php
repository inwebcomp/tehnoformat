<?php

class controller_images extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Images';
		$this->controllerName = 'images';
	}
	
	public function create_thumbnails($model, $ID, $name){
		
		$content = array_merge(self::items(array()), Images::CreateThumbnails($model, $ID, $name));
		
		return $content;
		
	}
}