<?php

final class View
{
    private static $dir = ""; // Путь после корневой директории, указывается в том случае если директория шаблонов расположена не в корневой директории сайта
    private static $extension = "tpl";
    private static $leftSeparator = "{%";
    private static $rightSeparator = "%}";
    private static $blockCount = array();

    private $filePath; // Директория шаблонов
    private $cachePath; // Директория кешированых шаблонов
    private $includePath; // Директория подключаемых шаблонов
    private $viewPath; // Директория шаблонов контроллера
    private $jsPath;
    private $defaultPath;

    private $info;

    private $db;
    private $conf;

    private $pathName;
    private $content;
    private $errorStatus;
    private $error;

    private $modules = array();
    private $cmds = array();
    private $names = array();
    private $ifs = array();
    private $stack = array();
    public static $count = 0;

    public function __construct($name, $controllerName, $section, $hardCache = false)
    {
        $conf = KernelSettings::GetInstance();

        $this->errorStatus = false;
        $this->includePath = $conf->contentPath . '/' . $section . '/view/include';
        $this->jsPath = $conf->contentPath . '/' . $section . '/view/js';
        $this->defaultPath = $conf->contentPath . '/' . $section . '/view/default';
        $this->cachePath = $conf->cacheContentPath . '/' . $section;

        // Override
        $this->includePathOverride = $conf->overrideContentPath . '/' . $section . '/view/include';
        $this->jsPathOverride = $conf->overrideContentPath . '/' . $section . '/view/js';
        $this->defaultPathOverride = $conf->overrideContentPath . '/' . $section . '/view/default';

        $this->filePath = $conf->contentPath . '/' . $section . '/view/' . $controllerName . '/' . $name . '.tpl';
        // Override
        $this->filePathOverride = $conf->overrideContentPath . '/' . $section . '/view/' . $controllerName . '/' . $name . '.tpl';

        if (!is_file($this->filePath)){
            $this->filePath = $conf->contentPath . '/' . $section . '/view/default/' . $name . '.tpl';
            // Override
            $this->filePathOverride = $conf->overrideContentPath . '/' . $section . '/view/default/' . $name . '.tpl';
        }

        $this->viewPath = $conf->contentPath . '/' . $section . '/view/' . $controllerName;
        // Override
        $this->viewPathOverride = $conf->overrideContentPath . '/' . $section . '/view/' . $controllerName;

        $this->info['name'] = $name;
        $this->info['controllerName'] = $controllerName;

        if(!is_file($this->filePath))
        {
//            if(!is_file($this->filePathOverride))
//            {
            throw new Exception(lang('Шаблон не найден.') . '[view: ' . $name . ', controller: ' . $controllerName . ']');
//            }
        }
    }

    function __get($name)
    {
        return isset($this->info[$name]) ? $this->info[$name] : NULL;
    }

    public function GetInfo()
    {
        return $this->info;
    }

    private function Error($error)
    {
        $this->error = lang("Синтаксическая ошибка.") . " " . $error . " [controller: '" . $this->info['controllerName'] . "', view: '" . $this->info['name'] . "']";
    }

    private function IncludeFile($name)
    {
//        if(file_exists($this->includePathOverride . '/' . $name . ".tpl"))
//            return file_get_contents($this->includePathOverride . '/' . $name . ".tpl");
//        else
        return file_get_contents($this->includePath . '/' . $name . ".tpl");
    }

    private function IncludeMainView()
    {
//        if(file_exists($this->filePathOverride))
//            $data = file_get_contents($this->filePathOverride);
//        else
        $data = file_get_contents($this->filePath);

        array_unshift($this->modules, array());
        $this->modules[0]['data'] = $data;
        $this->modules[0]['pos'] = 0;
        $this->modules[0]['size'] = strlen($data);
        $this->modules[0]['name'] = $this->name;
        $this->modules[0]['line'] = 1;
    }

