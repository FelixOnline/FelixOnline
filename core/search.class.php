<?php
/**
 * Search query
 */
class Search
{
	protected $query;
	protected $articles = array();
	protected $people = array();

	function __construct($query) {
		global $db;
		global $safesql;
		$this->db = $db;
		$this->safesql = $safesql;
		$this->pageSize = ARTICLES_PER_CAT_PAGE;

		$this->query = $query;
	}

	public function people() {
		return array();
	}

	public function articleTitles($page = 1) {
		// title search
		$sql = $this->safesql->query(
			"SELECT
				id
			FROM article
			WHERE title LIKE '%s'
			AND hidden = 0
			AND published < NOW()
			ORDER BY date DESC",
			array('%'.$this->query.'%')
		);
		$results = $this->db->get_results($sql);

		if (is_null($results)) {
			return array();
		} else {
			$articles = array();
			foreach ($results as $a) {
				array_push($articles, new Article($a->id));
			}
			return $articles;
		}
	}
}
