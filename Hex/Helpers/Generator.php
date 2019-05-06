<?php

namespace Hex\Helpers;

use KernelSettings as Settings;
use Database;

class Generator
{
	public static function createModel($name, $controller = null, $force = false)
	{
		$path = Settings::get('modelPath') . '/' . $name . '.php';

		if ($force or ! file_exists($path)) {
			$content = self::generateModel(
				$name, 
				array(
					'controller' => $controller
				)
			);

			file_put_contents($path, $content);
		}
	}

	public static function generateModel($name, $options = array())
	{
		if (!isset($options['controller']) or $options['controller'] == '')
			$options['controller'] = self::getControllerName($name);

		return "<?php

use Hex\App\Entity;
		
class {$name} extends Entity
{
    protected static \$findByFields = ['ID'];
    public static \$poolName = '{$options[controller]}';
    protected static \$entityName = '{$name}';
    protected static \$tableName = '{$name}';
    protected \$controllerName = '{$options[controller]}';
}";
	}

	public static function createController($controller, $model = null, $force = false)
	{
		$pathController = Settings::get('controllerBackendPath') . '/' . $controller . '.php';
		$pathView = Settings::get('viewBackendPath') . '/' . $controller;

		if ($force or ! file_exists($pathController)) {
			$content = self::generateController(
				$controller,
				array(
					'model' => $model
				)
			);

			file_put_contents($pathController, $content);
		}

		if (! is_dir($pathView)) {
			mkdir($pathView);
		}
	}

	public static function generateController($controller, $options = array())
	{
		if (!isset($options['model']) or $options['model'] == '')
			$options['model'] = self::getModelName($controller);

		return "<?php

class controller_{$controller} extends crud_controller_tree
{
	public function __construct()
	{
		\$this->modelName = '{$options[model]}';
        \$this->controllerName = '{$controller}';
	}
}";
	}

	public static function createTable($name, $timepstamps = true, $utils = true, $multilang = false)
	{
		$fields = array();

		if ($timepstamps) {
			$fields[] = 'created DATETIME NULL';
			$fields[] = 'creator_ID INT NULL';
			$fields[] = 'updated DATETIME NULL';
			$fields[] = 'updater_ID INT NULL';
		}
		if ($utils) {
			$fields[] = 'block TINYINT(1) NOT NULL';
			$fields[] = 'pos INT NOT NULL';
		}

		self::createSQLTable($name, $fields, $multilang);	
	}

	public static function ifTableExists($table)
	{
		return (bool) Database::Value("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '" . Database::GetDatabaseInfo()['db_name'] . "' AND table_name = '" . $table . "' LIMIT 1");
	}

	public static function createSQLTable($table, $fields = array(), $multilang = false)
	{
		$fieldsArray = array('ID INT PRIMARY KEY AUTO_INCREMENT');

		if (count($fields)) {
			foreach ($fields as $field) {
				$fieldsArray[] = $field;
			}
		}

		if (! self::ifTableExists($table)) {
			Database::Query('CREATE TABLE `' . $table . '` (' . implode(', ', $fieldsArray) . ')');
		}

		$langTable = $table . '_ml';

		if ($multilang and ! self::ifTableExists($langTable)) {
			Database::Query('CREATE TABLE `' . $langTable . '` (ID INT NOT NULL, lang_ID INT NOT NULL)');

			Database::Query('ALTER TABLE `' . $langTable . '` ADD INDEX (`ID`)');
			//Database::Query('ALTER TABLE `' . $langTable . '` ADD FOREIGN KEY (`ID`) REFERENCES `' . $tableName . '` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE');
			Database::Query('ALTER TABLE `' . $langTable . '` ADD UNIQUE ( `ID` , `lang_ID` )');
			//Database::Query('ALTER TABLE `' . $langTable . '` ADD FOREIGN KEY (`lang_ID`) REFERENCES `Language` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE');
		}
	}






	public static function getControllerName($model)
	{
		preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $model, $matches);

		$return = $matches[0];
		foreach ($return as &$match)
			$match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
	
		return implode('_', $return);
	}

	public static function getModelName($controller)
	{
		$str = str_replace(' ', '', ucwords(str_replace('-', ' ', $controller)));

		$str[0] = strtolower($str[0]);

		return $str;
	}
}