    private function IncludeView($name, $array = array(), $type = "include")
    {
        if($type == 'include'){

//            if(file_exists($this->includePathOverride . '/' . $name . '.tpl'))
//                $data = file_get_contents($this->includePathOverride . '/' . $name . '.tpl');
//            else
            $data = file_get_contents($this->includePath . '/' . $name . '.tpl');

        }elseif($type == 'js'){

//            if(file_exists($this->jsPathOverride . '/' . $name . '.tpl'))
//                $data = file_get_contents($this->jsPathOverride . '/' . $name . '.tpl');
//            else
            $data = file_get_contents($this->jsPath . '/' . $name . '.tpl');

        }elseif($type == 'includeview'){

//            $oPath = $this->viewPathOverride . '/' . $name . '.tpl';
//            if(!file_exists($oPath))
//                $oPath = $this->defaultPathOverride . '/' . $name . '.tpl';
//
//            if(file_exists($oPath)){
//                $data = file_get_contents($oPath);
//            }else{
            $Path = $this->viewPath . '/' . $name . '.tpl';
            if(!file_exists($Path))
                $Path = $this->defaultPath . '/' . $name . '.tpl';

            $data = file_get_contents($Path);
//            }
        }

        array_unshift($this->modules, array());
        $this->modules[0]['data'] = $data;
        $this->modules[0]['pos'] = 0;
        $this->modules[0]['size'] = strlen($data);
        $this->modules[0]['name'] = $name;
        $this->modules[0]['line'] = 1;
        $this->modules[0]['array'] = $array;
    }

    private function IncludeField($name, $array = array())
    {
//        $oPath = $this->viewPathOverride . '/field_'.$array[0].'.tpl';
//        if(!file_exists($oPath))
//            $oPath = $this->defaultPathOverride . '/field_'.$array[0].'.tpl';
//
//        if(file_exists($oPath)){
//            $data = file_get_contents($oPath);
//        }else{
        $Path = $this->viewPath . '/field_'.$array[0].'.tpl';
        if(!file_exists($Path))
            $Path = $this->defaultPath . '/field_'.$array[0].'.tpl';

        $data = file_get_contents($Path);
//        }

        array_unshift($this->modules, array());

        $data = '{%assign _field_type = "'.$array[0].'"%}'.$data;
        $data = '{%assign _field_name = "'.$array[1].'"%}'.$data;
        $data = '{%assign _field_title = lang@'.$array[2].'%}'.$data;
        $data = '{%assign _field_width = '.($array[3]?$array[3]:6).'%}'.$data;

        $this->modules[0]['data'] = $data;
        $this->modules[0]['pos'] = 0;
        $this->modules[0]['size'] = strlen($data);
        $this->modules[0]['name'] = $name;
        $this->modules[0]['line'] = 1;
        $this->modules[0]['array'] = $array;
    }


    private function getImagePath($model, $ID, $basename = '', $type = '', $size = '')
    {
        $path = '/img/images/' . $model . '/' . $ID . '/' . ($type != '' ? $type . '/' : '') . '/' . $basename;

        if ($basename == '' or ! file_exists(Model::$conf->documentroot . $path))
            $path = '/img/images/nophoto/' . ($size != '' ? $size . '/' : '') . 'nophoto.jpg';

        return $path;
    }

    private function Variable($var)
    {
        if ($var[0] == "."){
            return (count($this->names) ? end($this->names) . $var : substr($var, 1));
        }elseif ($var[0] == "@"){
            return $this->Variable($this->Variable(substr($var, 1)));
        }else{
            return $var;
        }
    }

    private function VariableDynamic($var)
    {

        $params = explode(".", $var);

        foreach($params as $value){
            $type = ($value[0] == '"' or $value[0] == "'") ? "string" : "var";
            $newvar[] = array("value" => trim($value), "type" => $type);
        }

        return $newvar;
    }

