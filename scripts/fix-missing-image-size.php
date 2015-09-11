<?php
/**
 * Fix missing image width/height
 */

if(php_sapi_name() === 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Image', 'image');
$manager->filter("width = 0", array(), array(array("height = 0", array())));

$values = $manager->values();

if(!$values) {
	echo "Nothing to do.\n";
	return;
}

foreach($values as $record) {
	echo "Doing ".$record->getUri()."... ";

	if(!file_exists('../'.$record->getUri())) {
		echo "NOT FOUND\n";

		continue;
	}

	$sizeInfo = get_image_size('../'.$record->getUri());

	$record->setWidth($sizeInfo['width']);
	$record->setHeight($sizeInfo['height']);
	$record->save();

	echo "DONE\n";
}

echo "All done.\n";
