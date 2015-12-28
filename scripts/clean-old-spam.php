<?php
/**
 * Delete spam older than 30 days
 */

if(php_sapi_name() !== 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Comment', 'comment');
$manager->filter("spam = 1")
		->filter("timestamp < DATE_SUB(NOW(), INTERVAL 30 DAY);");

$values = $manager->values();

if(!$values) {
	echo "Nothing to do.\n";
	return;
}

$app = App::getInstance();

foreach($values as $record) {
	$id = $record->getId();

	$app['db']->query("DELETE FROM akismet_log WHERE comment_id = ".$id);
	$app['db']->query("DELETE FROM comment WHERE id = ".$id);
}

echo "All done.\n";
