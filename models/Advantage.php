<?php

use Hex\App\Entity;
		
class Advantage extends Entity
{
    protected static $findByFields = ['ID'];
    public static $poolName = 'advantage';
    protected static $entityName = 'Advantage';
    protected static $tableName = 'Advantage';
    protected $controllerName = 'advantage';
}