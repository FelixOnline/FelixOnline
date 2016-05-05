<?php
/**
 * Convert text fields to use Sir Trevor format
 */

if(php_sapi_name() !== 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

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
		$json = $converter->toJson(str_replace("&nbsp;<a", " <a", str_replace("</a>&nbsp;", "</a> ", $text))); // Fix for some odd behaviour from the old formatter
		$object = new \FelixOnline\Core\Text($row->id);
		$object->setContent($json);
		$object->setConverted(1);
		$object->save();
		echo "DONE\n";
	} catch(\Exception $e) {
		echo "FAILED - ".$e->getMessage()."\n";
	}
}

echo "All done.\n";
exit(0);