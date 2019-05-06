<?php

use Hex\App;
use Hex\App\Auth;
use Hex\App\Request;
use Hex\App\Router;

final class Application
{
    public static $language;
    public static $lang_config;
    public static $oc;
    public static $noLangRequest;
    public static $section;
    public static $page;
    public static $page_template;
    public static $returnType;
    public static $routing;
    public static $rates;
    public static $engineInfo;
    public static $params;
    public static $global = false;
    public static $controllerName;
    public static $actionName;
    public static $pageObject;
    public static $mainObjectData;
    public static $alias;
    public static $security;
    public static $request;
    public static $pages;

    public static $mobile = false;

    public static function Initialize($siteParams)
    {
        global $time_spent;

        $params = [
            'multilang' => true,
            'count_visits' => true,
            'rates' => false,
            'use_filters' => false,
            'users' => true,
            'followers' => false,
            'cart' => false,
            'shares' => false,
            'orders' => false,
            'parameters' => false
        ];

        // Debug
        Hex\App\Debug::getInstance();

        $params = Application::$params = array_merge($params, $siteParams);

        self::$request = new Request();

        $pos = strpos($_SERVER['REQUEST_URI'], '?');
        if ($pos !== false)
            $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 0, $pos);

        if (isset($_SERVER['REQUEST_URI'])) {
            $queryStringArr = preg_split("/(\/|\?)/", preg_replace('#^/#', '', $_SERVER['REQUEST_URI']));
        }
        
        Database::$instance = Database::DataBaseConnect();

        //Controller::SetOverrides();

        Model::Initialize();

        date_default_timezone_set((trim(Model::$conf->default_timezone) !== '' ? Model::$conf->default_timezone : 'Europe/Chisinau'));
      
        self::$engineInfo = KernelSettings::GetEngineInfo();
        foreach (self::$engineInfo as $key => $value) {
            define('ENGINE_' . strtoupper($key), $value);
        }



        if ($queryStringArr[0] == 'backend') {
            Session::CreateSession();

//        KernelSettings::SetImageSettings();

            \Hex\App\Auth::checkAuthTime();

            unset($queryStringArr[0]);

            if (Auth::logined()) {
                if ((Model::$user->type !== 'admin' and Model::$user->type !== 'moder' and Model::$user->type !== 'developer')) {
                    exit();
                }
            }
        }

        // Memory Cache
        //Cache::getInstance();
        // END

        Application::$section = self::GetSection();

        Application::$returnType = array_shift($queryStringArr);

        switch (Application::$returnType) {
            case 'ajax': case 'json': case 'html': case 'html-block': break;
            default:
                array_unshift($queryStringArr, Application::$returnType);
                Application::$returnType = 'html';
        }

        self::$security = Hex\App\Security::getInstance();

        /*if(Model::$user->login !== 'Saneock' and Application::$section !== 'backend' and Application::$returnType == "html"){
            header("HTTP/1.1 503 Service Unavailable");
            exit('Сайт временно недоступен. Мы проводим технические работы');
        }*/

        if ($params['count_visits'] and Application::$section !== 'backend' and Application::$returnType == 'html') {
            Visits::AddNew();
        }

        try {
            $language = array_shift($queryStringArr);
            Application::$language = new Language($language);
        } catch (Exception $ex) {
            array_unshift($queryStringArr, $language);
            Application::$language = new Language(Model::$conf->default_language);
        }

        if (Application::$language->name == 'ru') {
            setlocale(LC_ALL, 'ru_RU.UTF_8');
        } else {
            setlocale(LC_ALL, 'ro_RO.UTF_8');
        }

        // Cron tasks
        $tasks = \Hex\App\Tasks::getInstance();
        if (isset($queryStringArr[0]) and $task = $tasks->find($queryStringArr[0])) {
            $task->execute();
            exit(1);
        }
        // ---------------


        if (Application::$section == 'frontend' and Application::$returnType == 'html') {
//            array_unshift($queryStringArr, $language);

            $queryStringArr = Application::RewriteURI($queryStringArr);
//            array_shift($queryStringArr);

//
//            if (! Auth::logined()) {
//                include 'temp/index.html';
//                exit();
//            }
        }

        KernelSettings::GetSettings();

        if ($params['rates']) {
            Application::$rates = Rates::GetTodayRates();
        }

        self::$noLangRequest = implode('/', $queryStringArr);

        $content['database'] = Database::GetDatabaseInfo('config');

        // Console commands
        // $application = new \Symfony\Component\Console\Application();

        // // ... register commands
        // $application->addCommands(include(Model::$conf->documentroot . '/Console/register.php'));

        // $application->run();
        // ---------------


        $navParams = [];

