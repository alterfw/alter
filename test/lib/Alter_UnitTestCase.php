<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 05/07/14
 * Time: 10:47 PM
 */

class Alter_UnitTestCase extends WP_UnitTestCase{

    // Setup the theme
    function setUp() {

        parent::setUp();
        //include_once THEME_ABSOLUTE_PATH. "/functions.php";
        switch_theme( 'example-theme-master', 'Alter Example Theme' );
        global $app;
        $this->app = $app;

    }

}