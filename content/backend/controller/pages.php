<?php

class controller_pages extends crud_controller_tree
{
    public function __construct()
    {
        $this->modelName = 'Pages';
        $this->controllerName = 'pages';
    }

    public function edit($object)
    {
        $content = parent::edit($object);

        $content['pages_tpl'] = Pages::GetTemplates();

        return $content;
    }
}