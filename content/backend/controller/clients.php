<?php
class controller_clients extends crud_controller
{
	public function __construct()
	{
		$this->modelName = "Clients";
		$this->controllerName = "clients";
	}
}
?>