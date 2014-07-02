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

Create a `composer.json` for your theme:

	composer init

Then add to your `composer.json`:
	
	"minimum-stability": "dev",
	"require": {
        "alterfw/alter": "0.1.x"
    }    

Run composer:

	composer install

After this, add this line to your **functions.php**:

```php
require_once "vendor/autoload.php";
```

## Documentation

Checkout our [documentation](http://alter-framework.readthedocs.org/en/latest/index.html) on readthedocs.org.

You can also contribute with the documentation in the [separated repository](https://github.com/alterfw/docs).