    private function GetCmd($cmd)
    {
        $pos = strpos($cmd, " ");
        if ($pos === false)
        {
            $cmdname = $cmd;
            $arg = "";
        }
        else
        {
            $cmdname = substr($cmd, 0, $pos);
            $arg = substr($cmd, $pos + 1);
        }

        if($cmdname == "include")
        {
            $this->IncludeView(trim($arg));
            return true;
        }

        elseif($cmdname == "js")
        {
            $this->IncludeView(trim($arg), array(), "js");
            return true;
        }

        elseif($cmdname == "includeview")
        {
            $this->IncludeView(trim($arg), array(), "includeview");
            return true;
        }

        elseif($cmdname == "pagepath")
        {
            $this->cmds[] = array("cmd" => $cmdname, 'arg' => $arg);
            return true;
        }

        elseif($cmdname == "image")
        {
            $args = explode("/", $arg);

            $args = $args + array_fill(0, 5, '');

            $args[1] = $this->Variable($args[1]);
            $args[2] = $this->Variable($args[2]);

            $this->cmds[] = array("cmd" => $cmdname, 'arg' => $args);

            return true;
        }

        elseif($cmdname == "field")
        {
            $arr = explode("/", $arg);

            $this->IncludeField("field", $arr);

            return true;
        }


        elseif ($cmdname == "end")
        {
            $up = array_pop($this->stack);
            if($up == "if")
            {
                $this->Error(lang("Закрытие конструкции 'if' конструкцией 'end'") . " ("  . array_pop($this->ifs) . ")");
                return false;
            }
            else
            {
                array_pop($this->names);
                $this->cmds[] = array("cmd" => "end");
            }
        }
        elseif ($cmdname == "endif")
        {
            $up = array_pop($this->stack);
            if ($up && ($up != "if"))
            {
                $this->Error(lang("Закрытие конструкции 'list' или 'block' конструкцией 'endif'") . " ("  . array_pop($this->names) . ")");
                return false;
            }
            else
            {
                array_pop($this->ifs);
                $this->cmds[] = array("cmd" => "endif");
            }
        }
        elseif ($cmdname == "else")
        {
            $up = array_pop($this->stack);
            array_push($this->stack, $up);
            if ($up && ($up != "if"))
            {
                $this->Error(lang("Использование 'else' в конструкции 'list' или 'block'") . " (" . array_pop($this->names) . ")");
                return false;
            }
            else
            {
                $this->cmds[] = array("cmd" => "else");
            }
        }
        elseif ($cmdname == "list" || $cmdname == "block")
        {
            $this->cmds[] = array("cmd" => $cmdname,
                                  "name" => $this->Variable($arg));
            array_push($this->names, $this->Variable($arg));
            array_push($this->stack, $cmdname);
        }
        elseif ($cmdname == "nodes")
        {
            $this->cmds[] = array("cmd" => $cmdname,
                                  "name" => $this->Variable($arg));
            array_push($this->names, $this->Variable($arg));
            array_push($this->stack, $cmdname);
        }
        elseif ($cmdname == "controller")
        {
            $this->cmds[] = array("cmd" => $cmdname,
                                  "arg" => $this->Variable($arg));
            //array_push($this->names, $this->Variable($arg));
        }
        elseif ($cmdname == "controllerdynamic")
        {
            $blockInfo = $this->Variable($arg);
            //array_push($this->names, $this->Variable($blockInfo));

            $this->cmds[] = array("cmd" => $cmdname, 'arg' => $this->Variable($arg));
        }
        elseif ($cmdname == "assign")
        {
            $params = explode("=", $arg);
            foreach($params as &$value) $value = trim($value);

            $var = (strpos($params[1], '"') === false) ? true : false;
            $lang = (strpos($params[1], 'lang@') !== false) ? true : false;

            if($lang){
                $params[1] = "lang(".str_replace("lang@", "", $params[1]).")";
            }elseif($var){
                $params[1] = $this->Variable($params[1]);
            }

            $this->cmds[] = array("cmd" => $cmdname, "arg" => $params, "var" => $var, "lang" => $lang);
        }
        elseif ($cmdname == "textblock")
        {
            $this->cmds[] = array("cmd" => $cmdname, 'arg' => $arg);
        }
        elseif ($cmdname == "config")
        {
            $this->cmds[] = array("cmd" => $cmdname, 'arg' => $arg);
        }
        elseif ($cmdname == "if" || $cmdname == "ifnot")
        {
            array_push($this->ifs, $arg);
            array_push($this->stack, "if");

            preg_match_all("/([@\\.a-z_-][\\.a-z0-9_-]*)|(==|%|>|<|<=|>=|!==)|([\"\']{1}[^\"\']*[\"\']{1})|([0-9]+)/i", $arg, $match);
            $a = trim($match[0][0]);
            $op = $match[0][1];
            $b = trim($match[0][2]);
            $this->cmds[] = array("cmd" => $cmdname,
                                  "a" => ($a[0] != "\"" && $a[0] != "'") ? ($a[0]=="@"?$this->VariableDynamic(substr($a, 1)):$this->Variable($a)) : $a,
                                  "b" => ($b[0] != "\"" && $b[0] != "'") ? ($b[0]=="@"?$this->VariableDynamic(substr($b, 1)):$this->Variable($b)) : $b,
                                  "op" => $op);
        }
        elseif ($cmdname == "ifdots")
        {
            array_push($this->ifs, $arg);
            array_push($this->stack, "if");

            preg_match_all("/([\\.a-z_-][\\.a-z0-9_-]*)|(==|%|>|<|<=|>=|!==)|([\"\']{1}[^\"\']*[\"\']{1})|([0-9]+)/i", $arg, $match);
            $a = $match[0][0];
            $op = $match[0][1];
            $b = $match[0][2];
            $this->cmds[] = array("cmd" => $cmdname,
                                  "a" => ($a[0] != "\"" && $a[0] != "'") ? $this->Variable($a) : $a,
                                  "b" => ($b[0] != "\"" && $b[0] != "'") ? $this->Variable($b) : $b,
                                  "op" => $op);
        }
        elseif ($cmdname == "ifcount")
        {
            array_push($this->ifs, $arg);
            array_push($this->stack, "if");

            preg_match_all("/([\\.a-z_-][\\.a-z0-9_-]*)|(==|%|>|<|<=|>=|!=)|([\"\']{1}[^\"\']*[\"\']{1})|([0-9]+)/i", $arg, $match);
            $a = $match[0][0];
            $op = $match[0][1];
            $b = $match[0][2];
            $this->cmds[] = array("cmd" => $cmdname,
                                  "a" => ($a[0] != "\"" && $a[0] != "'") ? $this->Variable($a) : $a,
                                  "b" => ($b[0] != "\"" && $b[0] != "'") ? $this->Variable($b) : $b,
                                  "op" => $op);
        }
        elseif ($cmdname == "lang")
        {
            $this->cmds[] = array("cmd" => $cmdname,
                                  "arg" => $this->Variable($arg));
        }
        elseif ($cmdname == "ifset")
        {
            array_push($this->ifs, $arg);
            array_push($this->stack, "if");
            $this->cmds[] = array("cmd" => $cmdname,
                                  "arg" => self::Variable($arg));
        }
        elseif ($cmdname == "@ifset")
        {
            array_push($this->ifs, $arg);
            array_push($this->stack, "if");
            $this->cmds[] = array("cmd" => $cmdname,
                                  "arg" => self::VariableDynamic($arg));
        }
        elseif ($cmdname == "ifnotset")
        {
            array_push($this->ifs, $arg);
            array_push($this->stack, "if");
            $this->cmds[] = array("cmd" => "ifnotset",
                                  "arg" => self::Variable($arg));
        }
        elseif ($cmdname == "ifinarray")
        {
            $argArr = explode(",", $arg);
            array_push($this->ifs, $arg);
            array_push($this->stack, "if");
            $this->cmds[] = array("cmd" => 'in_array',
                                  "a" => $this->Variable($argArr[0]),
                                  "b" => $this->Variable($argArr[1]),
                                  "arg" => self::Variable($arg));
        }
        elseif ($cmdname == "@")
        {
            $this->cmds[] = array("cmd" => "vardynamic",
                                  "name" => self::VariableDynamic($arg));
        }
        else
        {
            $this->cmds[] = array("cmd" => "var",
                                  "name" => self::Variable($cmd));
        }

        return true;
    }

