<?php
	/*
	 * Site constants
	 * 
	 * To change constants define them in config.inc.php
	 */

	/* SYSTEM */
	if(!defined('STANDARD_URL'))					define('STANDARD_URL','http://felixonline.co.uk/'); // standard site url
	if(!defined('ADMIN_URL'))					 	define('ADMIN_URL','http://felixonline.co.uk/engine/'); // url of engine page
	if(!defined('PRODUCTION_FLAG'))					define('PRODUCTION_FLAG', true); // if set to true css and js will be minified etc.
	if(!defined('LOCAL'))							define('LOCAL', false); // if true then site is hosted locally - don't use pam_auth etc.
	if(!defined('DEBUG_MODE'))						define('DEBUG_MODE', false); // if true then show debug info on errors - useful for debugging errors without disabling authentication
	if(!defined('AUTHENTICATION_SERVER'))			define('AUTHENTICATION_SERVER','dougal.union.ic.ac.uk'); // authentication server
	if(!defined('AUTHENTICATION_PATH'))				define('AUTHENTICATION_PATH','https://dougal.union.ic.ac.uk/media/felix/'); // authentication path
