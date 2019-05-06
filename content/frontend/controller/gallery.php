<?php

use Hex\App;

class controller_gallery
{
    public function index()
    {
        return [
            'title' => Infoblock::getText('gallery_header'),
            'gallery' => Gallery::getAll()
        ];
    }

    public function items()
    {
        return [
            'page_title' => strip_tags($title = Infoblock::getText('gallery_header')),
            'title' => $title,
            'gallery' => Gallery::getAll()
        ];
    }

    public function info()
    {
        $gallery = Application::$mainObjectData;

        $images = array_filter($gallery->getImages(), function($image) use ($gallery) {
            return $image['name'] != $gallery->base_image;
        });

        return [
            'page_title' => strip_tags(Infoblock::getText('gallery_header')),
            'title' => $gallery->title,
            'images' => $images
        ];
    }
}