    private function GetText($text)
    {
        $this->cmds[] = array("cmd" => "text",
                              "data" => $text);
    }

    private function Separate()
    {
        $done = $oldpos = 0;

        while (!$done)
        {
            $buff = "";
            $declude = 0;

            $pos = (isset($this->modules[0]['data'])) ? mb_strpos($this->modules[0]['data'], self::$leftSeparator, $oldpos, 'UTF-8') : false;
            if ($pos === false)
            {
                $pos = (isset($this->modules[0]['data'])) ? mb_strlen($this->modules[0]['data'], 'UTF-8') : 0;
                $declude = 1;
            }

            if (isset($this->modules[0]['data']))
                $buff = mb_substr($this->modules[0]['data'], $oldpos, $pos - $oldpos, 'UTF-8');

            $this->GetText($buff);
            if ($declude)
            {
                if (count($this->modules))
                {
                    array_shift($this->modules);
                    $oldpos = (isset($this->modules[0]['pos'])) ? $this->modules[0]['pos'] : 0;
                }
                else
                    $done = 1;
            }
            else
            {
                $oldpos = $pos + 2;
                $pos = mb_strpos($this->modules[0]['data'], self::$rightSeparator, $oldpos, 'UTF-8');

                if ($pos === false)
                {
                    $this->Error(lang("Команда не закрыта."));
                    return false;
                }
                else
                {
                    $buff = mb_substr($this->modules[0]['data'], $oldpos, $pos - $oldpos, 'UTF-8');
                    $oldpos = $pos + 2;
                    $this->modules[0]['pos'] = $oldpos;
                    if (!$this->GetCmd($buff))
                        return false;
                    $oldpos = $this->modules[0]['pos'];
                }
            }
        }

        return true;
    }

