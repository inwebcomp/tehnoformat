<?php

class Help extends databaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Help';
        $this->controllerName = 'help';
		
		parent::__construct($ID);
	}

}

?>