<?php

use FelixOnline\Core;

if(!defined('THEME_DIRECTORY')) define('THEME_DIRECTORY', dirname(__FILE__).'/');
if(!defined('THEME_URL')) define('THEME_URL', STANDARD_URL.'themes/modern/');

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
	array('normalize.min.css', 'foundation.min.css', 'felix.css', 'slick.css', 'slick-theme.css', 'rrssb.css', 'glyphicons.css', 'glyphicons-social.css'), 
	/* JS files */
	array('jquery.min.js', 'foundation.min.js', 'fastclick.js', 'jquery.cookie.js', 'jquery.placeholder.js', 'slick.min.js', 'jquery-visible.js', 'script.js', 'rrssb.min.js')
);

?>
