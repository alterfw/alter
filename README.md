Alter
=====

Alter is a small MVC-like framework to develop Wordpress themes

Checkout the [Documentation](https://github.com/sergiovilar/alter/wiki).

## Installation

Enter in your theme folder and run:

	git clone git@github.com:sergiovilar/alter.git alter
	cd alter;
	git pull && git submodule init && git submodule update && git submodule status
	git submodule foreach --recursive git submodule update --init

### TODO
 - [x] Create helper class for templates
 - [ ] Create views
 - [x] PostObject returns the file url with type file
 - [ ] Create Controllers (it's necessary?)
 - [x] Create Options Page abstraction
 - [x] Create "default pages" abstraction (on models maybe?)
 - [x] Create interface for sending email via SMTP