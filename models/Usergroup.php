<?php

class Usergroup extends DatabaseObject
{
	public function __construct($ID = NULL)
	{
        $this->modelName = 'Usergroup';
        $this->controllerName = 'usergroup';

        parent::__construct($ID);
	}
}

?>