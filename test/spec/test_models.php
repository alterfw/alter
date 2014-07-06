<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 05/07/14
 * Time: 10:46 PM
 */

class WP_Test_Alter_Models extends Alter_UnitTestCase {

    function test_models_loaded(){

        do_action('init');
        $this->assertTrue(class_exists('BookModel'), 'Verify if the class BookModel has been loaded');
        $this->assertTrue(class_exists('MagazineModel'), 'Verify if the class BookModel has been loaded');

    }

    function test_post_type_exists(){

        $this->assertTrue(post_type_exists('book'), 'Verify if post type "book" has been registered');
        $this->assertTrue(post_type_exists('magazine'), 'Verify if post type "magazine" has been registered');

    }

    function test_models_return(){

        $this->assertFalse(is_array($this->app->book->find()), 'Check if the model->find() method returns false (empty)');

    }

}