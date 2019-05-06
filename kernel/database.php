<?php 

class Database {

	public static $instance;
	private $db_settings;
	public static $db_name;
	private $hnd;
	public static $counter = 0;
	public static $connections = 0;
	public static $queries = array();
	public static $db = "";
	public static $errors = array();
	
	public static function GetDatabaseSettings($part = ""){
		$db_settings = array();

		if(preg_match("/localhost/", $_SERVER["HTTP_HOST"]) or $_SERVER['SESSIONNAME'] == 'Console')
        	$settingsPath = dirname(__FILE__) . "/../local_settings.cfg";
		else
			$settingsPath = dirname(__FILE__) . "/../settings.cfg";
		
		
		if(!is_file($settingsPath))
			throw new Exception(lang("Файл конфигурации базы данных не найден.")); 

		$configFile = file($settingsPath);
		foreach ($configFile as $val){
			$val = preg_replace("/(\s)|(#.*$)/isu", "", $val);
			if(!$val) continue;
			$key = $value = NULL;
			list($key, $value) = explode("==", $val);

			$key = isset($key) ? $key : NULL;
			$value = isset($value) ? $value : NULL;
			if($key){ 
				if($key == "db_user" or $key == "db_pass")
					$db_settings["permissions"][$key] = $value;
				else
					$db_settings["config"][$key] = $value;
			}
			
		}
		
		if($part == "")
			return $db_settings;
			
		if($part == "config")
			return $db_settings["config"];
		
	}

	public static function DataBaseConnect(){
		
		$db_settings = self::GetDatabaseSettings();
	
		$db_settings_private = $db_settings['permissions'];
		$db_settings = $db_settings['config'];
		
		$db = mysqli_init();
		
		if(!$db) die("MySQLi init failed");
		
		if(!$db->options(MYSQLI_INIT_COMMAND, "SET NAMES ".$db_settings["db_names"])) die("Setting MYSQLI_INIT_COMMAND failed");
		if(!$db->options(MYSQLI_INIT_COMMAND, "SET CHARACTER SET ".$db_settings["db_names"])) die("Setting MYSQLI_INIT_COMMAND failed");
		if(!$db->options(MYSQLI_INIT_COMMAND, "SET COLLATION_CONNECTION=\"".$db_settings["db_collation"]."\"")) die("Setting MYSQLI_INIT_COMMAND failed");
		if(!$db->options(MYSQLI_INIT_COMMAND, "SET time_zone = \"+02:00\"")) die("Setting MYSQLI_INIT_COMMAND failed");
		
		if(!$db->real_connect($db_settings["db_host"], $db_settings_private["db_user"], $db_settings_private["db_pass"], $db_settings["db_name"])) die("Connect Error (" . $db->connect_errno . ") " . $db->connect_error);
		
		self::$db = $db;

		self::$db_name = $db_settings["db_name"];

		self::$connections++;

		self::CheckTables($db_settings["db_name"]);

		return new Database();
	}

	public static function CheckTables($name){
		
		if(!self::$db->Query("SELECT COUNT(*) FROM `Admmenu`")){
	
			$result = self::$db->Query("SHOW TABLES FROM `".$name."`");
			
			if(!$result){
				echo lang("Ошибка базы, не удалось получить список таблиц\n");
				echo lang("Ошибка MySQL: ") . mysql_error();
				exit;
			}
		
			$row = self::Fetch($result);
			
			while($row = self::Fetch($result)){
				$table = array_shift($row);
				echo "Table: {$table}<br>";
				self::$db->Query("RENAME TABLE `{$table}` TO  `".ucfirst($table)."`");
			}
			
		}
	}

	public static function Query($q){
		
		self::$counter++;
		self::$queries[] = $q;
		
		$query = self::$db->query($q);

		if(self::$db->errno and self::$db->errno !== 1146){
			self::$errors[] =("Select Error (" . self::$db->errno . ") " . self::$db->error . "\n".$q); 
			
            if (Model::$conf->localhost) {
                echo('Select Error (' . self::$db->errno . ') ' . self::$db->error . "\n" . $q);
            } else {
				file_put_contents(Model::$conf->documentroot . '/error_log.txt', 'Select Error (' . self::$db->errno . ') ' . self::$db->error . "\n" . $q . "\n\n");
				echo 'SQL error';
			}
			exit();
		}

		return $query;
		
	}
	
