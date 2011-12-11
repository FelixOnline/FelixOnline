<?php
    /*
     * Create a config.inc.php with the information below to run on a local dev machine
     */

    $dbname = "DB_TABLE";
    $host = "localhost";
    $user = "DB_USER";
    $pass = "DB_PASSWORD";
    $cid = mysql_connect($host,$user,$pass);
    $dbok = mysql_select_db($dbname,$cid);

    /* Initialise ezSQL database connection */
    $db = new ezSQL_mysql();
    $db->quick_connect($user,$pass,$dbname,$host);

    /* Set settings for caching (turned off by defualt) */
	// Cache expiry
	$db->cache_timeout = 24; // Note: this is hours
	$db->use_disk_cache = true;
	$db->cache_dir = 'inc/ezsql_cache'; // Specify a cache dir. Path is taken from calling script

    /* Forces charset to be utf8 */
    mysql_set_charset('utf8',$db->dbh);

    /* turn off error reporting */
    //error_reporting(0);
    /* to turn on error reporting uncomment line: */
    error_reporting(E_ERROR | E_WARNING | E_PARSE);

    /*
     * Change these urls to your local versions, e.g http://localhost/felix
     */
    define('STANDARD_URL','http://localhost/felix/');
    define('BASE_URL','http://localhost/felix/');
    define('ADMIN_URL','http://localhost/felix/engine/');
    define('AUTHENTICATION_SERVER','localhost'); // authentication server
    define('AUTHENTICATION_PATH','http://localhost/felix/'); // authentication path

    define('PRODUCTION_FLAG', false); // if set to true css and js will be minified etc..
    define('LOCAL', true); // if true then site is hosted locally - don't use pam_auth etc. 
?>
