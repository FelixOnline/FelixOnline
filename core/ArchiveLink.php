<?php
	class ArchiveLink {
		private $dba;

		function __construct() {
			global $dba;
			global $db;

			$this->db = $db;

			$dbaname = ARCHIVE_DATABASE;

			$dba = new ezSQL_mysqli();
			$dba->quick_connect(
				$this->db->dbuser,
				$this->db->dbpassword,
				$dbaname,
				$this->db->dbhost,
				3306,
				'utf8'
			);
			$this->safesql = new SafeSQL_MySQLi($dba->dbh);
			$dba->cache_timeout = 24; // Note: this is hours
			$dba->use_disk_cache = true;
			$dba->cache_dir = 'inc/ezsql_cache'; // Specify a cache dir. Path is taken from calling script
			$dba->show_errors();

			$this->dba = $dba;
		}

		public function getLatestForPublication($pubid) {
			try {
				$issue_manager = new \FelixOnline\Core\IssueManager();

				$issues = $issue_manager->getLatestPublicationIssue($pubid);

				$issue = end($issues);
				return $issue;
			} catch(Exception $e) {
				return false;
			}
		}
	}