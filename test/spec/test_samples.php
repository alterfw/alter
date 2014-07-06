<?php

/**
 * Tests to test that that testing framework is testing tests. Meta, huh?
 *
 * @package wordpress-plugins-tests
 */
class WP_Test_Wordpress_samples extends WP_UnitTestCase {

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
