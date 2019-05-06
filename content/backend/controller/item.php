<?php

class controller_item extends crud_controller_tree
{
    public function __construct()
    {
        $this->modelName = 'Item';
        $this->controllerName = 'item';

        // Addons
        $this->addons = new stdClass();
        $this->addons->images = true;
        $this->addons->similar_items = true;
    }

    public function items($params)
    {
        $content = [];

        $checker = new Checker('Parameters');
        list($params) = $checker->Get($params);

        $whereDefault = 'cmf_Item_default_where';

        if ($params->where->category_ID) {
            $currentCategory = $params->where->category_ID->Val();
        } else if (is_array(Model::$session->$whereDefault) and (int) Model::$session->{$whereDefault}["category_ID"] > 0) {
            $currentCategory = (int) Model::$session->{$whereDefault}["category_ID"];
        } else {
            $currentCategory = 1;
        }

        $currentCategory = ((int) $currentCategory > 0) ? (int) $currentCategory : 1;

        if ($params->where->category_ID and $params->where->category_ID->Val() != '_NULL') {
            $params->in->category_ID = "(" . Category::GetChildrenAs($params->where->category_ID->Val(), "String") . ")";
            if ((int) $params->where->category_ID->Val() == 1) {
                $params->where->category_ID = "_NULL";
                $params->in->category_ID = "(_NULL)";
            }
        } else if ((isset(Model::$session->$whereDefault) and Model::$session->$whereDefault["category_ID"] == 1) or ((int) isset(Model::$session->$whereDefault) and Model::$session->$whereDefault["category_ID"] === 0)) {
            if (! $params instanceof Parameters)
                $params = new Parameters();
            $params->where->category_ID = "_NULL";
            $params->in->category_ID = "(_NULL)";
        }


        if (! $params instanceof Parameters)
            $params = new Parameters();


        $params->smart = 1;

        eval('$content = ' . $this->modelName . '::GetList($this->modelName, $params);');

        $content = array_merge($content, DatabaseObject::GetRelationsList($this->modelName));

        $checker = new Checker('Category');
        foreach ($content['items'] as $key => $value) {
            list($category) = $checker->Get($value['category_ID']);
            $content['items'][$key]['category'] = ($category ? $category->title : "");
            if ($category and ! isset($content["select"]["where"]["in"]["category_ID"])) {
                $parentcategory = Category::GetMainCategory($category->ID);
                if ($parentcategory) $content['items'][$key]['category'] = ($parentcategory->title !== $content['items'][$key]['category'] ? $parentcategory->title . " - " : '') . $content['items'][$key]['category'];
            }

            $content['items'][$key]['person'] = Person::getName($value['person_ID']);
        }

        if (isset($content["select"]["where"]["in"]["category_ID"]) and $content["select"]["where"]["in"]["category_ID"] !== "") {
            $content["selected_category"] = intval(substr(array_shift(explode(",", $content["select"]["where"]["in"]["category_ID"])), 1));
        } else {
            $content["selected_category"] = "_NULL";
        }

        $content["select"]["where"]["in"]["category_ID"] = $currentCategory;

        if ($params->order_mode->Val())
            $content["order_mode"] = 1;

        Controller::AssignActions($this, $content);

        return $content;
    }

    public function edit(&$object)
    {
        $content = [];

        $content = parent::edit($object);

        $content['persons'] = Person::getAll();

        return $content;
    }

    public function save(&$object, $params)
    {
        $content = [];

        if (is_array($params['results'])) {
            foreach ($params['results'] as $key => $value) {
                if ($value['text'] == '')
                    unset($params['results'][$key]);
            }
        }
        if (is_array($params['requirements'])) {
            foreach ($params['requirements'] as $key => $value) {
                if ($value['text'] == '')
                    unset($params['requirements'][$key]);
            }
        }
        if (is_array($params['auditory'])) {
            foreach ($params['auditory'] as $key => $value) {
                if ($value['text'] == '')
                    unset($params['auditory'][$key]);
            }
        }
        if (is_array($params['plan'])) {
            foreach ($params['plan'] as $key => $value) {
                if ($value['text'] == '')
                    unset($params['plan'][$key]);
            }
        }

        $content = parent::save($object, $params);

        return $content;
    }

    public function fast_edit_open($object, $field)
    {
        $content = [];

        if (in_array($field, []))
            $multilang = true;
        else
            $multilang = false;

        $table = ($multilang) ? 'Item_ml' : 'Item';

        $type = Item::GetColumnType("Item", $field);

        if ($type == "VARCHAR" or $type == "TEXT" or $type == "DOUBLE") {
            $content["type"] = $type = "String";
        } else if ($type == "INT") {
            $content["type"] = $type = "Int";
        }
        if ($type == "BOOL") {
            $content["type"] = $type = "Bool";
        }

        if ($field == "category_ID") {
            $content["type"] = $type = "Select";
            $value = Item::GetObjectColumn($table, $object, $field, ($multilang ? Application::$language->ID : false));
            $content["selected"] = $value;
            $categories = Category::GetCategoriesTree();
            foreach ($categories["items"] as $value) {
                $content["items"][] = ["value" => $value["ID"], "title" => $value["padding"] . $value["title"]];
            }
        } else {
            $value = Item::GetObjectColumn($table, $object, $field, ($multilang ? Application::$language->ID : false));
            if ($value !== false) {
                $content["value"] = $value;
            }
        }

        $content["value"] = htmlspecialchars($content["value"]);

        $content["object"] = $object;
        $content["fieldname"] = $field;

        return $content;
    }

