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

require_once(BASE_DIRECTORY.'/glue.php');
require_once(BASE_DIRECTORY.'/inc/config.inc.php');
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

$app['db'] = $db;
$app['safesql'] = $safesql;

if (LOCAL) { // development connector
	// Initialize Akismet
	$connector = new \Riv\Service\Akismet\Connector\Test();
	$app['akismet'] = new \Riv\Service\Akismet\Akismet($connector);

	// Initialize email
	$transport = \Swift_NullTransport::newInstance();
	$app['email'] = \Swift_Mailer::newInstance($transport);

	// Don't cache in local mode
	$app['cache'] = new \Stash\Pool();
}

// Initialize Sentry
$app['sentry'] = new \Raven_Client($app->getOption('sentry_dsn', NULL));

$app->run();
