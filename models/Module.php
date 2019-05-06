<?php 
abstract class Module extends databaseObject
{
	// Module variables
	public $name;
	public $title;
	public $version;
	public $version_required = '1.0.0';
	public $author = 'Engine developer';
	public $description = "";
	
	// Error codes
	const ERROR_VERSION = 100;
	const ERROR_MODULE_STRUCTURE = 200;
	const ERROR_INSTALL = 301;
	const ERROR_REMOVE = 302;
	const ERROR_DISABLE = 303;
	const ERROR_ENABLE = 304;

	public function __construct()
	{
		if(!isset($this->name))
			throw new Exception(get_class($this) . ' missing module property $name');
		if(!isset($this->title))
			throw new Exception(get_class($this) . ' missing module property $title');
		if(!isset($this->version))
			throw new Exception(get_class($this) . ' missing module property $version');
		
		$this->CheckVersion();
		
		return $this->Init();
	}	
	
	public function CheckVersion()
	{ 
		if(version_compare(ENGINE_VERSION, $this->version_required, ">=") == -1)
			throw new Exception('Your engine version must be '.$this->version_required.' or higher', self::ERROR_VERSION);
	}
	
	public static function RenameModule($newName)
	{
	
		
	
	}
	
	public static function InstallModule()
	{
	
		
	
	}
	
	public static function RemoveModule()
	{
	
		
	
	}
	
	public static function DisableModule()
	{
	
		
	
	}
	
	public static function EnableModule()
	{
	
		
	
	}

}