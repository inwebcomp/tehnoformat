<?php

class controller_navigation
{
    public function top()
    {
        $content = [];

        $checker = new Checker("Menu");
        list($top) = $checker->Get("top");

        if ($top) {
            $params = new Parameters();
            $params->order = "pos";
            $params->where->not->block = 1;
            $params->where->parent_ID = $top->ID;

            $multiSelect = new Parameters();
            $multiSelect->menu->whereThis->parent_ID = 'ID';
            $multiSelect->menu->order = 'pos';

            $content = Menu::GetList("Menu", $params);

            $content = Menu::GetListTree("Menu", $params, $content, 'items', $multiSelect);

            Menu::FillMenu($content, true);
        }

        return $content;
    }

    public function bottom()
    {
        return $this->top();
    }

    public function mobile()
    {
        return ['categories' => Category::getAll()];
    }

    public function meta()
    {
        $content = [];

        $page = Application::$pageObject ?? Pages::one(Application::$page);

        if ($page) {
            $content['meta'] = $page->GetInfo();

            if ($content['meta']["meta_title"] == '' and $page->tpl == 'page')
                $content['meta']["meta_title"] = $content['meta']["title"];
        }

        $content['meta']["meta_title"] = (trim($content['meta']["meta_title"]) == "") ? "" : trim($content['meta']["meta_title"]);
        $content['meta']["meta_keywords"] = (trim($content['meta']["meta_keywords"]) == "") ? "" : trim($content['meta']["meta_keywords"]);
        $content['meta']["meta_description"] = (trim($content['meta']["meta_description"]) == "") ? "" : trim($content['meta']["meta_description"]);

        return $content;
    }
}