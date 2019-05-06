<?php
class controller_usergroup extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Usergroup';
		$this->controllerName = 'usergroup';
	}

	public function edit(&$object)
  	{
  		$content = parent::edit($object);
		
  		if ($object){
  			$content['rights'] = 'rights/rights/' . $object->ID . ' rights';
			$content['rights_form'] = 'rights/rights_form/' . $object->ID . ' rights_form';
		}

  		return $content;
  	}

}

?>