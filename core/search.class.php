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
		return array(
			'count' => 0,
			'people' => array()
		);
	}

	public function articleTitles($page = 1) {
		$filters = "FROM article
			WHERE title LIKE '%s'
			AND hidden = 0
			AND published < NOW()
			ORDER BY date DESC";

		// get count
		$sql = $this->safesql->query(
			"SELECT
				COUNT(id)
			" . $filters,
			array(
				'%'.$this->query.'%',
			)
		);
		$count = (int)$this->db->get_var($sql);

		if ($count == 0) {
			return array(
				'count' => 0,
				'articles' => array()
			);
		}

		$sql = $this->safesql->query(
			"SELECT
				id
			" . $filters . "
			LIMIT %i, %i",
			array(
				'%'.$this->query.'%',
				($page - 1) * ARTICLES_PER_CAT_PAGE,
				ARTICLES_PER_CAT_PAGE
			)
		);
		$results = $this->db->get_results($sql);

		if (is_null($results)) {
			throw new InternalException("Results array is null");
		} else {
			$articles = array();
			foreach ($results as $a) {
				array_push($articles, new Article($a->id));
			}
			return array(
				'count' => $count,
				'articles' => $articles
			);
		}
	}

	public function articleContent($page = 1) {
		$filters = "FROM `article`
			INNER JOIN `text_story`
			ON (article.text1 = text_story.id)
			WHERE text_story.content LIKE '%s'
			AND article.hidden = 0
			AND article.published < NOW()
			ORDER BY article.date DESC";

		// get count

		$sql = $this->safesql->query(
			"SELECT
				COUNT(article.id)
			" . $filters,
			array(
				'%'.$this->query.'%',
			)
		);

		$count = (int)$this->db->get_var($sql);

		if ($count == 0) {
			return array(
				'count' => 0,
				'articles' => array()
			);
		}
	
		$sql = $this->safesql->query(
			"SELECT
				article.id
			" . $filters . "
			LIMIT %i, %i",
			array(
				'%'.$this->query.'%',
				($page - 1) * ARTICLES_PER_CAT_PAGE,
				ARTICLES_PER_CAT_PAGE
			)
		);
		$results = $this->db->get_results($sql);

		if (is_null($results)) {
			throw new InternalException("Results array is null");
		} else {
			$articles = array();
			foreach ($results as $a) {
				array_push($articles, new Article($a->id));
			}
			return array(
				'count' => $count,
				'articles' => $articles
			);
		}
	}
}
