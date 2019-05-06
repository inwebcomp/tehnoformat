<?php

class controller_comment extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Comment';
        $this->controllerName = 'comment';
	}

	public function items($params)
	{
		if(! $params['order'] and (string) Model::$session->cmf_Item_default_order == '')
			$params['order'] = "created";
		if(! $params['orderDirection'] and (string) Model::$session->cmf_Item_default_orderDirection == '')
			$params['orderDirection'] = "DESC";

		$content = parent::items($params);
       
		foreach ($content['items'] as &$value) {
			$value['text'] = substr($value['text'], 0, 300).(mb_strlen($value['text'], 'UTF-8') > 300 ? '...' : '');

			if ($value['model'] == 'Item')
				$value['type'] = lang("Курс");
			if ($value['model'] == 'Post')
				$value['type'] = lang("Запись в блоге");

			$person = Person::getName($value['person_ID']);
			if ($person)
				$value['person'] = $person;
		}
		
		return $content;
	}

	public function edit($object)
	{
		$content = parent::edit($object);

		if ($content['model'] == 'Item') {
			$object = Item::find($content['object_ID']);
			
			if ($object and $object->real())
				$content['view_at_page'] = $object->name;
		}
		
		if ($content['model'] == 'Post') {
			$object = Post::find($content['object_ID']);
			
			if ($object and $object->real())
				$content['view_at_page'] = 'blog/' . $object->name;
		}

		$content['persons'] = Person::getAll();

		return $content;
	}
}