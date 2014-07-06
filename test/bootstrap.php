<?php
/**
 * Installs WordPress for running the tests and loads WordPress and the test libraries
 */

define('ABSPATH',  '/tmp/wordpress/src/');
define('THEME_ABSOLUTE_PATH',  '/tmp/wordpress/src/wp-content/themes/example-theme-master/');
//error_reporting(0);

//require_once 'PHPUnit/Autoload.php';

$config_file_path = ABSPATH . 'wp-tests-config.php';

/*
 * Globalize some WordPress variables, because PHPUnit loads this file inside a function
 * See: https://github.com/sebastianbergmann/phpunit/issues/325
 * See: https://github.com/sebastianbergmann/phpunit/issues/325
 *
 * These are not needed for WordPress 3.3+, only for older versions
*/
global $table_prefix, $wp_embed, $wp_locale, $_wp_deprecated_widgets_callbacks, $wp_widget_factory;

// These are still needed
global $wpdb, $current_site, $current_blog, $wp_rewrite, $shortcode_tags, $wp;

//define( 'WPMU_PLUGIN_DIR', dirname( __FILE__ ) . '/mu-plugins' );

require_once $config_file_path;

$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST'] = WP_TESTS_DOMAIN;
$PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';

system( WP_PHP_BINARY . ' ' . escapeshellarg( dirname( __FILE__ ) . '/bin/install.php' ) . ' ' . escapeshellarg( $config_file_path ) );

require dirname( __FILE__ ) . '/lib/functions.php';

// Load WordPress
require_once ABSPATH . '/wp-settings.php';

require dirname( __FILE__ ) . '/lib/testcase.php';
require dirname( __FILE__ ) . '/lib/exceptions.php';
require dirname( __FILE__ ) . '/lib/Alter_UnitTestCase.php';

require_once THEME_ABSOLUTE_PATH . '/functions.php';
