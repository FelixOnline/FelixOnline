<?php
/*
 * Classic theme
 * Author: Jonathan Kim
 * Date: 30/12/2011
 */

//if(!defined('THEME_DIRECTORY')) define('THEME_DIRECTORY', dirname(__FILE__));

/*
 * Set default site wide resources
 */
$this->resources = new ResourceManager(
    /* CSS files */
    array(
        'style' => 'style.css'
    ), 
    /* JS files */
    array(
        'jquery' => 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js',
        'script' => 'script.js'
    ),
    $this // give the resource manager the theme object
);

?>
