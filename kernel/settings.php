<?php

class KernelSettings
{
    private static $instance;
    private $settings;

    private function __construct()
    {
        $this->settings = array();

        if (!isset($this->settings['documentroot']['value']))
            $this->settings['documentroot']['value'] = $_SERVER['DOCUMENT_ROOT'];

        if (!isset($this->settings['host']['value']))
            $this->settings['host']['value'] = $_SERVER['HTTP_HOST'];

        $this->settings['debugMode']['value'] = false;
        $this->settings['protocol']['value'] = 'https';

        $this->settings['modulesPath']['value'] = $this->settings['documentroot']['value'] . '/content';

        $this->settings['contentPath']['value'] = $this->settings['documentroot']['value'] . '/content';
        $this->settings['controllerBackendPath']['value'] = $this->settings['documentroot']['value'] . '/content/backend/controller';
        $this->settings['viewBackendPath']['value'] = $this->settings['documentroot']['value'] . '/content/backend/view';
        $this->settings['pagesPath']['value'] = $this->settings['documentroot']['value'] . '/content/frontend/view/page/';
        $this->settings['kernelPath']['value'] = $this->settings['documentroot']['value'] . '/kernel';
        $this->settings['metaPathRoot']['value'] = $this->settings['documentroot']['value'] . '/meta';
        $this->settings['metaPath']['value'] = $this->settings['documentroot']['value'] . '/meta/backend';
        $this->settings['metaFrontendPath']['value'] = $this->settings['documentroot']['value'] . '/meta/frontend';
        $this->settings['modelPath']['value'] = $this->settings['documentroot']['value'] . '/models';
        $this->settings['languagePath']['value'] = $this->settings['documentroot']['value'] . '/locale';
        $this->settings['cachePath']['value'] = $this->settings['documentroot']['value'] . '/cache';
        $this->settings['cacheContentPath']['value'] = $this->settings['documentroot']['value'] . '/cache/content';
        $this->settings['cacheFilesPath']['value'] = $this->settings['documentroot']['value'] . '/cache/files';
        $this->settings['tmpPath']['value'] = $this->settings['documentroot']['value'] . '/tmp';
        $this->settings['filesPath']['value'] = $this->settings['documentroot']['value'] . '/files';
        $this->settings['exportPath']['value'] = $this->settings['documentroot']['value'] . '/export';

        $this->settings['imgPath']['value'] = $this->settings['documentroot']['value'] . '/img';
        $this->settings['mediaContent']['value'] = $this->settings['documentroot']['value'] . '/img';

        $this->settings['classesPath']['value'] = $this->settings['documentroot']['value'] . '/classes';

    }

    function __get($name)
    {
        return (isset($this->settings[$name]['value'])) ? $this->settings[$name]['value'] : NULL;
    }

    public function Set($key, $value)
    {    	$this->settings[$key]['value'] = $value;
        return true;
    }

    public static function get($key)
    {
        return self::$instance->settings[$key]['value'];
    }

    public static function GetInstance($config = true)
    {
        if (!isset(self::$instance))
        {
            $className = __CLASS__;
            self::$instance = new $className;

            if($config and class_exists("Model")){
                $res = Model::$db->Query("SELECT name, value FROM `Config`");
                while ($arr = Model::$db->Fetch($res))
                    self::$instance->Set($arr['name'], $arr['value']);
            }elseif($config){
                $db = Database::DataBaseConnect();
                $res = $db->Query("SELECT name, value FROM `Config`");
                while ($arr = $db->Fetch($res))
                    self::$instance->Set($arr['name'], $arr['value']);
            }
        }
        return self::$instance;
    }

    public static function GetSettings()
    {
        $db = Database::DataBaseConnect();
        $res = $db->Query("SELECT s.name, sl.value FROM `Settings` s LEFT JOIN `Settings_ml` sl ON s.ID = sl.ID WHERE sl.lang_ID = '".Application::$language->ID."'");
        while ($arr = $db->Fetch($res))
            self::$instance->Set($arr['name'], $arr['value']);

        return self::$instance;
    }

    public static function SetImageSettings()
    {
        $image_settings = serialize(array(
            "retina" => Model::$conf->retina,
            "recreate_images" => Model::$conf->recreate_images,
            "image_quality" => Model::$conf->image_quality,
            "cache_images" => Model::$conf->cache_images
        ));

        if(!isset($_COOKIE["image_settings"]) or $image_settings !== $_COOKIE["image_settings"]){
            $time = 60 * 60 * 24 * 30; // 30 Дней
            if(setcookie("image_settings", $image_settings, time() + $time, "/")) return true;
        }
    }

    public function GetInfo()
    {
        $info = $this->settings;
        if (count($info) > 0)
        {
            foreach ($info as $key => $value)
            {
                if (count($info) && is_null($value['value']))
                    $info[$key] = $value['value'];
                elseif (!is_null($value['value']))
                    $info[$key] = $value['value'];
                else
                    unset($info[$key]);
            }
        }
        return $info;
    }

    public static function GetEngineInfo(){

        $confidPath = Model::$conf->documentroot.'/engineInfo.cfg';

        if(!is_file($confidPath)){
            throw new Exception(lang("Файл сведений движка не найден"));
        }

        $configFile = file($confidPath);

        foreach ($configFile as $val)
        {
            $val = preg_replace('/(\s)|(#.*$)/isu', '', $val);
            if (!$val) continue;
            $key = $value = NULL;
            list($key, $value) = explode('=', $val);

            $key = isset($key) ? $key : NULL;
            $value = isset($value) ? $value : NULL;

            $config[$key] = $value;

        }

        return $config;

    }
}

?>