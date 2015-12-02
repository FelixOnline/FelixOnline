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

foreach($values as $record) {
	$id = $record->getId();

	// clean akismet log - this actually cascades but we can do it manually to be sure
	$akManager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\AkismetLog', 'akismet_log');
	$akManager->filter("comment_id = %i", array($id));

	$akValues = $akManager->values();

	if($akValues) {
		foreach($akValues as $akRecord) {
			$akRecord->delete();
		}
	}

	$record->delete();
}

echo "All done.\n";
