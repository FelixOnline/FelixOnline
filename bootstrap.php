<?php
/* 
 * Sets up the Felix Online environment 
 */

date_default_timezone_set('Europe/London');

// define current working directory
if(!defined('BASE_DIRECTORY')) define('BASE_DIRECTORY', dirname(__FILE__));
if(!defined('CACHE_DIRECTORY')) define('CACHE_DIRECTORY', BASE_DIRECTORY.'/cache/');

// Composer
require BASE_DIRECTORY.'/vendor/autoload.php';

require_once(BASE_DIRECTORY.'/inc/ez_sql_core.php');
require_once(BASE_DIRECTORY.'/inc/ez_sql_mysqli.php');
require_once(BASE_DIRECTORY.'/inc/SafeSQL.class.php');
require_once(BASE_DIRECTORY.'/glue.php');
$config = require_once(BASE_DIRECTORY.'/inc/config.inc.php');
require_once(BASE_DIRECTORY.'/inc/const.inc.php');
require_once(BASE_DIRECTORY.'/inc/functions.inc.php'); // TODO move to utilities
require_once(BASE_DIRECTORY.'/inc/validator.inc.php');
require_once(BASE_DIRECTORY.'/inc/is_email.inc.php');

/*
 * Models
 */
require_once(BASE_DIRECTORY.'/core/baseModel.class.php');
require_once(BASE_DIRECTORY.'/core/BaseManager.php');
require_once(BASE_DIRECTORY.'/core/user.class.php');
require_once(BASE_DIRECTORY.'/core/frontpage.class.php');
foreach (glob(BASE_DIRECTORY.'/core/*.php') as $filename) {
	require_once($filename);
}

//require_once(BASE_DIRECTORY.'/inc/authentication.php');
require_once(BASE_DIRECTORY.'/inc/rss.inc.php');

// Initialize Akismet
if (LOCAL) { // development connector
	$connector = new \RzekaE\Akismet\Connector\Test();
} else {
	$connector = new \RzekaE\Akismet\Connector\Curl();
}
$akismet = new \RzekaE\Akismet\Akismet($connector);

// Initialize App
$app = new \FelixOnline\Core\App($config);

$app['db'] = $db;

$app['safesql'] = $safesql;

$app['env'] = \FelixOnline\Core\Environment::getInstance();
$app['currentuser'] = new \FelixOnline\Core\CurrentUser();

$app->run();
