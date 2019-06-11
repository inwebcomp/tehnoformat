<?php

use Hex\App\Entity;
		
class Service extends Entity
{
    protected static $findByFields = ['ID'];
    public static $poolName = 'service';
    protected static $entityName = 'Service';
    protected static $tableName = 'Service';
    protected $controllerName = 'service';

    public function path()
    {
        $page = Pages::one($this->page_ID);

        if (! $page)
            return null;

        return '/' . (Application::$language->name == Model::$conf->default_language ? '' : Application::$language->name . '/') . $page->name;
    }

    public static function pathStatic($data)
    {
        $page = Pages::one($data['page_ID']);

        if (! $page)
            return null;

        return '/' . (Application::$language->name == Model::$conf->default_language ? '' : Application::$language->name . '/') . $page->name;
    }

    public static function modifyData($data)
    {
        if ($data)
            $data['href'] = self::pathStatic($data);

        return $data;
    }
}