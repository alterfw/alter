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
    function test_models_post_type_exists(){

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
    function test_models_find(){

        // ---- Arrange

        $book_author = 'J. R. R. Tolkien';
        $book_genre = 'Fantasy';

        // Creates the post
        $post_id = $this->factory->post->create( array(
            'post_type' => 'book',
            'post_title' => 'The Lord Of The Rings'
        ));

        // Add the custom fields
        update_post_meta($post_id, 'author', $book_author);
        update_post_meta($post_id, 'genre', $book_genre);

        // ---- Act
        $book = $this->app->book->findById($post_id);

        // ---- Assert
        $this->assertTrue(is_int($post_id), 'Assert if wp_insert_post returns a post id');
        $this->assertInstanceOf('PostObject', $book, 'Assert if $book is a instance of PostObject');
        $this->assertEquals($book_author, $book->author, 'Asserts if the PostObject returns correctly the attribute author');
        $this->assertEquals($book_genre, $book->genre, 'Asserts if the PostObject returns correctly the attribute genre');

    }

	/**
	 * This method tests the return of multiple results from a model
	 */
	function test_models_multiple_results(){

		// ---- Arrange
		$this->factory->post->create_many(10, array(
			'post_type' => 'book'
		));

		// ---- Act
		$books = $this->app->book->find();

		// ---- Assert
		$this->assertTrue(true, 'Asserts if the book->find() returns an array');
		$this->assertInstanceOf('PostObject', $books[0], 'Asserts if is an array of PostObject');

	}

	/**
	 * This method tests the return of multiple results with limit from a model
	 */
	function test_models_find_with_limit(){

		// ---- Arrange
		$this->factory->post->create_many(10, array(
			'post_type' => 'book'
		));

		// ---- Act
		$books = $this->app->book->find(5);
		$books_wpquery = $this->app->book->find(array('posts_per_page' => 5));

		// ---- Assert
		$this->assertTrue(is_array($books), 'Assert if book->find(5) returns an array');
		$this->assertEquals(count($books), 5, 'Assert if the lenght is correct');
		$this->assertEquals(count($books_wpquery), 5, 'Assert if the lenght is correct');

	}

	/**
	 * This method tests the paginate method of the model
	 */
	function test_models_paginate(){

		// ---- Arrange
		$this->factory->post->create_many(100, array(
			'post_type' => 'book'
		));

		// ---- Act
		$books_page1 = $this->app->book->paginate(10);
		$books_page2 = $this->app->book->paginate(10, 2);

		// ---- Assert
		$this->assertTrue(is_array($books_page1), 'Assert if book->find(5) returns an array');
		$this->assertEquals(count($books_page1), 10, 'Assert if the lenght is correct');
		$this->assertInstanceOf('PostObject', $books_page1[0], 'Asserts if is an array of PostObject');

		$this->assertTrue(is_array($books_page2), 'Assert if book->find(5) returns an array');
		$this->assertEquals(count($books_page2), 10, 'Assert if the lenght is correct');
		$this->assertInstanceOf('PostObject', $books_page2[0], 'Asserts if is an array of PostObject');

		$this->assertNotEquals($books_page1[0]->ID, $books_page2[0]->ID, 'Asserts if the two arrays are not equals');

	}

	/**
	 * This method tests the result of finding by a slug on the model
	 */
	function test_models_find_by_slug(){

		// ---- Arrange
		$title = 'The Lord Of The Rings';

		$this->factory->post->create( array(
			'post_type' => 'book',
			'post_title' => $title
		));

		// ---- Act
		$book = $this->app->book->findBySlug('the-lord-of-the-rings');

		// ---- Assert
		$this->assertInstanceOf('PostObject', $book);
		$this->assertEquals($book->title, $title);

	}

    /**
     * This method tests the reflection api with Alter and custom fields
     */
    function test_models_find_automagic_custom_field(){

        // ---- Arrange
        $post_id = $this->factory->post->create( array(
            'post_type' => 'book'
        ));

        // Add the custom fields
        update_post_meta($post_id, 'author', 'J. R. R. Tolkien');
        update_post_meta($post_id, 'genre', 'Fantasy');

        // ---- Act
        $book = $this->app->book->findByGenre('Fantasy');

        // ---- Assert
        $this->assertTrue(is_array($book));
        $this->assertInstanceOf('PostObject', $book[0]);
        $this->assertEquals($book[0]->genre, 'Fantasy');

    }

    /**
     * This method tests the reflection api with alter and default allowed fields
     */
    function test_models_find_automagic_author(){

        // ---- Arrange
        $post_id = $this->factory->post->create_many(100, array(
            'post_type' => 'book',
            'post_author' => 1
        ));

        // ---- Act
        $book = $this->app->book->findByAuthor(1);

        // ---- Assert
        $this->assertTrue(is_array($book));
        $this->assertInstanceOf('PostObject', $book[0]);

    }


	/**
	 * This method tests the result of finding by a taxonomy on the model
	 */
	function test_models_find_by_taxonomy(){

		// TODO: Register some taxonomies on example-theme

	}

}