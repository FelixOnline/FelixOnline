<?php
/*
 * Issue Class
 *
 * Fields:
 *      id          - Issue id
 *      PubDate     - Date of publish (YYYY-MM-DD)
 *      IssueNo     - Issue number
 *      PubNo       - Publication number (references publication table)
 *      Description - Text description of publication
 *      Year        - Temporary
 */
class Issue extends BaseModel {
	protected $filters = array();

	function __construct($id = NULL) {
		global $dba;
		$this->dba = $dba;
		$this->safesql = new SafeSQL_MySQLi($dba->dbh);
		if($id !== NULL) {
			$sql = $this->safesql->query("SELECT
						`id`,
						UNIX_TIMESTAMP(`PubDate`) as pub_date,
						`IssueNo`,
						`PubNo`,
						`Description`,
						`Year`
					FROM `Issues`
					WHERE id=%i", array($id));
			$this->filters = array(
				'IssueNo' => 'issue_no',
				'PubNo' => 'pub_no'
			);
			parent::__construct($this->dba->get_row($sql), 'Issue', $id);
			return $this;
		} else {
			// initialise new issue
		}
	}

	/*
	 * Public: Get URL
	 *
	 * Returns string
	 */
	public function getURL() {
		$url = STANDARD_URL.'issuearchive/issue/'.$this->getId();
		return $url;
	}

	/*
	 * Public: Get download URL
	 *
	 * Returns string
	 */
	public function getDownloadURL() {
		$url = $this->getURL().'/download';
		return $url;
	}

	/*
	 * Public: Get thumbnail
	 * Gets thumbnail filename
	 *
	 * TODO: clean up
	 *
	 * Returns string
	 */
	public function getThumbnail() {
		$thumb = substr($this->getFile(),8,(strlen($this->getFile())-11)).'png';
		return $thumb;
	}

	/*
	 * Public: Get thumbnail url
	 *
	 * Returns string
	 */
	public function getThumbnailURL() {
		$url = 'http://felixonline.co.uk/archive/thumbs/'.$this->getThumbnail();
		return $url;
	}

	/**
	 * Public: Get file
	 *
	 * Returns string
	 */
	public function getFile() {
		if (!array_key_exists('file', $this->fields)) {
			$sql = $this->safesql->query(
					"SELECT
						FileName
					FROM Files
					WHERE PubNo = %i
					AND IssueNo = %i",
					array(
						$this->getPubNo(),
						$this->getIssueNo()
					));
			$result = $this->dba->get_row($sql);
			$this->fields['file'] = $result->FileName;
		}
		return $this->fields['file'];
	}

	/**
	 * Public: Get file name
	 *
	 * Returns string
	 */
	public function getFileName() {
		if (!array_key_exists('file_name', $this->fields)) {
			$file = $this->getFile();
			preg_match('/\/(\w+)_[A-Z]/', $file, $matches);
			$filename = $matches[1] . '.pdf';
			$this->fields['file_name'] = $file_name;
		}
		return $this->fields['file_name'];
	}
}

