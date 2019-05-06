<?php

use Hex\App\Entity;

class Infoblock extends Entity
{
    protected static $findByFields = ['ID', 'name'];
    public static $poolName = 'infoblocks';
    protected static $entityName = 'Infoblock';
    protected static $tableName = 'Infoblock';
    protected $controllerName = 'infoblock';

    public static function getText($name)
    {
        return ($infoblock = self::one($name)) ? $infoblock->description : false;
    }
}

