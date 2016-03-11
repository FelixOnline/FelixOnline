<?php
/**
 * Export database and sanitise emails and other sensitive information
 */

if(php_sapi_name() !== 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

$config = require dirname(__FILE__) . '/../inc/config.inc.php';

class FelixExporter extends \FelixOnline\Exporter\MySQLExporter
{
	protected $count = 1;
	function processTable($table)
	{
		if ($table == 'akismet_log') {
			return false;
		}

		if ($table == 'advert') {
			return false;
		}

		if ($table == 'advert_category') {
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

		if ($table == 'archive_file') {
			return false;
		}

		if ($table == 'archive_issue') {
			return false;
		}

		if ($table == 'archive_publication') {
			return false;
		}

		if ($table == 'email_validation') {
			return false;
		}

		if ($table == 'cookies') {
			return false;
		}

		if ($table == 'login') {
			return false;
		}

		if ($table == 'audit_log') {
			return false;
		}

		return $table;
	}

	function processRow($row, $table)
	{
		if ($row['deleted'] == 1) {
			return false;
		}

		if ($table == 'category') {
			if ($row['secret'] == 1) {
				return false;
			}
		}

		if ($table == 'article') {
			// Check if in a secret category
			$res = $this->db->query(
				"SELECT 
					secret 
				FROM `category` 
				WHERE category='".$row['category']."'");

			if($res) {
				$secret = $res->fetch_row()[0];
			} else {
				$secret = 0;
			}

			if($secret == 1) {
				return false;
			}

			if ($row['hidden'] == 1) {
				return false;
			}

			if ($row['published'] == 0) {
				return false;
			}
		}

		if ($table == 'link') {
			if ($row['active'] == 0) {
				return false;
			}
		}

		if ($table == 'comment') {
			if ($row['spam'] == 1) {
				return false;
			}

			$row['email'] = 'test-'.$this->count.'@example.com';
			$row['ip'] = '0.0.0.0';
			$row['referer'] = 'Anonymised';
			$row['useragent'] = 'Anonymised';
			$this->count++;
		}

		if ($table == 'user') {
			// Check if user has any articles
			$res = $this->db->query(
				"SELECT 
					COUNT(article.id) 
				FROM `article` 
				INNER JOIN `article_author` 
					ON (article.id=article_author.article) 
				WHERE article_author.author='".$row['user']."'
				AND published < NOW()");

			if($res) {
				$count = $res->fetch_row()[0];
			} else {
				$count = 0;
			}

			if ($count > 0) {
				$row['ip'] = '0.0.0.0';
				$row['facebook'] = NULL;
				$row['twitter'] = NULL;
				$row['websitename'] = NULL;
				$row['websiteurl'] = NULL;
				$row['info'] = "[]";
				$row['visits'] = 0;

				if ($row['email'] ) {
					$row['email'] = "test-".$this->count."@example.com";
					$this->count++;
				}
			} else {
				return false;
			}
		}

		if ($table == 'comment_like') {
			$row['ip'] = '0.0.0.0';
			$row['user_agent'] = 'Anonymised';
		}

		if ($table == 'polls_response') {
			$row['ip'] = '0.0.0.0';
			$row['useragent'] = 'Anonymised';
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
		if ($time - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days - get older stuff from ICU backup
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
