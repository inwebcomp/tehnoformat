<?php
class Rights extends DatabaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Rights';
        $this->controllerName = 'rights';
		
		parent::__construct($ID);
	}
}

?>