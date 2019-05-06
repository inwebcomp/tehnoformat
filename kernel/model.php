<?php

use Hex\App\Auth;

abstract class Model
{
	public static $session;
	public static $db;
	public static $conf;
	public static $user;

	public static function Initialize()
	{
		if (!self::$db instanceof Database)
			self::$db = Database::GetInstance(); 
		
		if (!self::$conf instanceof KernelSettings)
			self::$conf = KernelSettings::GetInstance(true, true);		

		if (!self::$session instanceof Session)
			self::$session = Session::GetInstance();
			
		if (!self::$user instanceof User)
			self::$user = User::getCurrentUser(); 
	}
}

spl_autoload_register(function ($className) {
	$interface = strpos($className, "i");
	$pModule = strpos($className, "Module");

	if($interface === 0 and $interface !== false){
		$conf = KernelSettings::GetInstance();
		if(!file_exists($conf->interfacesPath . '/' . $className . '.php'))
			throw new Exception(lang('Не найден интерфейс.') . ' [interface: ' . $className . ']');
		else
			include_once($conf->interfacesPath . '/' . $className . '.php');
			
	}elseif(strpos($className, 'Doctrine') !== false){
		$tmp = explode('\\', $className);
		$className = end($tmp); 
		$conf = KernelSettings::GetInstance();
		if (!file_exists($conf->classesPath . '/Cache/' . $className . '.php')){
			throw new Exception(lang('Не найден класс.') . '[model: ' . $className . ']'); }else{
		include_once($conf->classesPath . '/Cache/' . $className . '.php'); }
	}elseif($pModule == 0 and $pModule !== false and $className !== 'Module' and $className !== 'ModuleCore'){
		
		$conf = KernelSettings::GetInstance();
		$folder = substr($className, 6);
		if(!file_exists($conf->modulesPath . '/' . $folder . '/' . $folder . '.php'))
			throw new Exception(lang('Не найден модуль.') . ' [module: ' . $folder . ']');
		else
			include_once($conf->modulesPath . '/' . $folder . '/' . $folder . '.php');
			
	}elseif(strpos($className, "controller") === false and $className !== 'Memcached' and $className !== 'Memcache'){
		
		$conf = KernelSettings::GetInstance();
		
		$matchCore = strpos($className, "Core");
		if($matchCore !== false and $matchCore == strlen($className) - 4) $className = substr($className, 0, $matchCore);
	
		/*if($class = Controller::GetOverride($className, false) and $class)
			include_once($conf->overridePath . $class['path']);*/

		if(!file_exists($conf->modelPath . '/' . $className . '.php')){
			if(!file_exists($conf->modulesPath . '/' . $className . '/' . $className . '.php'))
				throw new Exception('Не найден класс. [model: ' . $className . ']');
			else
				include_once($conf->modulesPath . '/' . $className . '/' . $className . '.php');
		}else {
			include_once($conf->modelPath . '/' . $className . '.php');
		}
			
	}
}, true, false);