    private function VarEx($var, $free = false)
    {
        $quotes = "'";

        if($free){
            return "\$this->content[".$var."]";
        }

        if ($var[0] == "\"" || $var[0] == "'" || preg_match("#^[0-9]+$#is", $var[0]))
            return $var;

        $var = explode(".", $var);
        if (count($var) == 1)
        {
            return "\$this->content[".$quotes . $var[0] . $quotes."]";
        }
        else
        {
            $lv = array_pop($var);
            $var = implode(".", $var);

            return "\$this->content[".$quotes."@" . $var . $quotes."][".$quotes . $lv . $quotes."]";
        }
    }

    private function CreateDynamicVar($arr)
    {
        $var = "";
        $n = 1;
        foreach($arr as $value){
            if($value["type"] == "string"){
                $var .= ($n>1?".":"").$value["value"];
            }else{
                $var .= ($n>1?".":"").$this->VarEx($value["value"]);
            }
            $n++;
        }
        return (string)$var;
    }

    private function Build()
    {
        $result = "";

        foreach ($this->cmds as $cmd)
        {
            if($cmd['cmd'] == "text")
                $result .= $cmd['data'];
            elseif ($cmd['cmd'] == "var")
                $result .= "<?php if(isset(" . $this->VarEx($cmd['name']) . ")) print " . $this->VarEx($cmd['name']) . "?>";
            elseif ($cmd['cmd'] == "vardynamic"){
                $var = self::CreateDynamicVar($cmd["name"]);
                $result .= "<?php if(isset(" . $this->VarEx($var, true) . ")) print " . $this->VarEx($var, true) . "?>";
            }
            elseif ($cmd['cmd'] == "end")
                $result .= "<?php }?>";
            elseif ($cmd['cmd'] == "endif")
                $result .= "<?php }?>";
            elseif ($cmd['cmd'] == "else")
                $result .= "<?php }else{?>";
            elseif ($cmd['cmd'] == "list")
            {
                $indexvar = $this->VarEx("{$cmd['name']}._index");
                $countvar = $this->VarEx("{$cmd['name']}._count");
                $first = $this->VarEx("{$cmd['name']}._first");
                $last = $this->VarEx("{$cmd['name']}._last");
                $valuevar = $this->VarEx("{$cmd['name']}._value");
                $result .= "<?php "
                    . "\$this->content[\"#{$cmd['name']}\"]=0;"
                    . "if(isset(" . $this->VarEx($cmd['name']) . ") && is_array(" . $this->VarEx($cmd['name']) . ")) foreach(" . $this->VarEx($cmd['name']) . " as \$this->content[\"@{$cmd['name']}\"]){"
                    . "\$this->content[\"#{$cmd['name']}\"]++;"
                    . "$valuevar=\$this->content[\"@{$cmd['name']}\"]; "
                    . "$indexvar=\$this->content[\"#{$cmd['name']}\"];"
                    . "$countvar=count(" . $this->VarEx($cmd['name']) . ");if($indexvar==$countvar)$last=1;if($indexvar==1)$first=1;"
                    . "?>";
            }


            elseif ($cmd['cmd'] == "block"){
                $result .= "<?php if(isset(" . $this->VarEx($cmd['name']) . ") && is_array(" . $this->VarEx($cmd['name']) . ")){\$this->content[\"@$cmd[name]\"]=" . $this->VarEx($cmd['name']) . ";?>";
            }elseif ($cmd['cmd'] == "ifset")
                $result .= "<?php if(isset(" . $this->VarEx($cmd['arg']) . ")){?>";
            elseif ($cmd['cmd'] == "@ifset"){
                $var = self::CreateDynamicVar($cmd["arg"]);
                $result .= "<?php if(isset(" . $this->VarEx($var, true) . ")){?>";
            }elseif ($cmd['cmd'] == "if" and $cmd['op'] !== "%")
            {
                if(!is_array($cmd['a']) and !is_array($cmd['b'])){
                    $result .= "<?php if(isset(" . $this->VarEx($cmd['a']) . ") && " . $this->VarEx($cmd['a']) . $cmd['op'] .  $this->VarEx($cmd['b']) . "){?>";
                }else{
                    $a = (is_array($cmd["a"])) ? self::CreateDynamicVar($cmd["a"]) : $cmd["a"];
                    $b = (is_array($cmd["b"])) ? self::CreateDynamicVar($cmd["b"]) : $cmd["b"];
                    $result .= "<?php if(isset(" . $this->VarEx($a, (is_array($cmd["a"])?true:false)) . ") && " . $this->VarEx($a, (is_array($cmd["a"])?true:false)) . $cmd['op'] .  $this->VarEx($b, (is_array($cmd["b"])?true:false)) . "){?>";
                }
            }elseif ($cmd['cmd'] == "if" and $cmd['op'] == "%")
            {
                $result .= "<?php if(isset(" . $this->VarEx($cmd['a']) . ") && " . $this->VarEx($cmd['a']) . $cmd['op'] .  $this->VarEx($cmd['b']) . " == 0){?>";
            }
            elseif ($cmd['cmd'] == "ifcount")
            {
                $result .= "<?php if(isset(" . $this->VarEx($cmd['a']) . ") && count(" . $this->VarEx($cmd['a']) . ")" . $cmd['op'] .  $this->VarEx($cmd['b']) . "){?>";
            }
            elseif ($cmd['cmd'] == "ifdots")
            {
                $result .= "<?php if(isset(" . $this->VarEx($cmd['a']) . ") && " . $this->VarEx($cmd['a']) . $cmd['op'] . "\".\"." . $this->VarEx($cmd['b']) . ".\".\"){?>";
            }
            elseif ($cmd['cmd'] == "ifnot" and $cmd['op'] !== "%")
                $result .= "<?php if(!(" . $this->VarEx($cmd['a']) . $cmd['op'] .  $this->VarEx($cmd['b']) . ")){?>";
            elseif ($cmd['cmd'] == "ifnot" and $cmd['op'] == "%")
                $result .= "<?php if(" . $this->VarEx($cmd['a']) . $cmd['op'] .  $this->VarEx($cmd['b']) . " !== 0){?>";
            elseif ($cmd['cmd'] == "ifnotset")
                $result .= "<?php if(!isset(" . $this->VarEx($cmd['arg']) . ")){?>";
            elseif ($cmd['cmd'] == "in_array")
                $result .= "<?php if(is_array(" . $this->VarEx($cmd['b']) . ") and in_array(" . $this->VarEx($cmd['a']) . ", " . $this->VarEx($cmd['b']) . ")){?>";
            elseif ($cmd['cmd'] == "lang")
            {
                $result .= '<?php print lang("' . $cmd['arg'] . '");?>';
            }
            elseif ($cmd['cmd'] == "controller")
            {
                $paramsStack = explode(' ', $cmd['arg']);

                $blockInfo = array_shift($paramsStack);
                $oc = array_shift($paramsStack);
                $hardCache = (bool)array_shift($paramsStack);
                $nav = explode('/', $blockInfo);
                $controllerName = array_shift($nav);
                $actionName = array_shift($nav);
                $actionName = ($actionName) ? $actionName : 'index';

                $navParams = array();
                while (($navParams[] = array_shift($nav)) !== NULL);

                for ($i = 0; $i < count($navParams); $i++)
                    $navParams[$i] = '"' . $navParams[$i] . '"';

                $result .= '<?php print Application::GetViewData("' . $controllerName . '", "' . $actionName . '", array(' . implode(',', $navParams) . '), "' . $oc . '", ' . ($hardCache ? "true" : "false") . ');' . '?>';
            }
            elseif ($cmd['cmd'] == "controllerdynamic")
            {
                $result .= '<?php print View::IncludeControllerDynamic(' . $this->VarEx($cmd["arg"]) . ');' . '?>';
            }
            elseif ($cmd['cmd'] == "textblock")
            {
                $result .= '<?php print \Infoblock::getText("' . $cmd['arg'] . '");' . '?>';
            }
            elseif ($cmd['cmd'] == "pagepath")
            {
                $result .= '<?php print Pages::getUrlName("' . $cmd['arg'] . '");' . '?>';
            }
            elseif ($cmd['cmd'] == "image")
            {
                $result .= "<?php print View::getImagePath('" .  $cmd["arg"][0] . "', " .  $this->VarEx($cmd["arg"][1]) . ", " .  $this->VarEx($cmd["arg"][2]) . ", '" . $cmd["arg"][3] . "', '" .  $cmd["arg"][4] . "');?>";
            }
            elseif ($cmd['cmd'] == "config")
            {
                $result .= '<?php print Application::GetViewData("infoblocks", "show_config", array("' . $cmd['arg'] . '",""), "");' . '?>';
            }
            elseif ($cmd['cmd'] == "assign")
            {
                $result .= '<?php '. $this->VarEx($cmd["arg"][0]) .' = '. ($cmd["var"] ? $this->VarEx($cmd["arg"][1]) : $cmd["arg"][1]) .'; ?>';
            }


        }
        return $result;
    }

