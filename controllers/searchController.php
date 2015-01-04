<?php

class SearchController extends BaseController {
	function GET($matches) {
		global $timing;
		$timing->log('Search controller');

		$_query = trim(strip_tags($_GET['q']));
		$query = str_replace(" ", "%", trim($_query));

		$page = isset($_GET['p']) ? $_GET['p'] : 1;

		if (strlen($query) <= 2) {
			$this->theme->render('search', array('toofew' => true));
			return false;
		}

		$search = new \FelixOnline\Core\Search($query);
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
				'query' => $_query,
				'page' => $page
			)
		);
	} 
}
