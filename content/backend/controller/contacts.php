<?php
class controller_contacts extends crud_controller
{
	public function __construct()
	{
		$this->modelName = "Contacts";
		$this->controllerName = "contacts";
	}
}
?>