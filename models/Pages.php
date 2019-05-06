<?php

use Hex\App\Entity;

class Pages extends Entity
{
	protected static $findByFields = array('ID', self::NAMEID_FIELD);

    public static $poolName = 'pages';
    protected static $entityName = 'Pages';
    protected static $tableName = 'Pages';
    protected $controllerName = 'pages';

    public function path()
    {
        return '/' . Application::$language->name . '/' . $this->name;
    }

    public static function pathStatic($data)
    {
        $page = is_array($data) ? $data['name'] : $data;

        return '/' . Application::$language->name . '/' . $page;
    }

    public function custom()
    {
        return $this->tpl == 'page';
    }

	public static function GetTemplates(){

		$pages = array();

		$info = array(
			"index" => array(
				"title" => lang("Главная")
			),
			"products" => array(
				"title" => lang("Каталог товаров")
			),
			"product" => array(
				"title" => lang("Страница описания товара")
			),
			"contacts" => array(
				"title" => lang("Контакты")
			),
			"about" => array(
				"title" => lang("О нас")
			),
			"contactus" => array(
				"title" => lang("Связаться с нами")
			),
			"shops" => array(
				"title" => lang("Магазины")
			),
			"partners" => array(
				"title" => lang("Партнёры")
			),
			"search" => array(
				"title" => lang("Поиск"),
			),
			"page" => array(
				"title" => lang("Текстовая страница"),
				"text" => 1
			),
			"404" => array(
				"title" => lang("Ошибка 404"),
				"error" => 1
			),
		);

		$dirlist = Utils::ListDirectory(Model::$conf->pagesPath);

		foreach($dirlist as $value){
			if(isset($info[$value["name"]])){
				$pages[] = array_merge(array("tpl" => $value["name"]), $info[$value["name"]]);
			}else{
				$pages[] = array("title" => $value["name"], "tpl" => $value["name"]);
			}
		}

		Utils::USort($pages, "title", "string");

		return $pages;

	}

	public static function GetPageTpl($name, $check = false){

		if(trim($name) == "" or Application::$section == "backend") return "index";

		if($name == "page") return "page";

		$checker = new Checker("Pages");
		list($page) = $checker->Get($name);

		if($page and (!$check or (int)$page->block == 0)){
			return $page->tpl;
		}else{
			return "404";
		}

	}

	public static function GetPage($name){

		if(trim($name) == "" or Application::$section == "backend") return "index";

		//if($name == "page") return "page";

		$checker = new Checker("Pages");
		list($page) = $checker->Get($name);

		if($page){
			return $page;
		}else{
			return false;
		}

	}

	public static function pagesMap()
	{
		return array (
            'index' => 1,
            'gallery' => 4,
            'gallery-category' => 6,
            'contacts' => 5,
		);
	}

	public static function getPageByName($name)
	{
		$map = self::pagesMap();

		$ID = (isset($map[$name])) ? $map[$name] : false;

		if ($ID) {
			if (isset(Application::$pages[$ID]))
				return Application::$pages[$ID];

			$page = Pages::find($ID);

			if ($page and $page->real()) {
				Application::$pages[$ID] = $page;

				return $page;
			}

			return false;
		}
	}

	public static function getUrlName($name)
	{
		$map = self::pagesMap();

        $ID = (isset($map[$name])) ? $map[$name] : false;

		if ($ID) {
			if (isset(Application::$pages[$ID]))
                return Application::$pages[$ID]->name;

			$page = Pages::find($ID);

			if ($page and $page->real()) {
				Application::$pages[$ID] = $page;

				return $page->name;
			}

            return false;
		}
	}
}