	public static function Fetch(&$q, $free = true, $type = MYSQLI_ASSOC){
		
		$res = $q->fetch_array($type);
		
		if($free and is_null($res)) $q->free();
		
		return $res;
		
	}
	
	public static function Num(&$q){

		$num = $q->num_rows;
		
		return $num;
		
	}
	
	public static function NumAll($q){
		
		$q = self::Query($q);
		
		$res = self::Num($q);
		
		return $res;
		
	}
	
	public static function Value($q, $array = false) {
		
		$res = self::Query($q);
		$arr = self::Fetch($res);
		$result = NULL;

		if (is_array($arr) and $array)
			$result = $arr;
		else if (is_array($arr))
			list($result) = array_values($arr);
			
		return $result;
		
	}
	
	public static function ArrayValues(&$q){
		
		$result = array(); 
		
		while($arr = self::Fetch($q)){
			$result[] = $arr;
		}
		
		return $result;
		
	}
	
	public static function ArrayValuesQ($q){
		
		$result = array(); 
		$q = self::Query($q);
		
		while($arr = self::Fetch($q)){
			$result[] = $arr;
		}
		
		return $result;
		
	}

	public static function Row($q)
	{
		$res = self::Query($q);
		
		return self::Fetch($res);
	}

	public function SelectDB($name)
	{
		return ($this->db->select_db($name)) ? true : false;
	}

	public function Hash(&$q)
	{
		$result = array();
		
		while($arr = $this->Fetch($q))
			$result[] = $arr;

		return $result;
	}


	public function Close(&$q = false)
	{
		if($q == false)
			$q->close();
		else
			$this->db->close();
	}

