<?php

class controller_backup extends crud_controller_tree
{
	
	public function __construct()
	{
		$this->modelName = 'Backup';
        $this->controllerName = 'backup';
	}
	
	public function items()
	{
		$content = array();
		
		$content['items'] = Backup::GetBackupsList();	
		$content['select']['num'] = count($content['items']);
		
		Controller::AssignActions($this, $content);

		return $content;
	}
	
	public function edit($object)
	{
		$content = array();
		
		$backup = new Backup($object);

		$content = $backup->GetBackupInfo();	
		
		if(isset($_POST['submit'])){
			try{
				$backup->Install();
				$content['mess'] = lang('Восстановление прошло успешно');
			}catch(Exception $ex){
				$content['err'] = 1;
				$content['mess'] = $ex->getMessage();
			}
		}
		
		return $content;
	}

}