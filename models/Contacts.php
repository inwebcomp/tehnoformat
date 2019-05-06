<?php

use Hex\App\Entity;
		
class Contacts extends Entity
{
    protected static $findByFields = ['ID'];
    public static $poolName = 'contacts';
    protected static $entityName = 'Contacts';
    protected static $tableName = 'Contacts';
    protected $controllerName = 'contacts';

    public static function modifyData($data)
    {
        if (! $data)
            return $data;

        $data['phone_clean'] = str_replace(['(', ')', ' ', '-'], '', $data['phone']);
        $data['phone2_clean'] = str_replace(['(', ')', ' ', '-'], '', $data['phone2']);

        return $data;
    }
}