    public function fast_edit_save($object, $field, $value)
    {
        $content = [];

        $type = Item::GetColumnType("Item", $field);

        $content["type"] = "Default";

        if ($type == "BOOL") {
            $value = ($value == "true") ? 1 : 0;
            $content["type"] = "Bool";
        }
        if ($field == "block") {
            $value = ($value == "true") ? 0 : 1;
            $content["type"] = "Bool";
        }

        if ($field == "expired") {
            $value = ($value == 1) ? 0 : 1;
            $content["type"] = "Bool";
        }

        if (in_array($field, []))
            $multilang = true;
        else
            $multilang = false;

        $table = ($multilang) ? 'Item_ml' : 'Item';

        Item::UpdateObjectColumn($table, $object, $field, $value, ($multilang ? Application::$language->ID : false));

        $value = Item::GetObjectColumn($table, $object, $field, ($multilang ? Application::$language->ID : false));

        if ($field == "category_ID") {
            $checker = new Checker("Category");
            list($cat) = $checker->Get($value);
            if ($cat) {
                $content["value"] = $cat->title;
            }

            if (! $content["value"]) {
                $content["value"] = 1;
            }
        } else if ($field == "price") {
            if ($value !== false) {
                $content["value"] = $value;
                $content["currency"] = Item::GetObjectColumn($table, $object, "currency");
            }
        } else {
            if ($value !== false) {
                $content["value"] = $value;
            }
        }

        $content["object"] = $object;
        $content["fieldname"] = $field;

        return $content;
    }

    public function sort_items($params)
    {
        if (! isset($params['where']['category_ID'])) {
            $params['where']['category_ID'] = 1;
            $params['in']['category_ID'] = "(" . Category::GetChildrenAs(1, "String") . ")";
        }

        $params['likeA']['article'] = '';

        //	$params['onPage'] = 100;

        $content = $this->items($params, 'grid');

        $params2 = new Parameters();

        $content['category_ID_block'] = Category::GetList('Category', $params2);

        $content['selected_category'] = $params['where']['category_ID'];

        return $content;
    }

    public function select_item($category_ID, $object)
    {
        $content = [];

        $checker = new Checker("Category");
        list($category) = $checker->Get($category_ID);

        if ($category) {
            $content["path"] = $category->GetPathFilter();
            $content["parent_ID"] = $category->ID;
            if ($category->last_level == 1) {
                $params = new Parameters();
                $params->onPage = 100000;
                $params->where->category_ID = $category->ID;
                $content["products"] = Item::GetList("Item", $params);
            }
        }

        $content["object_ID"] = ((int) $object) ? (int) $object : null;

        return $content;
    }

    public function select_similar_item($parent_ID, $object)
    {
        $content = [];

        $checker = new Checker("Category");
        list($model) = $checker->Get($parent_ID);
        $content['ID'] = $object;

        $content['path'] = $model->GetPathFilter();
        $content['parent_ID'] = $parent_ID;
        if ($model->last_level == 1) {
            $params = new Parameters();
            $params->onPage = 100000;
            $params->where->category_ID = $parent_ID;
            $params->where->not->ID = $object;
            $content['products'] = Item::GetList('Item', $params);
        }

        return $content;
    }

    public function similar_items($object)
    {
        $content = [];

        $checker = new Checker($this->modelName);
        list($object) = $checker->Get($object);

        $content = $object->GetInfoUnEscape();
        $content['select_similar_item'] = $this->controllerName . '/select_similar_item/1/' . $object->ID;
        $content['items'] = $object->Similar();
        $content['count'] = count($content['items']);

        $content["object"] = $object->ID;

        return $content;
    }

    public function save_similar($object, $params)
    {
        $content = [];

        $add = ($add == "") ? 'save' : $add;

        $checker = new Checker($this->modelName);
        list($object) = $checker->Get($object);

        if ($object) {
            $object->SimilarSave($params);
            $content['mess'] = lang('Сохранение прошло успешно');
        }

        return array_merge(["similar_items" => $this->similar_items($object->ID)], $content);
    }

    public function delete_similar($object, $main_object)
    {
        $content = [];

        if ($object) {
            Model::$db->Query('DELETE FROM `Similar` WHERE main_object = ' . $main_object . ' AND object = ' . $object);
            $content['mess'] = lang('Удаление прошло успешно');
        }

        return array_merge(["similar_items" => $this->similar_items($main_object)], $content);
    }

    public function repost($object)
    {
        $item = Item::find($object);

        if ($item and $item->real()) {
            $item->repost();
            $item->publish();

            $content['mess'] = lang("Запись переопубликована");
        }

        return $this->edit($item->ID);
    }
}