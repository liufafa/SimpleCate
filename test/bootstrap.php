<?php
// Errors on full!
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
$dir = realpath(dirname(__FILE__));
// Path constants
defined('PROJECT_BASE') OR define('PROJECT_BASE', realpath($dir.'/../').DIRECTORY_SEPARATOR);
defined('SYSTEM_PATH') OR define('SYSTEM_PATH', PROJECT_BASE.'system'.DIRECTORY_SEPARATOR);

define('BASEPATH', PROJECT_BASE.'system'.DIRECTORY_SEPARATOR);
define('APPPATH', PROJECT_BASE.'application'.DIRECTORY_SEPARATOR);
//引入一个公共方法
require_once(BASEPATH . 'core/Common.php');
//加载url方法
load_class('Url', 'core');
//加载路由方法

$Router = & load_class('Router', 'core');

$class = ucfirst($Router->class);

//核心类文件
require_once BASEPATH . 'core/Controller.php';

function &get_instance() {
    return Controller::get_instance();
}

//引入路由中的类
if (empty($class) OR ! file_exists(APPPATH . 'controllers/' . $Router->directory . $class . '.php')) {
    $heading = '404 Page Not Found';
    $message = 'The page you requested was not found.';
    echo "\nERROR: ",
    $heading,
    "\n\n",
    $message,
    "\n\n";
} else {
    require_once(APPPATH . 'controllers/' . $Router->directory . $class . '.php');

    $class = new $class;
}

