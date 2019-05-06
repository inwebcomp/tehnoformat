<?php

class Mailsettings extends DatabaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Mailsettings';
        $this->controllerName = 'mailsettings';
		
		parent::__construct($ID);
	}
}

?>