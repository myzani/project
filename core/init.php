<?php
define('DS', DIRECTORY_SEPARATOR);
defined('SITE_URL')      ? null : define('SITE_URL', $_SERVER['SERVER_NAME']);
defined('SITE_ROOT')     ? null : define('SITE_ROOT', realpath(dirname(__FILE__).DS.'..'.DS));
defined('LIB_PATH')      ? null : define('LIB_PATH', SITE_ROOT.DS.'core');

// Class autoload path
defined('CLASS_AUTO')    ? null : define('CLASS_AUTO', SITE_ROOT.DS.'vendor'.DS.'autoload.php');

// Header and Footer template
defined('PATH_TMPL')     ? null : define('PATH_TMPL', SITE_ROOT.DS.'public'.DS.'layout');

// Log File
defined('LOGS')          ? null : define('LOGS', SITE_ROOT.DS.'logs');

spl_autoload_register(function($class) {
    require_once(LIB_PATH.DS.$class.'.php');
});

//require_once(LIB_PATH.DS.'IUser.php');
require_once('config.php');
//require_once(CLASS_AUTO);
require_once('function.php');

//set_include_path(implode(PATH_SEPARATOR, array(
//    realpath(SITE_ROOT.DS.LIB_PATH),
//    realpath(SITE_ROOT.DS.CLASS_AUTO),
//    get_include_path()
//)));

?>
