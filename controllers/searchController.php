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
		$articles = $search->articleTitles();
		$people = $search->people();

		// get articles
		$this->theme->render(
			'search',
			array(
				'articles' => $articles,
				'people' => $people,
				'query' => $query,
				'page' => $page
			)
		);
	} 
}
