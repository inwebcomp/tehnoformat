<?php

use Hex\App\Pools;

define('RETURN_VIEW', 1);
define('RETURN_JSON', 2);

class Action
{
    protected $controller;
    protected $info;

    public function __construct($actionName, Controller $controller)
    {
        $this->info["name"] = $actionName;
        $this->controller = $controller;
    }

    // Возвращает массив имен параметров метода
    public function GetParamNames()
    {
        $method = new ReflectionMethod($this->controller->className, $this->info['name']);
        $methodParamsArr = array();
        foreach ($method->getParameters() as $i => $param)
            $methodParamsArr[] = $param->getName();

        return $methodParamsArr;
    }

    // Возвращает количество параметров метода
    public function GetParamsCount()
    {
        $method = new ReflectionMethod($this->controller->className, $this->info['name']);
        return count($method->getParameters());
    }

    // Принимает массив значений в порядке определнном в методе и возвращает массив, отфильтрованный в соответствии с типами данных
    public function GetFilteredParams($paramsArr)
    {
        $params = array();
        $method = new ReflectionMethod($this->controller->className, $this->info['name']);

        $count = 0;
        foreach ($method->getParameters() as $i => $param)
        {
            $params[] = (isset($paramsArr[$count])) ? $paramsArr[$count] : NULL;
            $count++;
        }

        return $params;
    }

    // Проверяет права пользователя на действие
    public function CheckRights(User $user)
    {
        return true;

        $key = $this->controller->name . '.' . $this->info['name'];

        $pool = Pools::find('rights');

        if ($pool->has($key)) {
            return $pool->get($key);
        }

        $val = Model::$db->Value("SELECT `allow` FROM `Rights` WHERE `method` = '" . $this->info['name'] . "' AND `controller` = '" . $this->controller->name . "' AND `group` = '" . $user->type."'");

        $result = ($val == 1) ? true : false;
        $result = (is_null($val)) ? true : $result;

        $pool->set($key, $result);

        return $result;
    }

}