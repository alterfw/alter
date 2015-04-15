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

define('ALTER', __DIR__ . "/..");
define('ALTER_VENDOR', ALTER . "/..");

if(!defined('ASSETS_PATH')) define('ASSETS_PATH', get_bloginfo('template_url'));
if(!defined('APPLICATION_PATH')) define('APPLICATION_PATH', get_template_directory());

$path = explode('wp-content', realpath(ALTER_VENDOR . "/meta-box/"));
define('RWMB_URL', get_site_url().'/wp-content'.$path[1].'/');
define('RWMB_DIR', ALTER_VENDOR . "/meta-box/" );

// Assets constants
if(!defined('ALTER_IMG')) define('ALTER_IMG', ASSETS_PATH . "/img");
if(!defined('ALTER_CSS')) define('ALTER_CSS', ASSETS_PATH . "/css");
if(!defined('ALTER_JS')) define('ALTER_JS', ASSETS_PATH . "/js");

// ---- Import framework dependencies in the right order (Composer sucks for that!)
require_once ALTER_VENDOR . "/php-form-generator/fg/load.php";
require_once ALTER_VENDOR . "/wordpress-for-developers/lib/load.php";
require_once ALTER_VENDOR . "/meta-box/meta-box.php";

// ---- Import framework Classes

// Exceptions
require_once  __DIR__."/exceptions/NoPostFoundException.php";

// Framework Classes
require_once __DIR__ . "/core/App.php";
require_once __DIR__ . "/core/Post.php";
require_once __DIR__ . "/core/Model.php";
require_once __DIR__ . "/core/Loader.php";
require_once __DIR__ . "/api/Helper.php";
require_once __DIR__ . "/api/AppModel.php";
require_once __DIR__ . "/api/OptionPage.php";
require_once __DIR__ . "/api/AppTaxonomy.php";
require_once __DIR__ . "/api/AdminPage.php";
require_once __DIR__ . "/utility/RegisterMetabox.php";
require_once __DIR__ . "/utility/Utils.php";

// Initialize the app
global $app, $h;
$app = new App();
$h = new Helper();

// Load the user files
new Loader($app);
