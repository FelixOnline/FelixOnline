<?php
	/*
	 * Create a config.inc.php with the information below to run on a local dev machine
	 */

	$dbname = "DB_TABLE";
	$host = "localhost";
	$user = "DB_USER";
	$pass = "DB_PASSWORD";

	/*
	 * Change these urls to your local versions, e.g http://localhost/felix
	 */
	define('STANDARD_SERVER', 'felixonline.local');
	define('STANDARD_URL','http://felixonline.local/');
	define('BASE_URL','http://localhost/felix/');
	define('ADMIN_URL','http://localhost/felix/engine/');
	define('AUTHENTICATION_SERVER','localhost'); // authentication server
	define('AUTHENTICATION_PATH','http://localhost/felix/'); // authentication path
	define('RELATIVE_PATH','/felix'); // relative path from root

	define('PRODUCTION_FLAG', false); // if set to true css and js will be minified etc..
	define('LOCAL', true); // if true then site is hosted locally - don't use pam_auth etc.
	
	/* Initialise ezSQL database connection */
	$db = new ezSQL_mysqli();
	$db->quick_connect($user,$pass,$dbname,$host,'utf8');
	$safesql = new SafeSQL_MySQLi($db->dbh);

	/* Set settings for caching (turned off by defualt) */
	// Cache expiry
	$db->cache_timeout = 24; // Note: this is hours
	$db->use_disk_cache = true;
	$db->cache_dir = 'inc/ezsql_cache'; // Specify a cache dir. Path is taken from calling script
	$db->show_errors();

	/*
	 * To actually cache queries put this before any queries that you want to cache:
	 *
	 * $db->cache_queries = true;
	 *  // db queries go here
	 * $db->cache_queries = false;
	 *
	 * This will make sure that only the queries between the two commands will be cached.
	 * ***Only cache queries if they are unlikely to change within the cache timeout***
	 */

	/* turn off error reporting */
	//error_reporting(0);
	/* to turn on error reporting uncomment line: */
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
?>
