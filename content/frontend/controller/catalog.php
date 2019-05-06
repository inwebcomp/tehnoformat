<?php

class controller_catalog
{
    public function language()
    {
        $params = new Parameters();
        $params->ne->block = 1;

        $content = Language::GetList("Language", $params);

        foreach ($content['items'] as &$item) {
            $item['href'] = ($item['name'] == Model::$conf->default_language ? '/' : '/' . $item['name']) ;
        }

        return $content;
    }

    public function socials()
    {
        return ['items' => Socials::getAll()];
    }
}