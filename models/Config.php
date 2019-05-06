<?php
class Config extends DatabaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Config';
		$this->controllerName = 'config';

		parent::__construct($ID);
	}
}

?>