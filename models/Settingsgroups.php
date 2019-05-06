<?php
class Settingsgroups extends DatabaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Settingsgroups';
		$this->controllerName = 'settings_groups';

		parent::__construct($ID);
	}
}

?>