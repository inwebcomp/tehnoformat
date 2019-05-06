<?php

class Socials extends databaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Socials';
        $this->controllerName = 'socials';
		
		parent::__construct($ID);
	}
}

?>