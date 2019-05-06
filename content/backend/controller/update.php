<?php

class controller_update extends crud_controller_tree
{
	
	public function __construct()
	{
		$this->modelName = 'Update';
        $this->controllerName = 'update';
	}
	
	public function edit()
	{
		$content = array();
	
		if(count($_FILES)){
			try{
				
				$file = array_shift($_FILES);
				$content['update_info'] = Update::Unpack($file);
				$content['mess'] = lang('Архив распакован');
				
			}catch(Exception $ex){
				
				$content['err'] = 1;
				$content['mess'] = $ex->GetMessage();	
				
			}
		}
	
		return $content;
	}
	
	public function update($step, $params = array(), $init = '')
	{
		$content = array('state' => 'success', 'step' => (int)$step, 'next_step' => (int)$step+1);
	
		$step = (int)$step;
		
		$init = (trim($init) !== '') ? true : false;
			
		Update::IncludeUpdate();
		$update = Update::InitUpdateObject();
		
		if($init){
			Update::SetExternalParams($params);	
		}
		
		$content['steps'] = count($update->GetSteps());
			
		if(!$init){
			try{
				
				$content = array_merge($content, Update::ExecuteUpdate($step));
				
				if($content['steps'] == $step){
					$content["finish"] = 1;
				}
				
			}catch(Exception $ex){
					
				$state = array(Update::ERROR_WARNING => 'warning', Update::ERROR_FATAL => 'danger');
				$content['state'] = $state[$ex->getCode()];
				if(trim($content['state']) == '') $content['state'] = 'danger';
				$content['mess'] = $ex->GetMessage();	
				
				if($content['state'] = 'danger'){
					$content["err"] = 1;	
				}
				
			}
		}
		
		$content['progress'] = 100 / $content['steps'];
		
		$content['actions'] = $update->GetSteps();
		
		$icons = array(
			'success' => 'ok',
			'warning' => 'warning-sign',
			'danger' => 'remove',
			'precess' => 'hourglass'
		);
		
		$content['icon'] = $icons[$content['state']];
		$content['icon_process'] = $icons['precess'];
	
		return $content;
	}

}