        if (Application::$returnType == 'html') {
            try {
                $pageName = array_shift($queryStringArr);
                self::$page = ($pageName) ? $pageName : 'index';

                $pageViewName = Pages::GetPageTpl(self::$page, true);
                $pageView = new View((($pageViewName) ? $pageViewName : 'index'), 'page', Application::$section);

                Language::setLanguageLocales();

                self::$page_template = $pageView->name;
                $routingName = array_shift($queryStringArr);

                if ($pageViewName == '404') {
                    header('HTTP/1.1 404 Not Found');
                }

                header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
                header('Cache-Control: post-check=0, pre-check=0', false);
                header('Pragma: no-cache');

//                self::$pageObject = Pages::GetPage(self::$page);

                self::setLastModified(self::$pageObject);

                while (($navParams[] = array_shift($queryStringArr)) !== null);
                $routingName = ($routingName == '') ? 'index' : $routingName;
                try {
                    self::$routing = Routing::GetObjectByColumn('Routing', 'name', $routingName);
                    if (self::$routing) {
                        self::$routing->SetValues($navParams);
                    }
                } catch (Exception $ex) { /* Неправильный роутинг, просто пропускаем */
                }
            } catch (Exception $ex) {
                header('HTTP/1.1 404 Not Found');
                $pageView = new View('404', 'page', self::$section);
            }

            flush();

            $time_spent = microtime(true);

            try {
                print self::CompressOutput($pageView->Get(array_merge(['page' => $pageView->name], Application::GetGlobalParams())));
            } catch (Exception $ex) {
                print self::CompressOutput($ex->getMessage());
            }
        } elseif (Application::$returnType == 'ajax') {
            Application::$section = array_shift($queryStringArr);
            Application::$language = new Language(array_shift($queryStringArr));

            $controllerName = array_shift($queryStringArr);
            $actionName = array_shift($queryStringArr);
            $actionName = ($actionName) ? $actionName : 'index';

            Language::setLanguageLocales();

            while (($navParams[] = array_shift($queryStringArr)) !== null);

            $oc = (isset($_POST['oc'])) ? $_POST['oc'] : null;

            print self::CompressOutput(Application::GetAjaxData($controllerName, $actionName, $navParams, $oc));
        } elseif (Application::$returnType == 'html-block') {
            $controllerName = array_shift($queryStringArr);
            $actionName = array_shift($queryStringArr);
            $actionName = ($actionName) ? $actionName : 'index';

            while (($navParams[] = array_shift($queryStringArr)) !== null);

            $oc = (isset($_POST['oc'])) ? $_POST['oc'] : null;

            print self::CompressOutput(Application::GetViewData($controllerName, $actionName, $navParams, $oc));
        } elseif (Application::$returnType == 'json') {
            Application::$section = array_shift($queryStringArr);
            Application::$language = new Language(array_shift($queryStringArr));

            $controllerName = array_shift($queryStringArr);
            $actionName = array_shift($queryStringArr);
            $actionName = ($actionName) ? $actionName : 'index';

            Language::setLanguageLocales();

            while (($navParams[] = array_shift($queryStringArr)) !== null);

            print self::CompressOutput(Application::GetJsonData($controllerName, $actionName, $navParams));
        } elseif (Application::$returnType == 'xml') {
            $controllerName = array_shift($queryStringArr);
            $actionName = array_shift($queryStringArr);
            $actionName = ($actionName) ? $actionName : 'index';

            while (($navParams[] = array_shift($queryStringArr)) !== null);

            print self::CompressOutput(Application::GetXmlData($controllerName, $actionName, $navParams));
        }

//        Language::AddMissingWords();

