<?php

class UserController extends BaseController {
	function GET($matches) {
		$user = new \FelixOnline\Core\User($matches['user']);
		if(!isset($matches['page'])) {
			$pagenum = 1;
		} else {
			$pagenum = $matches['page'];
		}

		// Articles
		$manager = (new \FelixOnline\Core\ArticleManager())
			->filter('published < NOW()')
			->order('date', 'DESC');

		$authorManager = (new \FelixOnline\Core\ArticleAuthorManager())
			->filter("author = '%s'", array($user->getUser()));
		$manager->join($authorManager);

		$manager->limit(
			($pagenum - 1) * ARTICLES_PER_USER_PAGE,
			ARTICLES_PER_USER_PAGE
		);

		$articleCount = $manager->count();
		$articles = $manager->values();

		if (is_null($articles)) {
			$articles = array();
		}

		// Popular articles
		$popularArticles = $manager->order('hits', 'DESC')
			->limit(0, NUMBER_OF_POPULAR_ARTICLES_USER)
			->values();

		$pages = ceil(($articleCount - ARTICLES_PER_USER_PAGE) / (ARTICLES_PER_USER_PAGE)) + 1;

		$this->theme->appendData(array(
			'user' => $user,
			'pagenum' => $pagenum,
			'articles' => $articles,
			'article_count' => $articleCount,
			'pages' => $pages,
			'popular_articles' => $popularArticles,
		));
		$this->theme->setHierarchy(array(
			$user->getUser() // user-{user}.php
		));
		$this->theme->render('user');
	}
}