	public function GetTables()
	{
		$content = array();

		$result = array();
		
		$res = self::Query("SHOW TABLES");
		while($arr = self::Fetch($res)) $result[] = array("name" => $arr[0]);
		
		return $result;
	}

	
	public static function GetInstance()
    {
        if (!isset(self::$instance))
        {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
	
	public static function GetDatabaseInfo()
    {
        return self::GetDatabaseSettings("config");
    }


	public static function Escape($val, $type = "string")
	{
		if($type == "string"){
			return mysqli_real_escape_string(self::$db, $val);
		}elseif($type == "int"){
			return intval($val);
		}elseif($type == "float"){
			return (float)$val;
		}

		return $val;
	}

	public static function escapeH($val, $type = "string")
	{
		return self::escape($val, $type);
	}
}

/*
Старый класс Database
Используется устаревший MySQL

class Database {

	public static $instance;
	private $db_settings;
	public $db_name;
	private $hnd;
	public static $counter = 0;
	public static $connections = 0;
	public static $queries = "";
	public static $db = "";
	
	public static function GetDatabaseSettings($part = ""){
		$db_settings = array();
		
		if(preg_match("/^localhost/",$_SERVER["HTTP_HOST"]))
        	$settingsPath = dirname(__FILE__) . "/../local_settings.cfg";
		else
			$settingsPath = dirname(__FILE__) . "/../settings.cfg";
		
		
		if(!is_file($settingsPath))
			throw new Exception(lang("Файл конфигурации базы данных не найден.")); 

		$configFile = file($settingsPath);
		foreach ($configFile as $val){
			$val = preg_replace("/(\s)|(#.*$)/isu", "", $val);
			if(!$val) continue;
			$key = $value = NULL;
			list($key, $value) = explode("=", $val);

			$key = isset($key) ? $key : NULL;
			$value = isset($value) ? $value : NULL;
			if($key){ 
				if($key == "db_user" or $key == "db_pass")
					$db_settings["permissions"][$key] = $value;
				else
					$db_settings["config"][$key] = $value;
			}
			
		}
		
		if($part == "")
			return $db_settings;
			
		if($part == "config")
			return $db_settings["config"];
		
	}

	public static function DataBaseConnect(){
		
		$db_settings = self::GetDatabaseSettings();

		$db_settings_private = $db_settings['permissions'];
		$db_settings = $db_settings['config'];
		
		$db = mysql_connect($db_settings['db_host'],$db_settings_private['db_user'],$db_settings_private['db_pass']) or die('Bad pass or login');
		
		self::$db = $db;
		
		mysql_query('SET NAMES '.$db_settings['db_names']);
		mysql_query('SET CHARACTER SET '.$db_settings['db_names']);
		mysql_query('SET COLLATION_CONNECTION="'.$db_settings['db_collation'].'"');
		mysql_query("SET GLOBAL time_zone = '+02:00'");
		mysql_query("SET time_zone = '+02:00'");

		
		$db_connect = mysql_select_db($db_settings['db_name'],$db) or die('Database not found');
		
		$hnd = $db;
		
		self::$connections++;

		self::CheckTables($db_settings['db_name']);

		return new Database();
	}

	public static function CheckTables($name){
		
		if(!mysql_query("SELECT COUNT(*) FROM `Admmenu`")){

			$result = mysql_query("SHOW TABLES FROM ".$name);
			
			if(!$result){
				echo lang("Ошибка базы, не удалось получить список таблиц\n");
				echo lang('Ошибка MySQL: ') . mysql_error();
				exit;
			}
			
			while($row = mysql_fetch_row($result)){
				echo lang("Таблица").": {$row[0]}<br>";
				mysql_query("RENAME TABLE `{$row[0]}` TO  `".ucfirst($row[0])."`");
			}
			
		}
	}

	public static function query($q){
		
		self::$counter++;
		self::$queries .= $q . '<br />';
		
		$query = mysql_query($q, self::$db);
		
		$error_num = mysql_errno(self::$db);
		$error = mysql_error(self::$db);
	
		if($error_num !== 1146 and $error !== ''){
			echo $error." : ". $q."<br><br>";
		}
		
		return $query;
		
	}
	
	public static function fetch($q){
		
		$res = mysql_fetch_array($q);

		return $res;
		
	}
	
	public static function num($q){
		
		$res = mysql_num_rows($q);
		
		return $res;
		
	}
	
	public static function NumAll($q){
		
		$q = self::query($q);
		$res = mysql_num_rows($q);
		
		return $res;
		
	}
	
	public static function Value($q){
		
		$res = self::query($q);
		$arr = self::fetch($res);
		$tmp = NULL;
		if(is_array($arr))
			list($tmp) = array_values($arr);
		return $tmp;
		
	}
	
	public static function ArrayValues($q){
		
		$result = array(); 
		while($arr = self::fetch($q)){
			$result[] = $arr;
		}
		
		return $result;
		
	}
	
	public static function ArrayValuesQ($q){
		
		$result = array(); 
		$q = self::Query($q);
		while($arr = self::Fetch($q)){
			$result[] = $arr;
		}
		
		return $result;
		
	}

	public static function Row($query)
	{
		$res = self::query($query);
		return self::fetch($res);
	}
	
	

	public function SelectDB($name)
	{
		return (mysql_select_db($name, $this->hnd)) ? true : false;
	}

	public function Hash($res)
	{
		$result = array();
		while ($arr = $this->Fetch($res))
			$result[] = $arr;

		return $result;
	}


	public function Close()
	{
		mysql_close($this->hnd);
	}

	public function GetTables()
	{
		$content = array();

		$result = array();
		$res = self::query("SHOW TABLES");
		while ($arr = self::fetch($res))
		{
			$arr2['name'] = $arr[0];
			$result[] = $arr2;
		}
		return $result;
	}

	
	public static function GetInstance()
    {
        if (!isset(self::$instance))
        {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
	
	public static function GetDatabaseInfo()
    {
        return self::GetDatabaseSettings("config");
    }

}*/

?>