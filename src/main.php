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
if(!defined('THEME_ABSOLUTE_PATH')) define('THEME_ABSOLUTE_PATH', get_template_directory());
define('ALTER', get_template_directory() . "/vendor/alterfw/alter/");
define('RWMB_URL', THEME_PATH . "/vendor/alterfw/meta-box/" );
define('RWMB_DIR', THEME_ABSOLUTE_PATH . "/vendor/alterfw/meta-box/" );

// Assets constants
if(!defined('ALTER_IMG')) define('ALTER_IMG', THEME_PATH . "img/");
if(!defined('ALTER_IMG')) define('ALTER_CSS', THEME_PATH . "css/");
if(!defined('ALTER_IMG')) define('ALTER_JS', THEME_PATH . "js/");

// ---- Import framework dependencies in the right order (Composer sucks for that!)
require_once THEME_ABSOLUTE_PATH . "/vendor/alterfw/php-form-generator/fg/load.php";
require_once THEME_ABSOLUTE_PATH . "/vendor/alterfw/wordpress-for-developers/lib/load.php";
require_once THEME_ABSOLUTE_PATH . "/vendor/alterfw/meta-box/meta-box.php";

// ---- Import framework Classes

// Exceptions
require_once  __DIR__."/exceptions/NoPostFoundException.php";

// Framework Classes
require_once __DIR__ . "/core/App.php";
require_once __DIR__ . "/core/Post.php";
require_once __DIR__ . "/core/Loader.php";
require_once __DIR__ . "/api/Helper.php";
require_once __DIR__ . "/api/AppModel.php";
require_once __DIR__ . "/api/OptionPage.php";
require_once __DIR__ . "/api/AppTaxonomy.php";
require_once __DIR__ . "/api/AdminPage.php";
require_once __DIR__ . "/utility/RegisterMetabox.php";

// Initialize the app
global $app, $h;
$app = new App();
$h = new Helper();

// Load the user files
new Loader($app);