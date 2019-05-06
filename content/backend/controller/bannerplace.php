<?php

class controller_bannerplace extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Bannerplace';
		$this->controllerName = 'bannerplace';
	}
	
	public function edit(&$object)
  	{
  		$content = parent::edit($object);
  		if($object){
  			$content['banners_controller'] = 'banners/edit/0/'.$object->ID;
			$content['banners_list_controller'] = 'banners/items/'.$object->ID;
		}

  		return $content;
  	}

}

?>