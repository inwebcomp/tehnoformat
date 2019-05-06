<?php

class controller_infoblocks extends crud_controller
{
    public function __construct()
    {
        $this->modelName = "Infoblock";
        $this->controllerName = "infoblocks";
    }

    public function show($object)
    {
        $content = [];

        $checker = new Checker('Infoblock');
        list($object) = $checker->Get($object);
        if ($object)
            $content = $object->GetInfo();

        return $content;
    }

    public function edit(&$object)
    {
        $content = [];

        $checker = new Checker($this->modelName);
        list($object) = $checker->Get($object);

        if ($object instanceof DatabaseObject) {
            $content = $object->GetInfoUnEscape(CMF_RELATION_SIMPLE_LIST);
        } else {
            $content = DatabaseObject::GetRelationsList($this->modelName);

            $tableColumns = DatabaseObject::GetTableColumns($this->modelName);
            if (in_array('pos', $tableColumns))
                $content['pos'] = DatabaseObject::GetMaxPos($this->modelName);
        }

        $content = array_merge($content, DatabaseObject::GetSmartParameters($this->modelName));

        $content['pages_tpl'] = Utils::ListDirectory(Model::$conf->menu_tpl);

        $with_editor = [
            'copyrights',
            'banner_header',
            'index_description',
            'index_header',
            'category_header',
            'order_header',
            'clients_header',
            'clients_description',
            'advantages_header',
            'steps_header',
            'contacts_form_header',
            'contacts_form_description1',
            'contacts_form_description2',
            'order_popup_header',
            'order_popup_description',
        ];

        if (in_array($object->name, $with_editor)) {
            $content['use_editor'] = 1;
        }

        return $content;
    }
}