    public function Get($content, $hardCache = false)
    {
        $this->content = $content;

        $this->IncludeMainView();

        $cacheFileName = $this->cachePath . '/' . $this->info['controllerName'] . '.' . $this->info['name'] . '.tpl.php';
        $hardCacheFileName = $this->cachePath . '/' . $this->info['controllerName'] . '.' . $this->info['name'] . '.' . Application::$language->name . '.html';

        ob_start();
        ob_implicit_flush(0);

        if (!is_file($cacheFileName) || (int)Model::$conf->cache)
        {
            if (!$this->Separate())
            {
                ob_end_clean();
                return $this->error;
            }

            $buildContent = $this->Build();

            file_put_contents($cacheFileName, $buildContent);
        }

        include $cacheFileName;

        $result = ob_get_contents();
        ob_end_clean();

        if($hardCache and (int)Model::$conf->cache == 0)
            file_put_contents($hardCacheFileName, $result);

        return $result;
    }

    public function GetCached()
    {
        $this->content = array();

        $hardCacheFileName = $this->cachePath . '/' . $this->info['controllerName'] . '.' . $this->info['name'] . '.' . Application::$language->name . '.html';

        $cachetime = 86400;

        if(is_file($hardCacheFileName) && $cachetime > time() - filemtime($hardCacheFileName) && (int)Model::$conf->cache == 0){
            ob_start();
            ob_implicit_flush(0);

            include $hardCacheFileName;

            $result = ob_get_contents();
            ob_end_clean();

            return $result;
        }else{
            return false;
        }
    }

