# Registerables
A collection of Abstract Classes for creating common WordPress fixtires which need registering.

* Post Types
* Taxonomies
* Metaboxes
* WP_Ajax Call


![alt text](https://img.shields.io/badge/Current_Version-0.1.0-yellow.svg?style=flat " ") 
[![Open Source Love](https://badges.frapsoft.com/os/mit/mit.svg?v=102)](https://github.com/ellerbrock/open-source-badge/)

![alt text](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat " ") 
![alt text](https://img.shields.io/badge/PHPUnit-PASSING-brightgreen.svg?style=flat " ") 
![alt text](https://img.shields.io/badge/PHCBF-WP_Extra-brightgreen.svg?style=flat " ") 


For more details please visit our docs.
https://app.gitbook.com/@glynn-quelch/s/pinkcrab/


## Version ##
**Release 0.1.0**


## Why? ##
Creating many of WordPress's internal fixtures can sometimes be very verbose with large arrays of values which do not throw errors if incorrect. 

The PinkCrab Registerables module provides a small selection of Abstract Classes which can be extended and added to the registration system.  

## Example ##

Creates a simple post type.

````php
use PinkCrab\Registerables\Post_Type;

class Basic_CPT extends Post_Type {

	public $key      = 'basic_cpt';
	public $singular = 'Basic';
	public $plural   = 'Basics';
}
````

Creates a flat taxonomy for the **Post** Post Type.

````php
use PinkCrab\Registerables\Taxonomy;

class Basic_Tag_Taxonomy extends Taxonomy {
	public $slug         = 'basic_tag_tax';
	public $singular     = 'Basic Tag Taxonomy';
	public $plural       = 'Basic Tag Taxonomies';
	public $description  = 'The Basic Tag Taxonomy.';
	public $hierarchical = false;
	public $object_type = array( 'post' );
}
````

## Testing ##

### PHP Unit ###
If you would like to run the tests for this package, please ensure you add your database details into the test/wp-config.php file before running phpunit.
````bash
$ phpunit
````
````bash 
$ composer test
````

### PHP Stan ###
The module comes with a pollyfill for all WP Functions, allowing for the testing of all core files. The current config omits the Dice file as this is not ours. To run the suite call.
````bash 
$ vendor/bin/phpstan analyse src/ -l8 
````
````bash 
$ composer analyse
````


## License ##

### MIT License ###
http://www.opensource.org/licenses/mit-license.html  

## Change Log ##
0.1.0 - Moved to composer.
