<?php

class controller_person extends crud_controller
{
	public function __construct()
	{
		$this->modelName = 'Person';
        $this->controllerName = 'person';
	}
	
	/*public function save($object, $params)
	{
		$content = array();

		if ($params['expert'] == 'on') {
			Person::setDailyExpert($object);
		}
	
		return parent::save($object, $params);
	}*/
}