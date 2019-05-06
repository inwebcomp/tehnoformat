<?php

class controller_products
{
    public function items()
    {
        $content = [
            'house' => Product::getAll('house', ['where' => ['type' => 0]]),
            'car' => Product::getAll('house', ['where' => ['type' => 1]])
        ];

        if (count($content['house']) > 4 and count($content['house']) % 2 == 1)
            $content['odd_house'] = 1;

        if (count($content['house']) > 4 and count($content['car']) % 2 == 1)
            $content['odd_car'] = 1;

        return $content;
    }

    public function info($object)
    {
        $object = Product::one($object);

        if (! $object or ! $object->published())
            return [];

        $content = $object->getInfo();

        $content['images'] = array_filter($object->getImages(), function($image) use ($object) {
            return $image['name'] != $object->base_image;
        });

        return $content;
    }

    public static function meta()
    {
        $content = [];

        $object = Application::$mainObjectData;
        $page = Pages::one(Application::$page);

        if ($object) {
            $content['meta'] = $page->GetInfo();

            $content['meta']["meta_title"] = $object->title . ' - ' . $page->meta_title;
            $content['meta']["meta_description"] = strip_tags($object->description_min);
        }

        $content['meta']["meta_title"] = (trim($content['meta']["meta_title"]) == "") ? "" : trim($content['meta']["meta_title"]);
        $content['meta']["meta_keywords"] = (trim($content['meta']["meta_keywords"]) == "") ? "" : trim($content['meta']["meta_keywords"]);
        $content['meta']["meta_description"] = (trim($content['meta']["meta_description"]) == "") ? "" : trim($content['meta']["meta_description"]);

        return $content;
    }
}