        Model::$session->saveToDB();
    }

    public static function GetSection()
    {
        $page = explode('/', $_SERVER['REQUEST_URI']);

        if ($page[1] == 'backend') {
            $section = 'backend';
        } else {
            $section = 'frontend';
        }

        return $section;
    }

    public static function GetGlobalParams()
    {
        if (self::$global) {
            return self::$global;
        }

        $content = [];
        $conf = KernelSettings::GetInstance();

        $content['page_name'] = Application::$page;
        $content['page_name_uniq'] = Pages::GetPageTpl(Application::$page);
        $content['language'] = Application::$language->GetInfo();
        $content['language_name'] = Application::$language->name;
        $content['other_language'] = Application::$language->name == 'ru' ? 'ro' : 'ru';
        $content['language_url'] = (Application::$language->name == Model::$conf->default_language) ? '' : '/' . Application::$language->name;
        $content['default_language'] = Language::$default_language;
        $content['section'] = Application::$section;
        $content['host'] = $conf->host;
        $content['url'] = 'https://' . $conf->host . $_SERVER['REQUEST_URI'];
        $content['base_url'] = 'https://' . $conf->host;
        $content['noLangRequest'] = self::$noLangRequest;
        $content['root'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', Model::$conf->documentroot);
        $content['rand'] = rand();
        $content['total_queries'] = Database::$counter;
        $content['phone_clean'] = str_replace(['(', ')', ' ', '-'], '', Model::$conf->phone);

        $content['protocol'] = 'https';

        $content['engine'] = self::$engineInfo;

        $content['site_params'] = self::$params;

        if (self::$params['rates']) {
            $content['default_currency_info'] = Rates::$default_currency;
            $content['default_currency'] = Rates::$default_currency['name'];
        }

        if (self::$params['cart']) {
            $content['cart_count'] = Cart::GetCount();
        }

        $content['csrf'] = self::$security->getToken();
        $content['csrf_meta'] = self::$security->csrfMeta();
        $content['csrf_field'] = self::$security->csrfField();

        $currentUser = User::getCurrentUser();

        $content['user'] = $currentUser->GetInfo();

        if ($currentUser->NonAnonymous()) {
            $content['auth'] = 1;
        }

        if ($currentUser->type == 'admin' || $currentUser->type == 'developer') {
            $content['admin'] = 1;
        }

        if ($currentUser->type == 'developer') {
            $content['developer'] = 1;
        }

        if ($currentUser->type == 'moder') {
            $content['moderator'] = 1;
        }

        if (isset($_POST['oc'])) {
            $content['oc'] = $_POST['oc'];
        }

        $configtmp = Model::$conf->GetInfo();
        $config = [];

        foreach ($configtmp as $key => $value) {
            $config['config_' . $key] = $value;
        }

        $content = array_merge($content, $config);

        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            self::$mobile = true;
            $content['is_mobile'] = 1;
        }

//        if (Model::$conf->minify) {
//            self::compressAssets();
//        }

        // Additional params

        self::$global = $content;

        $content['canonical'] = self::SetCanonical();

        return $content;
    }

    public static function compressAssets()
    {
        $minifiedCssPath = Model::$conf->cssPath . '/styles.min.css';
        $minifiedJsPath = Model::$conf->jsPath . '/scripts.min.js';

        $styles = [
            'input-elements.css',
            'select-theme-light.css',
            'select-theme-black.css',
            'default.css',
            'jquery-ui.css',
            'animate.css',
            'owl.carousel.css',
            'owl.theme.css',
            'froala_style.min.css',
            'font-awesome.min.css'
        ];

        $js = [
            'jquery.js',
            'scripts.js',
            'functions.js',
            'select.js',
            'owl.carousel.min.js'
        ];

        $minifierCss = new \MatthiasMullie\Minify\CSS();

        foreach ($styles as $path) {
            $minifierCss->add(Model::$conf->cssPath . '/' . $path);
        }

        $minifierJs = new \MatthiasMullie\Minify\JS();

        foreach ($js as $path) {
            $minifierJs->add(Model::$conf->jsPath . '/' . $path);
        }

        $minifierCss->minify($minifiedCssPath);
        $minifierJs->minify($minifiedJsPath);
    }

    public static function CompressOutput($contents)
    {
        /*if (Model::$conf->compress)
        {
            $accept_encoding = 'none';
            if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
                $accept_encoding = $_SERVER['HTTP_ACCEPT_ENCODING'];

            $gzip = strstr($accept_encoding, 'gzip');
            $deflate = strstr($accept_encoding, 'deflate');
            $encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');

            if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera') && preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches))
            {
                $version = floatval($matches[1]);
                if (($version < 6) || ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1')))
                    $encoding = 'none';
            }

            if (isset($encoding) && $encoding != 'none')
            {
                $contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
                header ("Content-Encoding: " . $encoding);
                header ('Content-Length: ' . mb_strlen($contents));
            }
        }*/

        return $contents;
    }

    public static function GetJsonData($controllerName, $actionName, $navParams)
    {
        $controller = new Controller($controllerName, Application::$section);

        $content = Application::GetRawData($controller, $actionName, $navParams, $_POST);

        $content['oc'] = 'json';
        return json_encode($content);
    }

    public static function GetAjaxData($controllerName, $actionName, $navParams, $oc = null)
    {
        $controller = new Controller($controllerName, Application::$section);
        $view = new View($actionName, $controller->name, Application::$section);

        $content = Application::GetRawData($controller, $actionName, $navParams, $_POST, $oc);

        if (isset($content['tcmf_fatalError'])) {
            $view = new View('error', 'include');
        }

        if (! is_array($content)) {
            $content = [];
        }

        $content = array_merge($content, $view->GetWithScripts($content));
        return json_encode($content);
    }

    private static function GetRawData(Controller $controller, $actionName, $navParams, $source = [], $oc = null)
    {
        $actionParams = [];

        if ($controller->IsAction($actionName)) {
            $paramNames = $controller->$actionName->GetParamNames();
        } else {
            throw new Exception(lang('Обращение к несуществующему действию.') . '[action: ' . $actionName . ', controller: ' . $controller->name . ']');
        }

        for ($i = 0; $i < count($paramNames); $i++) {
            if (isset($navParams[$i]) && $navParams[$i] != '') {
                $actionParams[] = $navParams[$i];
            } elseif (isset($source[$paramNames[$i]]) && $source[$paramNames[$i]] != '') {
                $actionParams[] = $source[$paramNames[$i]];
            } else {
                $actionParams[] = null;
            }
        }

        $currentUser = Model::$user;
        if ($controller->$actionName->CheckRights($currentUser)) {
            $content = $controller->$actionName($actionParams);

            if (! is_array($content)) {
                $content = [];
            }

            $content = array_merge($controller->GetRights($currentUser->type), $content);
        } else {
            $content = ['tcmf_fatalError' => 1, 'mess' => lang('Нет прав доступа.') . '[controller: ' . $controller->name . ', method: ' . $actionName . ']'];
            return false;
        }

        $content['oc'] = (!$oc) ? ('oc' . ceil((rand(1, 1000) / 10000) * 10000000)) : $oc;
        $content['controllerName'] = $controller->name;
        $content['actionName'] = $actionName;
        if (isset($controller->controller->modelName)) {
            $content['modelName'] = $controller->controller->modelName;
        }

        if (! is_array($content)) {
            $content = [];
        }

        $content = array_merge(Application::GetGlobalParams(), $content);

        return $content;
    }

    public static function GetViewData($controllerName, $actionName, $navParams, $oc = null, $hardCache = false)
    {
        global $time_spent;
        global $debug;

        if (self::$routing instanceof Routing && self::$routing->IsBlockName($oc)) {
            $info = self::$routing->GetBlockNameInfo($oc);
            $actionName = $info['method'];
            $controller = new Controller($info['controller'], Application::$section);
            $view = new View($actionName, $controller->name, Application::$section);
            $source = array_merge($_POST, self::$routing->GetParamsHash($oc));
        } else {
            $controller = new Controller($controllerName, Application::$section);
            $view = new View($actionName, $controller->name, Application::$section);
            $source = [];
        }

        if ($hardCache and (int)Model::$conf->cache == 0) {
            $result = $view->GetCached();

            if ($result !== false) {
                return $result;
            }
        }

        $content = Application::GetRawData($controller, $actionName, $navParams, $source, $oc);

        if (isset($content['tcmf_fatalError'])) {
            $view = new View('error', 'include', self::$section);
        }

        $mc = microtime(true);
        $debug[$controllerName . '-' . $actionName . ($oc !== '' ? '-' . $oc : '')] = ($mc - $time_spent) * 1000;
        $time_spent = $mc;

        return $view->Get($content, $hardCache);
    }



    public static function rewriteURI($query)
    {
        if (! $query[0])
            return $query;

        if ($category = Category::one($query[0]) and $category->published()) {
            self::$mainObjectData = $category;

            return [
                'category',
                'info',
                $category->name
            ];
        }

        if (! ($page = Pages::one($query[0])) or ! $page->published())
            return ['404'];

        if ($query[0] == Pages::getUrlName('gallery')) {
            if ($query[1] != '') {
                if ($gallery = Gallery::one($query[1]) and $gallery->published()) {
                    self::$mainObjectData = $gallery;

                    return [
                        Pages::getUrlName('gallery-category'),
                        'info',
                        $gallery->name
                    ];
                } else {
                    return ['404'];
                }
            }
        }

        if ($page->custom()) {
            self::$pageObject = $page;
            self::$mainObjectData = $page;

            return [
                'page',
                'info',
                $page->name
            ];
        }

        return $query;
    }

    public static function setLastModified($page)
    {

    }

    public static function setCanonical()
    {
        if (Application::$section !== 'backend' and Application::$returnType == 'html') {
            $query = array_filter(explode('/', preg_replace('#^/#', '', $_SERVER['REQUEST_URI'])));
        }

        $content = self::$global;

        if ($content['url'] == 'https://' . Model::$conf->host . '/ro' or $content['url'] == 'http://' . Model::$conf->host . '/ro') {
            return 'https://' . Model::$conf->host;
        }

        return null;
    }

    public static function wantsJson()
    {
        return Application::$returnType == 'json';
    }
}
