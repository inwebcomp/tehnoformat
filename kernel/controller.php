<?php

use Hex\App\Pools;

class Controller
{
    protected $conf;
    protected $info;
    public $controller;
    public static $groupParams;

    public function __construct($controllerName, $section)
    {
        $conf = KernelSettings::GetInstance();

        $this->info["name"] = $controllerName;
        $this->info["className"] = "controller_" . $this->info["name"];
        $this->info["filePath"] = $conf->modulesPath . "/" . $section . "/controller/" . $this->info["name"] . ".php";

        if (!is_file($this->info["filePath"]))
            throw new Exception(lang("Контроллер не найден.") . "[controller: " . $this->info["name"] . "]");

        include_once $this->info["filePath"];
        $this->controller = new $this->info["className"];
    }

    function __get($name)
    {
        if ($this->IsAction($name))
        {
            return new Action($name, $this);
        }
        else
            return (isset($this->info[$name])) ? $this->info[$name] : NULL;
    }

    public function SetGroupParams($params)
    {

    }

    public function GetInfo()
    {
        return $this->info;
    }

    function __call($actionName, $args = array())
    {

        $paramsFlag = true;
        if (!$this->IsAction($actionName))
        {

            $actionName = "index";
            $paramsFlag = false;
            if (!$this->IsAction($actionName))
                throw new Exception(lang("Действие по умолчанию не найдено.") . "[action: index, controller: " . $this->info["name"] . "]");
        }

        $argsString = "";
        $filteredParams = array();
        $action = new Action($actionName, $this);
        if ($paramsFlag && count($args) > 0)
            $filteredParams = $action->GetFilteredParams($args[0]);

        for ($i = 0; $i < count($filteredParams); $i++)
            $argsString .= ("\$filteredParams[" .$i . "]" . (($i != (count($filteredParams)-1)) ? ", " : ""));

        eval("\$content = \$this->controller->" . $actionName . "(" . $argsString . ");");

        return $content;
    }

    public function IsAction($actionName)
    {
        try
        {
            $action = new ReflectionMethod($this->info["className"], $actionName);
            return true;
        }
        catch (Exception $ex)
        {
            return false;
        }
    }

    public static function GetControllers($section, $selected = NULL)
    {
        $content = array();
        $conf = KernelSettings::GetInstance();
        $content = Utils::GetFileList($conf->modulesPath . "/" . $section . "/controller/");
        for ($i = 0; $i < count($content); $i++)
        {
            $content[$i]["name"] = preg_replace("#\.php$#", "", $content[$i]["name"]);
            if ($selected == $content[$i]["name"])
                $content[$i]["selected"] = 1;
        }

        return $content;
    }

    public function GetPublicMethods($selected = NULL)
    {
        $content = array();

        foreach (get_class_methods($this->className) as $name)
        {
            $method = new ReflectionMethod($this->className, $name);
            if ($name == "__construct")
                continue;

            if ($method->isPublic())
            {
                $arr = array();
                $arr["name"] = $name;
                if ($selected == $name)
                    $arr["selected"] = 1;

                $content[] = $arr;
            }
        }

        return $content;
    }

    public function GetRights($group)
    {
        $content = array();

        $key = $group . '.' . $this->info["name"];

        $pool = Pools::find('method_rights');

        if ($pool->has($key)) {
            return $pool->get($key);
        }

        $res = Model::$db->Query("SELECT `method` FROM `Rights` WHERE `controller` = '" . $this->info["name"] . "' AND `group` = '" . $group."' AND `allow` = 0");
        $nonRights = array();
        while($arr = Model::$db->Fetch($res))
            $nonRights[] = $arr["method"];

        foreach(get_class_methods($this->className) as $name)
        {
            $method = new ReflectionMethod($this->className, $name);
            if($name == "__construct")
                continue;

            if($method->isPublic()){
                if(!in_array($name, $nonRights))
                    $content["__" . $name] = 1;
            }
        }

        $pool->set($key, $content);

        return $content;
    }

    public function AssignActions($controller, &$content)
    {
        $content["controller_actions_fast_actions"] = 1;
        $content["controller_actions_fast_add"] = 1;
        $content["controller_actions_fast_save"] = 1;
        $content["controller_actions_fast_block"] = 1;
        $content["controller_actions_fast_unblock"] = 1;
        $content["controller_actions_fast_delete"] = 1;

        $content["controller_actions_export"] = 0;

        if(isset($controller->actions)){
            $actions = $controller->actions;

            if(is_array($actions)){
                foreach($actions as $action => $value){
                    if((string)$value !== ""){
                        $content["controller_actions_".$action] = $value;
                    }elseif(isset($content["controller_actions_".$action])){
                        unset($content["controller_actions_".$action]);
                    }
                }
            }
        }
    }

}

?>