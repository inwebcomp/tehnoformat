<?php

class controller_item_copy extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'ItemCopy';
        $this->controllerName = 'item_copy';

		$this->addons = new stdClass();
		$this->addons->images = true;
		
		// Действия
		$this->actions["fast_add"] = false;
	}

	
	
	public function items($params)
	{
		if(!isset($params["order"]))
			$params["order"] = "updated"; 
		
		if(!isset($params["orderDirection"]))
			$params["orderDirection"] = "DESC"; 

		$params['in']['category_ID'] = '_NULL';

		$content = parent::items($params);

		$checker = new Checker('Category');
		foreach($content['items'] as $key => $value){
			list($category) = $checker->Get($value['category_ID']);
			$content['items'][$key]['category'] = ($category ? $category->title : "");
			if($category and !isset($content["select"]["where"]["in"]["category_ID"])){
				$parentcategory = Category::GetMainCategory($category->ID);	
				if($parentcategory) $content['items'][$key]['category'] = ($parentcategory->title !== $content['items'][$key]['category'] ? $parentcategory->title." - " : '').$content['items'][$key]['category'];
			}

			$content['items'][$key]['person'] = Person::getName($value['person_ID']);
		}

		return $content;
	}

	public function edit(&$object)
	{
		$content = array();

		$content = parent::edit($object);

		$content['persons'] = Person::getAll();

		return $content;
	}
	
	public function save(&$object, $params)
	{
		$content = array();
		
		if(is_array($params['results'])){
			foreach ($params['results'] as $key => $value) {
				if($value['text'] == '')
					unset($params['results'][$key]);
			}
		}
		if(is_array($params['requirements'])){
			foreach ($params['requirements'] as $key => $value) {
				if($value['text'] == '')
					unset($params['requirements'][$key]);
			}
		}
		if(is_array($params['auditory'])){
			foreach ($params['auditory'] as $key => $value) {
				if($value['text'] == '')
					unset($params['auditory'][$key]);
			}
		}
		if(is_array($params['plan'])){
			foreach ($params['plan'] as $key => $value) {
				if($value['text'] == '')
					unset($params['plan'][$key]);
			}
		}

		$content = parent::save($object, $params);

		if ($params['status'] == 'accepted') {
			$copy = ItemCopy::find($object);
			$result = $copy->updateItem();
		}

		return $content;
	}
}