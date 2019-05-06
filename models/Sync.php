<?php
class Sync {

	public static $info;
	public static $name;

	public function __construct($name = false, $get = false){ 
	
		$name = ($name ? $name : self::$name);
	
		if(($get or !isset($this->info[$name])) and User::GetCurrentUser()->NonAnonymous())
			$data = Model::$db->Value("SELECT `value` FROM `Synchronization` WHERE login = '".Model::$user->login."' AND name = '".$name."'");
		else
			$data = $this->info[$name];
		
		$this->info[$name] = (!is_array($data) ? unserialize($data) : $data);

	}

	public function Init($name, $value = array())
    {
		if(User::GetCurrentUser()->NonAnonymous()){
			self::SetName($name);
			if(self::Check() == false){
				self::Create($value);
			}
			
			return new Sync($name);
		}else{
			return false;
		}
    }

	public function SetName($name)
    {
    	self::$name = $name;
    }

    public function Set($value)
    {
    	self::$info[self::$name] = $value;
    }

    public function Get($name = false)
    {
    	return ($name ? $this->info[$name] : $this->info[self::$name]);
    }

	public function Check($name = false)
    {
		return (Model::$db->Value("SELECT COUNT(ID) FROM `Synchronization` WHERE login = '".Model::$user->login."' AND name = '".(!$name ? self::$name : $name)."'") > 0 ? true : false);
    }

	public function Create($value = array())
    {
		Model::$db->Query("INSERT INTO `Synchronization` SET login = '".Model::$user->login."', name = '".self::$name."', value = '".addslashes(serialize($value))."', created = NOW(), updated = NOW()");
		self::Set($value);
    }

    public function Save($name = false)
    {
		Model::$db->Query("UPDATE `Synchronization` SET value = '".addslashes(serialize(($name ? $this->info[$name] : $this->info[self::$name])))."', updated = NOW() WHERE login = '".Model::$user->login."' AND name = '".(!$name ? self::$name : $name)."'");
    }

}
?>