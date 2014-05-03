<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 02/05/14
 * Time: 10:41 PM
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Importa os vendors
require_once __DIR__."/../vendor/meta-box/meta-box.php";
//require_once __DIR__."/../vendor/Wordpress-for-Developers/lib/load.php";

// ---- Import framework Classes

// Exceptions
require_once  __DIR__."/exceptions/NoPostFoundException.php";

// Framework Classes
require_once  __DIR__."/App.php";
require_once  __DIR__."/Post.php";
require_once  __DIR__."/AppModel.php";
require_once  __DIR__."/RegisterMetaBox.php";

// Initialize the app
global $app;
$app = new App();

// User Models, Views and Controllers
$rw = new RegisterMetabox();

foreach(['model', 'controller', 'view'] as $folder){

    foreach(glob( __DIR__.'/../'.$folder . "/*.php") as $file){

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

    }
}

$rw->register();