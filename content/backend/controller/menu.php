<?php

class controller_menu extends crud_controller_tree
{
    public function __construct()
    {
        $this->modelName = 'Menu';
        $this->controllerName = 'menu';
    }

    public function items($params)
    {
        $content = [];
        $checker = new Checker('Parameters');
        list($params) = $checker->Get($params);

        if (! $params instanceof Parameters) {
            $params = new Parameters();
            //$params->where->parent_ID = 1;
        }

        $params->smart = 1;

        $content = Menu::GetList("Menu", $params);

        $content = array_merge($content, DatabaseObject::GetRelationsList('Menu', [], $params));

        Controller::AssignActions($this, $content);

        return $content;
    }

    public function edit($object)
    {
        $content = parent::edit($object);

        $content['pages_tpl'] = Utils::ListDirectory(Model::$conf->pagesPath);

        return $content;
    }
}