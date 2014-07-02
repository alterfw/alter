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
define('ALTER', get_template_directory() . "/vendor/alterfw/alter/");
define('RWMB_URL', get_template_directory() . "/vendor/rilwis/meta-box/" );

// Assets constants
if(!defined('ALTER_IMG')) define('ALTER_IMG', THEME_PATH . "img/");
if(!defined('ALTER_IMG')) define('ALTER_CSS', THEME_PATH . "css/");
if(!defined('ALTER_IMG')) define('ALTER_JS', THEME_PATH . "js/");

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
require_once  __DIR__."/OptionTree.php";

// Initialize the app
global $app, $h, $OPT;
$app = new App();
$h = new Helper();

// User Models, Views and Controllers
$rw = new RegisterMetabox();

foreach(array('model', 'controller', 'view', 'option') as $folder){

    foreach(glob( get_template_directory().'/'.$folder . "/*.php") as $file){

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

        if(is_subclass_of($instance, 'OptionTree')){
            $OPT = new OPT($instance);
        }

    }
}

$rw->register();