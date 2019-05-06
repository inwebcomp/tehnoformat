<?php

use Hex\App\Auth;

class controller_category
{
    public function top()
    {
        return ['categories' => Category::getAll()];
    }

    public function items()
    {
        return [
            'categories' => Category::getAll(),
            'title' => Infoblock::getText('category_header'),
            'text' => Infoblock::getText('category_text'),
        ];
    }

    public function list()
    {
        $category = Application::$mainObjectData;

        $main = $category->parent_ID != 1 ? Category::one($category->parent_ID) : $category;

        $children = $main->children();

        return [
            'title' => $main->title,
            'href' => $main->path(),
            'selected' => $category->ID,
            'categories' => $children,
        ];
    }

    public function info($object)
    {
        $category = Application::$mainObjectData ?? Category::one($object);

        $gallery = $category->gallery();

        return array_merge($category->toArray(), [
            'gallery' => $gallery ? $gallery->withImages(6)->toArray() : null
        ]);
    }

    public function save() {
        if (! Auth::logined() and !Auth::getCurrentUser()->isAdmin()) {
            header('HTTP/1.1 403 Forbidden');
            return;
        }

        $ID = $_POST['ID'];
        $value = $_POST['value'];
        $value2 = $_POST['value2'];

        $category = Category::one($ID);

        if (! $category) {
            header('HTTP/1.1 404 Not Found');
            return;
        }

        Category::updateField($category->ID,'text', $value);
        Category::updateField($category->ID,'text2', $value2);
    }

    public function meta()
    {
        $content = [];

        $category = Application::$mainObjectData;

        if ($category) {
            $content['meta'] = $category->toArray();
        }

        $content['meta']["meta_title"] = (trim($content['meta']["meta_title"]) == "") ? "" : trim($content['meta']["meta_title"]);
        $content['meta']["meta_keywords"] = (trim($content['meta']["meta_keywords"]) == "") ? "" : trim($content['meta']["meta_keywords"]);
        $content['meta']["meta_description"] = (trim($content['meta']["meta_description"]) == "") ? "" : trim($content['meta']["meta_description"]);

        return $content;
    }
}