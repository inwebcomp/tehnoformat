<?php

use Hex\App\Entity;
		
class Step extends Entity
{
    protected static $findByFields = ['ID'];
    public static $poolName = 'step';
    protected static $entityName = 'Step';
    protected static $tableName = 'Step';
    protected $controllerName = 'step';
}