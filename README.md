Alter
=====

A small framework that provides the way to develop model-based Wordpress themes

### How this works?

Alter provides the way to develop model-based Wordpress themes, using models you can easily create post types, custom meta-boxes, and access the post properties

```php
<?php
class carModel extends AppModel{
	
	public $fields = array(

		// Default Wordpress fields
		'editor'	=> true,
		'title'		=> true,
		'thumbnail'	=> true,

		// Custom fields
		'manufacturer'	=> array(
			'label'		=> 'Manufacturer',
			'type'		=> 'text'	
		)

	)

}
```

### Stop using Wordpress functions

The Wordpress-way to get post properties like thumbnails and custom post fields is very painful, with Alter we made this simple.

```php
<?php

foreach($app->car->find() as $car){
	
	echo $car->title;
	echo $car->manufacturer;
	echo "<img src='". $car->thumbnail->medium ."' />";

}
```

## Installation

Enter in your theme folder and run:

	git clone git@github.com:alterfw/alter.git alter

Then install Alter dependencies:	
	
	cd alter; composer install	

After this, add this line to your **functions.php**:

```php
require_once "alter/core/main.php";
```

## Documentation

Checkout our [documentation](http://alter-framework.readthedocs.org/en/latest/index.html) on readthedocs.org.

You can also contribute with the documentation in the [separated repository](https://github.com/alterfw/docs).
