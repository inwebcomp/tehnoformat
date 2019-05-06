<?php

namespace Hex;

use App\Providers\AppServiceProvider;
use Application;
use Bramus\Router\Router;
use Hex\App\Event;

class App
{
    protected static $router;

    public function __construct()
    {
        $this->setRouter(new Router());
    }

    /**
     * @param mixed $router
     */
    public function setRouter($router)
    {
        self::$router = $router;
    }

    /**
     * @return Router
     */
    public static function getRouter()
    {
        return self::$router;
    }

//    private $providers = [
//        AppServiceProvider::class
//    ];
//
//    public function __construct()
//    {
//        foreach ($this->providers as $provider) {
//            (new $provider())->boot();
//        }
//    }

    /**
     * Get action path
     */
    public static function action($controller, $action, $data = [])
    {
        $section = Application::$section;

        $path = [];
        $path[] = 'ajax';
        $path[] = $section;
        $path[] = Application::$language->name;
        $path[] = $controller;
        $path[] = $action;

        foreach ($data as $value) {
            $path[] = $value;
        }

        $path = implode('/', $path);

        return '/' . $path;
    }

    /**
     * @param string|Event $event
     */
    public static function event($event)
    {
        if ($event instanceof Event)
            $eventName = get_class($event);
        else
            $eventName = $event;

        if (isset(AppServiceProvider::$events[$eventName])) {
            (new AppServiceProvider::$events[$eventName]($event))->handle();
        }
    }
}