    public function GetWithoutScripts($content)
    {
        $result = $this->Get($content);

        $matches = array();

        preg_match_all('#<script[^>]*>(.+?)<\/script>#isu', $result, $matches);
        $result = preg_replace('#<(script)[^>]*>.+?<\/\1>#isu', '', $result);

        $scripts = '';
        foreach ($matches[1] as $value)
            $scripts .= $value . "\n";

        $result = preg_replace("#\t+#isu", " ", $result);
        $scripts = preg_replace("#\t+#isu", " ", $scripts);

        return array('content' => $result, 'scripts' => $scripts);
    }

    public function GetWithScripts($content)
    {
        $result = $this->Get($content);

        $result = preg_replace("#\t+#isu", " ", $result);

        return array('content' => $result);
    }

    public function GetPageWithoutScripts($content)
    {
        $result = $this->Get($content);
        $result = preg_replace("#^.+?<body>#uis", "", $result);
        $result = preg_replace("#</body>.+?$#uis", "", $result);

        $matches = array();
        preg_match_all('|<script[^>]*>(.+?)<\/script>|uis', $result, $matches);
        $result = preg_replace('|<(script)[^>]*>.+?<\/\1>|uis', '', $result);

        $scripts = '';
        foreach ($matches[1] as $value)
            $scripts .= $value . "\n";

        $result = str_replace("\t", "", $result);
        $scripts = str_replace("\t", "", $scripts);
        return array('content' => $result, 'scripts' => $scripts);
    }

    public static function IncludeControllerDynamic($arg)
    {
        $paramsStack = explode(' ', $arg);
        $blockInfo = array_shift($paramsStack);
        $oc = array_shift($paramsStack);
        $nav = explode('/', $blockInfo);
        $controllerName = array_shift($nav);
        $actionName = array_shift($nav);
        $actionName = ($actionName) ? $actionName : 'index';
        while (($navParams[] = array_shift($nav)) !== NULL);

        return Application::GetViewData($controllerName, $actionName, $navParams, $oc);
    }

}





?>