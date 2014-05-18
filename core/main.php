<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 02/05/14
 * Time: 10:41 PM
 */

add_theme_support( 'post-thumbnails' );

// Constants
define('__DIR__', realpath(dirname(__FILE__)));
define('THEME_PATH', get_bloginfo('template_url'));
define('ALTER', THEME_PATH . "/alter/");
define('RWMB_URL', ALTER . "vendor/meta-box/" );

// Assets constants
if(!defined('ALTER_IMG')) define('ALTER_IMG', THEME_PATH . "img/");
if(!defined('ALTER_IMG')) define('ALTER_CSS', THEME_PATH . "css/");
if(!defined('ALTER_IMG')) define('ALTER_JS', THEME_PATH . "js/");

// Importa os vendors
require __DIR__."/../vendor/meta-box/meta-box.php";
require __DIR__."/../vendor/Wordpress-for-Developers/lib/load.php";

// ---- Import framework Classes

// Exceptions
require_once  __DIR__."/exceptions/NoPostFoundException.php";

// Framework Classes
require_once  __DIR__."/App.php";
require_once  __DIR__."/Post.php";
require_once  __DIR__."/Helper.php";
require_once  __DIR__."/AppModel.php";
require_once  __DIR__."/OptionPage.php";
require_once  __DIR__."/AppTaxonomy.php";
require_once  __DIR__."/RegisterMetabox.php";
require_once  __DIR__."/AdminPage.php";

// Initialize the app
global $app, $h;
$app = new App();
$h = new Helper();

// User Models, Views and Controllers
$rw = new RegisterMetabox();

foreach(array('model', 'controller', 'view', 'option') as $folder){

    foreach(glob( __DIR__.'/../../'.$folder . "/*.php") as $file){

        $name = str_replace('.php', '', $file);
        $name_arr = explode('/', $name);
        $name = $name_arr[count($name_arr) - 1];;

        require $file;

        $instance = new $name;

        // Register the meta-boxes if is a model
        if(is_subclass_of($instance, 'AppModel')){
            $app->registerModel($instance);
            $rw->add($instance->getPostType(), $instance->getFields());
        }

        if(is_subclass_of($instance, 'OptionPage')){
            $app->registerOption($instance);
        }

    }
}

$rw->register();