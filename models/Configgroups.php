<?php
class Configgroups extends DatabaseObject
{
	public function __construct($ID)
	{
        $this->modelName = 'Configgroups';
		$this->controllerName = 'config_groups';

		parent::__construct($ID);
	}
}

?>