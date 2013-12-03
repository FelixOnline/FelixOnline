<?php

class SearchController extends BaseController {
	function GET($matches) {
		global $timing;
		$timing->log('Search controller');

		$query = str_replace(" ", "%", trim($_GET['q']));

		$page = isset($_GET['p']) ? $_GET['p'] : 1;

		if (strlen($query) <= 2) {
			$this->theme->render('search', array('toofew' => true));
			return false;
		}

		$search = new Search($query);
		$articles = $search->articleTitles($page);

		$people = $search->people();

		// get articles
		$this->theme->render(
			'search',
			array(
				'articles' => $articles['articles'],
				'article_count' => $articles['count'],
				'people' => $people['people'],
				'people_count' => $people['count'],
				'query' => $query,
				'page' => $page
			)
		);
	} 
}
