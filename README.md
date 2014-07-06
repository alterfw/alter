Alter
=====

[![Build Status](https://travis-ci.org/alterfw/alter.svg?branch=master)](https://travis-ci.org/alterfw/alter)

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

## Contributing

Fell free to help to improve Alter, you can make pull requests or improve the [documentation](https://github.com/alterfw/docs) also.

If you make a Pull Request of a new feature, make sure to link the [documentation](https://github.com/alterfw/docs) Pull Request and to write the respective tests.

### Development Environment

We use [Vagrant](http://vagrantup.com/) to create the Alter Development Environment. To setup, follow this instructions:

Clone our fork of [Vagrantpress](http://vagrantpress.org/):

	git clone https://github.com/alterfw/vagrantpress

And start the virtual machine:

	cd vagrantpress;
	vagrant up	

So, with the Vagrant VM on, you need to replace the alter dependency (installed over composer) by your clone of the repository;

```shell
vagrant ssh
cd /vagrant/wordpress/wp-content/themes/example-theme-master/vendor/alterfw
rm -rf alter
git clone git@github.com:alterfw/alter.git
```	

### Running the tests

To run the tests you need first to setup the [development environment](#development-environment).

After this you can run the tests simply:

	cd /vagrant/wordpress/wp-content/themes/example-theme-master/vendor/alterfw/alter
	phpunit


