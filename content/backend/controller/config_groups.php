<?php
class controller_config_groups extends crud_controller_tree
{
	public function __construct()
	{
		$this->modelName = 'Configgroups';
		$this->controllerName = 'config_groups';
	}

	public function clear_cache()
	{
		$dir = Model::$conf->cacheContentPath.'/backend';
		$dir2 = Model::$conf->cacheContentPath.'/frontend';

		Utils::ClearDir($dir);
		Utils::ClearDir($dir2);
		
		if (file_exists(Model::$conf->cachePath . '/categories.ru.cache'))
			unlink(Model::$conf->cachePath . '/categories.ru.cache');
		
		if (file_exists(Model::$conf->cachePath . '/categories.ro.cache'))
			unlink(Model::$conf->cachePath . '/categories.ro.cache');

		$content = array("mess" => lang("Кэш успешно обновлён"));

        return array_merge($this->items(array()), $content);
    }
}