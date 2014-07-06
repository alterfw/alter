<?php

/**
 * Tests to test that that testing framework is testing tests. Meta, huh?
 *
 * @package wordpress-plugins-tests
 */
class WP_Test_Wordpress_samples extends Alter_UnitTestCase {

    function test_theme_is_active(){

        $this->assertTrue( 'Alter Example Theme' == get_current_theme() );

    }

	/**
	 * Run a simple test to ensure that the tests are running
	 */
	 function test_tests() {

		$this->assertTrue( true );

	 }

    function test_name(){

        $this->assertEquals(get_bloginfo('name'), 'Test Blog');

    }

}
