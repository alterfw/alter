Alter
=====

[![Build Status](https://travis-ci.org/alterfw/alter.svg?branch=master)](https://travis-ci.org/alterfw/alter) [![Latest Stable Version](https://poser.pugx.org/alterfw/alter/v/stable)](https://packagist.org/packages/alterfw/alter) [![Total Downloads](https://poser.pugx.org/alterfw/alter/downloads)](https://packagist.org/packages/alterfw/alter) [![Latest Unstable Version](https://poser.pugx.org/alterfw/alter/v/unstable)](https://packagist.org/packages/alterfw/alter) [![License](https://poser.pugx.org/alterfw/alter/license)](https://packagist.org/packages/alterfw/alter)

A small framework that provides features to develop a real WordPress Application.

## Installation

Alter uses [Composer](http://getcomposer.org/) to manage dependencies, so you need to install Composer to use Alter.

Enter in the WordPress *themes* directory and run:

	composer create-project alterfw/alter <theme-name> --prefer-dist

## Documentation

You can read more about how Alter works in the [documentation page](http://alterfw.github.io/docs/).

## Contributing

This project doesn't have an styleguide yet but you should follow the existing code. 
Before create any pull requests make sure that all tests are passing.

### Development Environment

To setup de development environment first download [Docker](https://www.docker.com/) and create a virtual machine:

    docker-machine create --driver virtualbox default
    eval "$(docker-machine env default)"
    
Then run:

    docker-compose up
    
This will create a WordPress and a theme with Alter. 

