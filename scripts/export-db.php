<?php
/**
 * Export database and sanitise emails and other sensitive information
 */

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../vendor/autoload.php';
require dirname(__FILE__) . '/../inc/ez_sql_core.php'; // TODO REMOVE
require dirname(__FILE__) . '/../inc/ez_sql_mysqli.php'; // TODO REMOVE
require dirname(__FILE__) . '/../inc/SafeSQL.class.php'; // TODO REMOVE

$config = require dirname(__FILE__) . '/../inc/config.inc.php';

class FelixExporter extends \FelixOnline\Exporter\MySQLExporter
{
	protected $count = 1;
	function processTable($table)
	{
		if ($table == 'akismet_log') {
			return false;
		}

		if ($table == 'article_visit') {
			return false;
		}

		if ($table == 'api_keys') {
			return false;
		}

		if ($table == 'api_log') {
			return false;
		}

		if ($table == 'comment_spam') {
			return false;
		}

		if ($table == 'cookies') {
			return false;
		}

		if ($table == 'ffs_completers') {
			return false;
		}

		if ($table == 'ffs_responses') {
			return false;
		}

		if ($table == 'login') {
			return false;
		}

		if ($table == 'optimise') {
			return false;
		}

		if ($table == 'preview_email') {
			return false;
		}

		if ($table == 'text_story_bkp') {
			return false;
		}

		if ($table == 'thephig_users') {
			return false;
		}

		return $table;
	}

	function processRow($row, $table)
	{
		if ($table == 'comment') {
			if ($row['spam'] == 1) {
				return false;
			}

			$row['ip'] = NULL;
		}

		if ($table == 'user') {
			// Check if user has any articles
			$res = $this->db->query(
				"SELECT 
					COUNT(id) 
				FROM `article` 
				INNER JOIN `article_author` 
					ON (article.id=article_author.article) 
				WHERE article_author.author='".$row['user']."'
				AND published < NOW()");
			$count = $res->fetch_row()[0];

			if ($count > 0) {
				$row['ip'] = '0.0.0.0';
				$row['facebook'] = NULL;
				$row['twitter'] = NULL;
				$row['websitename'] = NULL;
				$row['websiteurl'] = NULL;
				$row['visits'] = 0;

				if ($row['email'] ) {
					$row['email'] = "test-".$this->count."@example.com";
					$this->count++;
				}
			} else {
				return false;
			}
		}

		return $row;
	}
}

$orig_directory = getcwd();

// change to backup directory
$backup_directory = realpath(dirname(__FILE__) . '/../backups');
chdir($backup_directory);

// Remove old backups
$files = glob("*.sql.zip");
$time = time();

foreach ($files as $file) {
	if (is_file($file) && !is_link($file)) {
		if ($time - filemtime($file) >= 60 * 60 * 24 * 14) { // 14 days
			unlink($file);
		}
	}
}

// Start export
$db_file = $config['db_name'] . '-' . date("Y-m-d") . '.sql';

$exporter = new FelixExporter(array(
	'db_name' => $config['db_name'],
	'db_user' => $config['db_user'],
	'db_pass' => $config['db_pass'],
	'file' => $db_file,
));

$exporter->run();

// Zip file
exec(sprintf("zip %s %s", $db_file . '.zip', $db_file), $output, $return);

if ($return !== 0) {
	throw new Exception(implode("\n", $output));
}

// Remove original sql file
unlink($db_file);

// create symlink
$symlink = 'latest.sql.zip';
if (file_exists($symlink)) {
	unlink($symlink);
}

symlink($db_file . '.zip', $symlink);

// Move back to original directory
chdir($orig_directory);
