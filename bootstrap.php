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

/*
 * Exceptions
 */
require_once(BASE_DIRECTORY.'/exceptions/FrontendException.php');
require_once(BASE_DIRECTORY.'/exceptions/NotFoundException.php');
require_once(BASE_DIRECTORY.'/exceptions/GlueURLException.php');

foreach (glob(BASE_DIRECTORY.'/exceptions/*.php') as $filename) {
	require_once($filename);
}

require_once(BASE_DIRECTORY.'/core/glue.php');
$config = require_once(BASE_DIRECTORY.'/inc/config.inc.php');
require_once(BASE_DIRECTORY.'/vendor/felixonline/core/constants.php');
require_once(BASE_DIRECTORY.'/inc/const.inc.php');

/*
 * Models
 */
foreach (glob(BASE_DIRECTORY.'/core/*.php') as $filename) {
	require_once($filename);
}

// Initialize App
$app = new \FelixOnline\Core\App($config);

/* Initialise ezSQL database connection */
$db = new \ezSQL_mysqli();
$db->quick_connect(
	$config['db_user'],
	$config['db_pass'],
	$config['db_name'],
	$config['db_host'],
	$config['db_port'],
	'utf8'
);
$safesql = new \SafeSQL_MySQLi($db->dbh);

/* Set settings for caching (turned off by defualt) */
// Cache expiry
$db->cache_timeout = 24; // Note: this is hours
$db->use_disk_cache = true;
$db->cache_dir = 'inc/ezsql_cache'; // Specify a cache dir. Path is taken from calling script
$db->show_errors();

$app['db'] = $db;
$app['safesql'] = $safesql;

if (LOCAL) { // development connector
	// Initialize Akismet
	$connector = new \Riv\Service\Akismet\Connector\Test();
	$app['akismet'] = new \Riv\Service\Akismet\Akismet($connector);

	// Don't cache in local mode
	$app['cache'] = new \Stash\Pool();
}

if (!PRODUCTION_FLAG) {
	$app['cache'] = new \Stash\Pool();
}

// Initialize Sentry
$app['sentry'] = new \Raven_Client($app->getOption('sentry_dsn', NULL));

$app->run();
