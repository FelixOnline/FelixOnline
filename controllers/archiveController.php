<?php
/*
 * Archive controller
 */
class ArchiveController extends BaseController {
	private $currentyear;
	private $year;

	function __construct() {
		parent::__construct();
		global $dba;
		$dbaname = 'media_felix_archive';

		$dba = new ezSQL_mysqli();
		$dba->quick_connect(
			$this->db->dbuser,
			$this->db->dbpassword,
			$dbaname,
			$this->db->dbhost,
			'utf8'
		);
		$safesql = new SafeSQL_MySQLi($dba->dbh);
		$dba->cache_timeout = 24; // Note: this is hours
		$dba->use_disk_cache = true;
		$dba->cache_dir = 'inc/ezsql_cache'; // Specify a cache dir. Path is taken from calling script
		$dba->show_errors();

		$this->dba = $dba;
	}

	function GET($matches) {
		global $timing;
		if(array_key_exists('id', $matches) && array_key_exists('download', $matches)) { // viewing a specific issue
			$issue = new Issue($matches['id']);
			$file = BASE_DIRECTORY.'/archive/'.$issue->getFile();
			$filename = $issue->getFileName();
			
			// Make sure the files exists, otherwise we are wasting our time
			if (!file_exists($file)) {
				throw new NotFoundException("Issue doesn't exists on server");
			}

			$this->serveFile($file, $filename, 'application/pdf');
		} else if(array_key_exists('id', $matches)) {
			echo 'Issue page';
		} else {
			$this->currentyear = date('Y');
			if(array_key_exists('decade', $matches)) {
				$this->year = $matches['decade'];
			} else if(array_key_exists('year', $matches)) {
				$this->year = $matches['year'];
			} else {
				$this->year = $this->currentyear;
			}

			// get latest issue year TODO: cache
			$sql = "SELECT 
						MAX(YEAR(PubDate)) 
					FROM Issues";
			$end = $this->dba->get_var($sql);

			$start = 1950;
			$currentdecade = array();
			$decades = $this->getDecades($start, $end, $currentdecade);

			// 1949 edge case
			array_unshift($decades, array('final' => '1949'));
			if($this->year == '1949') { // if selected year is 1949
				$decades[0]['selected'] = true;
				$currentdecade = $decades[0];
			}
			
			// get issues 
			$issues = $this->getIssues($this->year);

			// 2011 Felix Daily
			// TODO: make this nicer
			$daily = array();
			if ($this->year == '2011') {
				$daily = $this->getIssues($this->year, 3);
			}

			$this->theme->appendData(array(
				'decades' => $decades,
				'currentdecade' => $currentdecade,
				'year' => $this->year,
				'issues' => $issues,
				'daily' => $daily
			));

			$this->theme->render('archive');
		}
	}

	/*
	 * Private: Return list of decades from a start and end year
	 *
	 * Returns array
	 */
	private function getDecades($start, $end, &$currentdecade) {
		$decades = array();
		for($i = $start; $i <= $end; $i = $i+10) {
			$final = $i + 9;
			if($final > $this->currentyear) {
				$final = $this->currentyear;
			}
			if($this->year >= $i && $this->year <= $final) {
				$selected = true;
			} else {
				$selected = false;
			}
			$info = array(
				'begin' => $i,
				'final' => $final,
				'selected' => $selected
			);
			array_push($decades, $info);

			if($selected) $currentdecade = $info;
		}
		return $decades;
	}

	/*
	 * Private: Serve files in
	 *
	 * Credit: http://stackoverflow.com/a/4451376/1165117
	 */
	private function serveFile($file, $filename, $contenttype = 'application/octet-stream') {
		// Avoid sending unexpected errors to the client - we should be serving a file,
		// we don't want to corrupt the data we send
		@error_reporting(0);

		// Send standard headers
		header("Content-Type: $contenttype");
		header("Content-Length: $filesize");
		header('Content-Disposition: inline; filename="'.$filename.'"');
		header('Accept-Ranges: bytes');

		// if requested, send extra headers and part of file...
		readfile($file);

		// Exit here to avoid accidentally sending extra content on the end of the file
		exit;
	}

	/*
	 * Public static: Get issues
	 *
	 * $year    - year to get publications from 
	 * $pub     - id of publication type [default = 1 (Felix)]
	 *
	 * Return array of issue objects
	 */
	public function getIssues($year, $pub = 1) {
		$sql = "SELECT
					i.id AS id
				FROM Issues as i
				WHERE YEAR(PubDate) = '".$year."'
				AND i.PubNo = ".$pub."
				ORDER BY i.id ASC";
		$result = $this->dba->get_results($sql);

		$issues = array();
		foreach($result as $obj) {
			$issue = new Issue($obj->id);
			$issues[] = $issue;
		}
		return $issues;
	}
}
