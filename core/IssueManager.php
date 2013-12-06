<?php
/**
 * Issue manager
 */
class IssueManager extends BaseManager
{
	function __construct() {
		parent::__construct();
		global $dba;
		$this->dba = $dba;
		$this->safesql = new SafeSQL_MySQLi($dba->dbh);
	}

	/**
	 * Public: Search issue content
	 *
	 * $query - query string
	 *
	 * Return a list of issues
	 */
	public function searchContent($query) {
		$sql = $this->safesql->query("
			SELECT
				Issues.id AS id,
				MATCH(Content) AGAINST ('%s') AS Relevance
			FROM Files, Issues
			WHERE MATCH(Content) AGAINST('%s')
			AND Issues.IssueNo = Files.IssueNo
			AND Issues.PubNo = Files.PubNo
			HAVING Relevance >= 0.0
			ORDER BY Relevance DESC",
			array(
				$query, $query	
			));

		$results = $this->dba->get_results($sql);

		$issues = array();

		// no results
		if (is_null($results)) {
			return $issues;
		}

		$maxr = 0; // max relevance
		foreach($results as $obj) {
			$issue = new Issue($obj->id);
			$issues[] = $issue;

			if ($maxr == 0) {
				$maxr = $obj->Relevance;
			}
			$relevance = ($obj->Relevance * (min(100, $maxr * 200))) / $maxr;
			$issue->setRelevance($relevance);
		}
		return $issues;
	}
}
