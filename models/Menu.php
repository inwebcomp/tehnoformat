<?php

class Menu extends databaseObject
{
    public function __construct($ID)
    {
        $this->modelName = 'Menu';
        $this->controllerName = 'menu';

        parent::__construct($ID);
    }

    public static function FillMenu(&$content, $count_items = false)
    {
        $pChecker = new Checker("Pages");

        foreach ($content["items"] as &$value) {
            if (! isset($value["type"]))
                continue;

            if ($value["type"] == "page") {
                list($page) = $pChecker->Get($value["page"]);
                if ($page) {
                    if ((int) $value["auto_title"] == 1) {
                        $value["title"] = $page->title;
                    }
//                    if ($page->tpl == "page"){
//                        $value["href"] = "http://".Model::$conf->host."/".Application::$language->name."/page/view/".$page->name;
//                    }else{
                        $value["href"] = Model::$conf->protocol . "://" . Model::$conf->host . (Application::$language->name == Model::$conf->default_language ? '' : '/' . Application::$language->name) . '/' . $page->name;
                }
            }

            if (isset($value["_itemsTree"]) and $value["_itemsTree"]["count"] == 0) {
                unset($value["_itemsTree"]);
            } else if (isset($value["_itemsTree"])) {
                self::FillMenu($value["_itemsTree"]);
            }
        }
    }
}