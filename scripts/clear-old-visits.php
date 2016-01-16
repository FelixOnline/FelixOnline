<?php
/**
 * Delete visits older than 3 months
 */

if(php_sapi_name() !== 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

$app = \FelixOnline\Core\App::getInstance();

$app['db']->query("DELETE FROM article_visit WHERE < DATE_SUB(NOW(), INTERVAL 3 MONTH)");

echo "All done.\n";
