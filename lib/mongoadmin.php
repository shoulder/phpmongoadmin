<?php

class MongoAdmin
{
    static public $_config = array();
    static public $_db;
    static public $root_path;

    static function loadConfig($config_filename)
    {
        self::$_config = require($config_filename);
    }

    static function get_config($option_name, $default_value = null)
    {
        return isset(self::$_config[$option_name])
                ? self::$_config[$option_name]
                : $default_value;
    }

    static function set_config($option_name, $value)
    {
        self::$_config[$option_name] = $value;
    }

    static function tpl($tpl_name, array $vars = array())
    {
        $tpl_dir = self::get_config('tpl_dir', dirname(dirname(__FILE__)) . '/tpl');
        $tpl_filename = rtrim($tpl_dir, '/\\') . '/' . ltrim($tpl_name, '/\\') . '.php';

        extract($vars);
//        $_errors = error_reporting(0);
//        error_reporting($_errors & ~E_NOTICE);
        $_BASE_DIR = base_dir();
        require $tpl_filename;
//        error_reporting($_errors);
    }

    static function dispatching()
    {
        if (isset($_GET['action']))
        {
            $action = strtolower(trim($_GET['action']));
        }
        else
        {
            $action = self::get_config('default_action');
        }

        $arr = explode('.', $action);
        $method = preg_replace('/[^a-z]/', '', trim(array_pop($arr)));
        $action = preg_replace('/[^a-z]/', '', trim(array_pop($arr)));

        if (empty($method)) $method = 'index';
        if (empty($action)) $action = 'default';

        $filename = self::get_config('actions_dir', dirname(__FILE__) . '/actions');
        $filename .= "/{$action}.php";

        if (!is_file($filename))
        {
            return self::error404($action, $method);
        }

        $class_name = "{$action}Action";
        $method_name = "{$method}_method";

        require $filename;
        $action = new $class_name();
        return $action->{$method_name}();
    }

    static function db()
    {
        if (is_null(self::$_db))
        {
            $conn = new Mongo();
            self::$_db = $conn->selectDB(self::get_config('db_name'));
        }
        return self::$_db;
    }

    static function error404($action, $method)
    {
        self::tpl('error404', array('action' => $action, 'method' => $method));
    }
}



function h($txt)
{
    return htmlspecialchars($txt);
}

function base_dir()
{
    static $base_dir = null;
    if (!$base_dir)
    {
        $base_dir = dirname($_SERVER['SCRIPT_NAME']);
        if ($base_dir == '/') $base_dir = '';
    }
    return $base_dir;
}

function url($action, array $vars = array())
{
    $url = base_dir() . '/?action=' . $action;
    if (!empty($vars))
    {
        $url .= '&' . http_build_query($vars);
    }
    return $url;
}

function redirect($url)
{
    header('Location: '. $url);
    exit;
}


MongoAdmin::$root_path = dirname(__FILE__);

// 禁止 magic quotes
if (phpversion() < '5.3')
{
    set_magic_quotes_runtime(0);
}

// 处理被 magic quotes 自动转义过的数据
if (get_magic_quotes_gpc())
{
    $in = array(& $_GET, & $_POST, & $_COOKIE, & $_REQUEST);
    while (list ($k, $v) = each($in))
    {
        foreach ($v as $key => $val)
        {
            if (! is_array($val))
            {
                $in[$k][$key] = stripslashes($val);
                continue;
            }
            $in[] = & $in[$k][$key];
        }
    }
    unset($in);
}


