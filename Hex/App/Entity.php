<?php

namespace Hex\App;

use DatabaseObject;
use Database;
use Application;
use Parameters;
use Hex\Traits\Copyable;

class Entity extends DatabaseObject
{
    use Copyable;
    const NAMEID_FIELD = 'name';
    const STATUS_FIELD = 'block';
    const STATUS_HIDDEN = 1;
    const STATUS_PUBLISHED = 0;
    protected static $findByFields = ['ID'];
    public static $poolName = false;
    protected static $entityName;
    protected static $tableName;
    protected $controllerName;

    public function __construct($data = true)
    {
        // @ftodo Fix for deprecated models
        $this->modelName = static::getModelName();
        $this->controllerName = static::getControllerName();
        //

        if ($data === true) // Create empty
            return $this;

        if ($data == false or $data === null)
            return false;

        if (is_array($data))
            return $this->setData($data);
        else if (is_int($data))
            $data = self::findAsArray($this->modelName, $data, ['ID']);
        else
            $data = self::findAsArray($this->modelName, $data);

        if ($data == false)
            return false;

        return $this->setData($data);
    }

    public function __get($name)
    {
        return parent::__get($name);
    }

    public function real()
    {
        return (is_array($this->info) ? (bool) count($this->info) : false);
    }

    public static function getTableName()
    {
        return static::$tableName ?? static::$entityName;
    }

    public function getModelName()
    {
        if ($this->modelName !== null)
            return $this->modelName;

        return static::$entityName ?? static::class;
    }

    public function getControllerName()
    {
        if ($this->controllerName !== null)
            return $this->controllerName;

        return strtolower(static::class);
    }

    public function getId()
    {
        return $this->ID;
    }

    public static function exists($value, $field = false)
    {
        if (! $field) {
            if (is_int($value))
                $field = 'ID';
            else
                $field = self::NAMEID_FIELD;
        }

        $model = static::class;

        return (bool) Database::value("SELECT COUNT(*) FROM `" . $model . "` WHERE `" . Database::escape($field) . "` = '" . Database::escape($value) . "'");
    }

    public function isHidden()
    {
        return ($this->{self::STATUS_FIELD} == self::STATUS_HIDDEN);
    }

    public function published()
    {
        return ($this->{self::STATUS_FIELD} == self::STATUS_PUBLISHED);
    }

    public function publish()
    {
        return Database::query("UPDATE `" . $this->getModelName() . "` SET `block` = " . self::STATUS_PUBLISHED . " WHERE `ID` = '" . $this->ID . "'");
    }

    public static function findAsArray($model, $value, $fields = false)
    {
        if (static::$poolName != false and Application::$section == 'frontend') {
            $pool = Pools::find(static::$poolName);

            if ($pool->has($value)) {
                return $pool->get($value);
            }
        }

        $query = [];

        $fields = ($fields !== false) ? $fields : static::$findByFields;

        foreach ($fields as $field) {
            $query[] = ($field == 'ID' ? 'i.' : '') . '`' . $field . '` = \'' . $value . '\'';
        }

        if (count($query)) {
            if (self::IfMultilangTable($model)) {
                $data = Database::value("SELECT i.*, i_ml.* FROM `" . $model . "` i LEFT JOIN `" . $model . "_ml` i_ml ON i.ID = i_ml.ID WHERE i_ml.lang_ID = '" . Application::$language->ID . "' AND ( " . implode(' OR ', $query) . ")", true);
            } else {
                $data = Database::value("SELECT * FROM `" . $model . "` i WHERE " . implode(' OR ', $query), true);
            }
        }

        if (static::$poolName != false and Application::$section == 'frontend') {
            $pool->set($value, $data);
        }

        $data= static::modifyData($data);

        return (isset($data)) ? $data : false;
    }

    public static function find($value, $fields = false)
    {
        $model = static::class;

        $object = new $model();

        $data = self::findAsArray($object->getModelName(), $value, $fields);

        $data = static::modifyData($data);

        $object->setData($data);

        return $object;
    }

    /**
     * @param mixed $value
     * @param bool $fields
     * @return static|null
     */
    public static function one($value, $fields = false)
    {
        $object = self::find($value, $fields);

        return ($object and $object->real()) ? $object : null;
    }

    public function setData($data)
    {
        $this->info = $data;

        return $this;
    }

    public static function getAllObjects($onlyPublished = true)
    {
        $items = [];

        $params = new Parameters();
        $params->order = "pos";
        $params->onPage = 100000;

        if ($onlyPublished)
            $params->ne->block = 1;

        $class = static::class;

        $arr = self::getList(static::$entityName, $params)['items'];

        foreach ($arr as $item) {
            $items[] = new $class($item);
        }

        return $items;
    }

    public static function getAll($onlyPublished = true, $params = [], $withSelectArray = false)
    {
        $items = [];

        $params = new Parameters($params);

        if (! $params->onPage->Val())
            $params->onPage = 10000;

        if (! $params->order->Val())
            $params->order = "pos";

        if ($onlyPublished)
            $params->ne->block = 1;

        $result = self::getList(static::$entityName, $params);

        foreach ($result['items'] as &$item) {
            $item = static::modifyData($item);
        }

        return $withSelectArray ? $result : $result['items'];
    }

    /**
     *  Возвращает массив с ключом и значением без выбранного элемента
     */
    public static function getListForSelect($ID = null, $keyField = 'ID', $titleField = 'title', $onlyPublished = true)
    {
        $ID = isset($ID) ? $ID : 0;

        $params = new Parameters();
        $params->order = "pos";

        if ($onlyPublished)
            $params->ne->block = 1;

        if ($ID)
            $params->where->not->ID = $ID;

        $items = self::getList(static::$entityName, $params)['items'];

        $newItems = $items;
        $items = [];

        foreach ($newItems as $item) {
            $items[$item[$keyField]] = $item[$titleField];
        }

        return ['' => lang('-- Выберите значение')] + $items;
    }

    public function toArray()
    {
        $content = [];

        return (array) $this->info;
    }

    /**
     * @param $data
     * @return array|static
     */
    public static function createNew($data = [])
    {
        $params = new Parameters($data);
        $errors = new Parameters();

        return self::create(static::$entityName, 'create', $params, $errors, true, static::class);
    }

    /**
     * @param $data
     * @return array|static
     */
    public function update($data = [])
    {
        $params = new Parameters($data);
        $errors = new Parameters();

        return $this->edit(static::$entityName, 'edit', $params, $errors);
    }

    public static function updateField($ID, $field, $value = '', $touch = false)
    {
        if ($touch) {
            $q = ', updated = \'' . date("Y-m-d H:i:s") . '\'';
        } else {
            $q = '';
        }

        $multilang = self::isMultilangColumn(static::getTableName(), $field);

        return Database::query('UPDATE `' . static::getTableName() . ($multilang ? '_ml' : '') . '` SET `' . $field . '` = \'' . Database::Escape($value) . '\' ' .  $q . ' WHERE `ID` = ' . (int) $ID . ($multilang ? ' AND lang_ID = ' . Application::$language->ID : ''));
    }

    public function remove()
    {
        return Database::query('DELETE FROM `' . static::getTableName() . '` WHERE `ID` = ' . (int) $this->ID);
    }

    public static function modifyData($data)
    {
        return $data;
    }
}