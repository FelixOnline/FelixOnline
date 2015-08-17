<?php
/**
 * Convert text fields to use Sir Trevor format
 */

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
libxml_use_internal_errors(true);

$res = $app['db']->get_results(
	"SELECT 
		id, content
	FROM `text_story`
	WHERE converted = 0");

foreach($res as $row) {
	echo "Converting record ".$row->id."... ";

	$text = $row->content;

	try {
		$converter = new \Sioen\Converter();
		$json = $converter->toJson($text);

		$object = new \FelixOnline\Core\Text($row->id);
		$object->setContent($json);
		$object->setConverted(1);
		$object->save();
		echo "DONE\n";
	} catch(\Exception $e) {
		echo "FAILED - ".$e->getMessage().". ";
	}
}

echo "All done.\n";
