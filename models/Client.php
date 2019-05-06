<?php

use Hex\App\Entity;
		
class Client extends Entity
{
    protected static $findByFields = ['ID'];
    public static $poolName = 'client';
    protected static $entityName = 'Client';
    protected static $tableName = 'Client';
    protected $controllerName = 'client';
}