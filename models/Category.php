<?php

use Hex\App\Entity;
		
class Category extends Entity
{
    protected static $findByFields = ['ID', 'name'];
    public static $poolName = 'category';
    protected static $entityName = 'Category';
    protected static $tableName = 'Category';
    protected $controllerName = 'category';

    public $nameImagesAsObject = true;

    public function path()
    {
        return '/' . (Application::$language->name == Model::$conf->default_language ? '' : Application::$language->name) . $this->name;
    }

    public static function pathStatic($data)
    {
        return '/' . (Application::$language->name == Model::$conf->default_language ? '' : Application::$language->name) . $data['name'];
    }

    public static function modifyData($data)
    {
        if ($data)
            $data['href'] = self::pathStatic($data);

        return $data;
    }

    public function children()
    {
        $categories = self::getAll(true, [
            'where' => [
                'parent_ID' => $this->ID
            ]
        ]);

        return $categories;
    }

    public function gallery()
    {
        if (! $this->gallery_ID)
            return null;

        return Gallery::one($this->gallery_ID);
    }
}