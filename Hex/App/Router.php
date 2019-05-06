<?php

namespace Hex\App;

use Hex\Abstracts\Singleton;
use Application;

/**
 * Маршрутизации данных
 *
 * Определяет какой раздел показать, какой контроллер и метод вызвать.
 * Работает с данными в ссылке.
 *
 * Class Router
 */
class Router extends Singleton
{	
	protected static $instance;

	/**
     * Текущий адрес страницы, разбитый на массив
	 *
	 * Содержит данные из ссылки после названия домена
     *
     * @var array
     */
    public static $url;

	/**
     * Список доступных разделов
	 *
	 * Заполняется в конструкторе класса, функцией getSectionsInfo(); 
     *
     * @var array
     */
    private $sections;

	/**
     * Главный роутер сайта
     *
     * @var array
     */
    private static $router;

	/**
     * Путь
     *
     * @var array
     */
    private static $route;

	/**
     * Параметры запроса
     *
     * @var array
     */
    private static $queryParams = array();

	/**
     * Путь по умолчанию
     *
     * @var string
     */
    private static $defaultRoute = 'index';

	/**
     * Путь ошибки
     *
     * @var string
     */
    private static $errorRoute = '404';



	/**
     * @return \Hex\App\Router
     */
	public function run()
    {
		// Получение данных из ссылки
		self::$url = self::parseUrl(Application::$request->getPathInfo());

		// Определение класса роутинга
		self::$router = $this->getRouterClass();

		// Установка страницы с ошибкой 404
		// $this->define404(self::$router);

		// Определение маршрутов приложения
		// $this->defineAppRoutes(self::$router);

		$this->defineRoutes(self::$router);

		$this->runRouter();

		return self::$route;
    }

	/**
     * Получение данных из ссылки
     *
     * @param string $url
     * @return array
     */
    public static function parseUrl($url)
    {
		$url = explode('/', trim(urldecode($url), ' /'));

		return $url;
	}
	
	/**
     * Получение контроллера и действия из пути
	 *
     * @return array Первый элемент - контроллер, второй - действие.
     */
    public static function parseRoute($route)
    { 
		$elements = explode('/', trim($route, ' /'));

		if (count($elements) == 2) {
			$module = false;
			$controller = $elements[0];
			$action = $elements[1];
		} elseif (count($elements) == 3) {
			$module = $elements[0];
			$controller = $elements[1];
			$action = $elements[2];
		} else {
			throw new Exception("Route contain ".count($elements)." parts, instead of 2 or 3.");
		}

		return [$controller, $action, $module];
	}

	/**
     * Получение класса роутинга
     */
    private static function getRouterClass()
    {
		return new \Bramus\Router\Router();
	}

	/**
     * Запуск роутера
	 *
     * @return Router
     */
    public function runRouter()
    {
		return self::$router->run();
	}

	/**
     * Установка страницы с ошибкой 404
	 *
     * @return void
     */
    private static function define404($router)
    { 
		$router->set404(function() {
			Hex::$app->getResponse()->setStatusCode(404);
			Hex::$app->route(self::$errorRoute);
		});
	}

	/**
     * Определение маршрутов приложения
	 *
     * @return void
	 * @todo Написать действие выполнения метода
     */
    private static function defineAppRoutes($router)
    {
		//
	}

	/**
     * Определение пользовательских маршрутов
	 *
     * @return void
     */
    private static function defineRoutes($router)
    {
		$router->get('/([a-z0-9_-]+)', function($page) {
			Hex::$app->getDb();
			
			echo $page;
			exit();
			self::$route = array(
				$page
			);
		});
	}	
}