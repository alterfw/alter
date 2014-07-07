<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 05/07/14
 * Time: 10:46 PM
 */

class WP_Test_Alter_Models extends Alter_UnitTestCase {

    /**
     * This method tests if the model has been loaded
     */
    function test_models_loaded(){

        $this->assertTrue(class_exists('BookModel'), 'Verify if the class BookModel has been loaded');
        $this->assertTrue(class_exists('MagazineModel'), 'Verify if the class BookModel has been loaded');

    }

    /**
     * This method tests if the post type has been registered
     */
    function test_post_type_exists(){

        do_action('init');
        $this->assertTrue(post_type_exists('book'), 'Verify if post type "book" has been registered');
        $this->assertTrue(post_type_exists('magazine'), 'Verify if post type "magazine" has been registered');

    }

    /**
     * This method tests if in a empty database, the return of find() will be false
     */
    function test_models_empty_return(){

        $this->assertFalse(is_array($this->app->book->find()), 'Check if the model->find() method returns false (empty)');

    }

    /**
     * This method test the attributes of the PostObject
     */
    function test_models_return(){

        // ---- Arrange

        $book_author = 'J. R. R. Tolkien';
        $book_genre = 'Fantasy';

        // Creates the post
        $post_id = wp_insert_post( array(
            'post_type' => 'book',
            'post_title' => 'The Lord Of The Rings'
        ));

        // Add the custom fields
        update_post_meta($post_id, 'author', $book_author);
        update_post_meta($post_id, 'genre', $book_genre);

        // ---- Act
        $book = $this->app->book->find($post_id);

        // ---- Assert
        $this->assertTrue(is_int($post_id), 'Assert if wp_insert_post returns a post id');
        $this->assertInstanceOf('PostObject', $book, 'Assert if $book is a instance of PostObject');
        $this->assertEquals($book_author, $book->author, 'Asserts if the PostObject returns correctly the attribute author');
        $this->assertEquals($book_genre, $book->genre, 'Asserts if the PostObject returns correctly the attribute genre');

    }

}