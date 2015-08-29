<?php

class SearchController extends BaseController {
	public function fetch($query, $page = 1) {
		$query2 = str_replace(" ", "%", trim($query));

		$search = new \FelixOnline\Core\Search($query2);
		$articles = $search->articleTitles($page);

		return array('articles' => $articles,
			'page' => $page,
			'query' => $query);
	}

	function GET($matches) {
		$_query = trim(strip_tags($_GET['q']));
		$query = str_replace(" ", "%", trim($_query));

		$page = isset($_GET['p']) ? $_GET['p'] : 1;

		if (strlen($query) <= 2) {
			$this->theme->render('search', array('toofew' => true));
			return false;
		}

		$search = new \FelixOnline\Core\Search($query);
		$people = $search->people();

		$data = self::fetch($_query, $page);

		$this->theme->render(
			'search',
			array(
				'articles' => $data['articles']['articles'],
				'article_count' => $data['articles']['count'],
				'people' => $people['people'],
				'people_count' => $people['count'],
				'query' => $_query,
				'page' => $data['page']
			)
		);
	} 
}
