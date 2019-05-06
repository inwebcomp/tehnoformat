<?php
class Settings extends DatabaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Settings';
		$this->controllerName = 'settings';

		parent::__construct($ID);
	}
}

?>