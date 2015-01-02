<?php
/*
 * Classic theme
 * Author: Jonathan Kim
 * Date: 30/12/2011
 */

use FelixOnline\Core;

if(!defined('THEME_DIRECTORY')) define('THEME_DIRECTORY', dirname(__FILE__));
if(!defined('THEME_NAME')) define('THEME_NAME', '2014');
if(!defined('THEME_URL')) define('THEME_URL', STANDARD_URL.'themes/'.THEME_NAME.'/');

global $hooks;

/*
 * Load in theme specific functions
 */
require_once(THEME_DIRECTORY.'/core/functions.php');

/*
 * Set default site wide resources
 */
$this->resources = new Core\ResourceManager(
	/* CSS files */
	array('foundation.css', 'felix.css', '../slick/slick.css'), 
	/* JS files */
	array('vendor/jquery.js', 'foundation.min.js', 'foundation/foundation.reveal.js', '../slick/slick.js', 'script.js')
);

?>
