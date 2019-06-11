<?php

use Hex\App\Entity;
		
class Gallery extends Entity
{
    protected static $findByFields = ['ID', 'name'];
    public static $poolName = 'gallery';
    protected static $entityName = 'Gallery';
    protected static $tableName = 'Gallery';
    protected $controllerName = 'gallery';

    public $nameImagesAsObject = true;

    public function path()
    {
        return '/' . (Application::$language->name == Model::$conf->default_language ? '' : Application::$language->name . '/') . Pages::getUrlName('gallery'). '/' . $this->name;
    }

    public static function pathStatic($data)
    {
        return '/' . (Application::$language->name == Model::$conf->default_language ? '' : Application::$language->name . '/') . Pages::getUrlName('gallery'). '/' . $data['name'];
    }

    public static function modifyData($data)
    {
        if ($data)
            $data['href'] = self::pathStatic($data);

        return $data;
    }

    public function withImages($number = null)
    {
        $images = array_filter($this->getImages(), function($image) {
            return $image['name'] != $this->base_image;
        });

        if ($number) {
            $images = array_slice($images, 0, $number);
        }

        $this->info['images'] = $images;

        return $this;
    }
}