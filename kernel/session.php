<?php
class Session {

    private static $instance;
    private static $conf;
    private static $session_ID;
    public $info;

    function __construct(){

        self::$session_ID = $_COOKIE['tcmf_session'];
        $this->session_ID = $_COOKIE['tcmf_session'];

        if(trim(self::$session_ID) !== ""){
            $data = Model::$db->Value("SELECT `value` FROM `Sessions` WHERE name = '".self::$session_ID."'");
        }else{ $data = ""; }

        $this->info = unserialize($data);

    }

    /*public function SetLang(){

        $lang = Model::$session->info['user']['lang'];

        $user = User::GetCurrentUser();

        if($lang !== $user->lang){
            Model::$session->info['user']['lang'] = $user->lang;
        }

        if(!isset($lang)){
            Model::$session->info['user']['lang'] = Language::$default_language;
        }

        Model::$session->Save();

        return Model::$session->info['user']['lang'];
    }*/

    public static function GetInstance($forced = false)
    {
        if (!isset(self::$instance) or $forced)
        {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

    public static function CreateSession($initFlag = false)
    {
        if(isset($_COOKIE['tcmf_session'])){
            self::$session_ID = $_COOKIE['tcmf_session'];
        }else{
            self::$session_ID = md5(microtime());
            $initFlag = true;
        }

        if(self::CheckSession() == 0 and trim(self::$session_ID) !== ''){
            self::InitiateNewSession();
        }

        if($initFlag)
        {
            $time = 60 * 60 * 24 * 30; // 30 Дней

            $validTime = time() + $time;
            setcookie('tcmf_session', self::$session_ID, $validTime, "/");
        }

    }

    public function Remember()
    {
        return (isset($_COOKIE['tcmf_remember']) && $_COOKIE['tcmf_remember'] == 1);
    }

    public static function Initialize($remember = 0)
    {
        $className = __CLASS__;
        self::$instance = new $className($remember, true);
    }

    function __get($name)
    {
        return (isset($this->info[$name])) ? $this->info[$name] : NULL;
    }

    public function Set($key, $value)
    {
        $this->info[$key] = $value;

        return $this;
    }

    public function __set($key, $value)
    {
        $this->info[$key] = $value;
    }

    public function GetInfo()
    {
        return $this->info;
    }

    public static function CheckSession()
    {
        return Model::$db->Value("SELECT COUNT(name) FROM `Sessions` WHERE name = '".self::$session_ID."'");
    }

    public static function InitiateNewSession()
    {
        Model::$db->Query("INSERT INTO `Sessions` SET name = '".self::$session_ID."', created = NOW(), updated = NOW()");
    }

    public function Save()
    {
        $this->refreshData();
    }

    public function saveToDB()
    {
        Model::$db->Query("UPDATE `Sessions` SET value = '".addslashes(serialize($this->info))."', updated = NOW() WHERE name = '".self::$session_ID."'");
    }

    public function refreshData()
    {
        // if (trim(self::$session_ID) !== ""){
        // 	$data = Model::$db->Value("SELECT value FROM `Sessions` WHERE name = '".self::$session_ID."'");
        // }else{ $data = ""; }

        // $this->info = unserialize($data);
    }

}