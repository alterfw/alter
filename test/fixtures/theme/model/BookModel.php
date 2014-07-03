<?php

/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 04/05/14
 * Time: 09:03 PM
 */
class BookModel extends AppModel
{

	public $plural = "Books";
	public $icon = "dashicons-location-alt";

	//public $taxonomies = array('categoria_local');

	public $fields = array(

		'title' => true

	);

} 