<?php

class controller_categorygroups extends crud_controller_tree {

	public function __construct()
	{
		$this->modelName = 'Categorygroups';
        $this->controllerName = 'categorygroups';
	}
	
	public function edit(&$object){
		
		$content = array();

        $content = parent::edit($object);

		$content["categories_array"] = (trim($content["categories"]) !== "") ? explode(",", $content["categories"]) : array();
		
		$content["categories_list"]["items"] = Category::GetCategoriesTree();
		
		return $content;
		
	}

}

?>