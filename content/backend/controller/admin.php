<?php
class controller_admin
{
	public $models = array();

	public function admin($controller, $action, $object, $sec_object, $third_object)
	{
		$content = array();
		
		$user = User::GetCurrentUser();

        if (($controller && $action && ($user->type == "admin" || $user->type == "moder" || $user->type == "developer")) or (Application::$returnType == "ajax" and $controller == "users" and $action == "validate")){
    		$content["ctr"] = $controller . "/" . $action . "";
			Model::$session->info["cms_controller"] = $controller;
			Model::$session->info["cms_action"] = $action;
			
			/*Model::$session->info["cms_noLangRequest"] = Application::$noLangRequest;
			Model::$session->Save();*/
		}else{
			$content["ctr"] = "users/validate_form";
			Model::$session->info["cms_controller"] = "users";
			Model::$session->info["cms_action"] = "validate_form";
		}

    	if ($object || $object == 0){
			$content["ctr"] = $content["ctr"] . "/" . $object. "/" . $sec_object . "/" . $third_object;
			Model::$session->info["cms_object"] = $object;
		}

    	$content["ctr"] .= " admin-content";

    	return $content;
	}
	
	public function main_page(){
		
		$content = array();
		
		return $content;
		
	}

}

?>