<?php
/**
 * Clear cache
 */

if(php_sapi_name() === 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

$app = \FelixOnline\Core\App::getInstance();

$app['cache']->flush();

echo "All done.\n";
