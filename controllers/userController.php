<?php

class UserController extends BaseController {
	function fetch($user, $pagenum = 1) {
		global $currentuser;

		$user = new \FelixOnline\Core\User($user);

		// Articles
		$manager = (new \FelixOnline\Core\ArticleManager())
			->filter('published < NOW()')
			->order('date', 'DESC');

		$authorManager = (new \FelixOnline\Core\ArticleAuthorManager())
			->filter("author = '%s'", array($user->getUser()));
		$manager->join($authorManager);

		$categoryManager = (new \FelixOnline\Core\CategoryManager())
			->filter("active = 1");

		if(!$currentuser->isLoggedIn()) {
			$categoryManager->filter('secret = 0');
		}

		$manager->join($categoryManager, null, 'category');

		$manager->limit(
			($pagenum - 1) * \FelixOnline\Core\Settings::get('articles_per_user_page'),
			\FelixOnline\Core\Settings::get('articles_per_user_page')
		);

		$articleCount = $manager->count();
		$articles = $manager->values();

		if (is_null($articles)) {
			$articles = array();
		}

		$pages = ceil(($articleCount - \FelixOnline\Core\Settings::get('articles_per_user_page')) / (\FelixOnline\Core\Settings::get('articles_per_user_page'))) + 1;

		return array('user' => $user,
			'pagenum' => $pagenum,
			'articles' => $articles,
			'pages' => $pages,
			'articleCount' => $articleCount);
	}

	function GET($matches) {
		global $currentuser;
		
		if(!isset($matches['page'])) {
			$pagenum = 1;
		} else {
			$pagenum = $matches['page'];
		}

		try {
			$data = self::fetch($matches['user'], $pagenum);
		} catch(Exception $e) {
			throw new NotFoundException(
				"User Not Found",
				$matches,
				'UserController'
			);
		}

		$user = $data['user'];

		// Popular articles
		$artManager = (new \FelixOnline\Core\ArticleManager())->filter('published < NOW()')->group('id');

		$autManager = (new \FelixOnline\Core\ArticleAuthorManager())
			->filter("author = '%s'", array($user->getUser()));

		$artManager->join($autManager);

		$visManager = (\FelixOnline\Core\BaseManager::build('FelixOnline\Core\Article', 'article_visit', 'article'));
		$artManager->order("COUNT(`article_visit`.id)", "DESC");

		$artManager->join($visManager);

		$popularArticles = $artManager->limit(0, \FelixOnline\Core\Settings::get('number_of_popular_articles_user'))
			->values();

		// Sections
		$categories = $user->getCategories();

		$this->theme->appendData(array(
			'user' => $user,
			'pagenum' => $data['pagenum'],
			'articles' => $data['articles'],
			'article_count' => $data['articleCount'],
			'pages' => $data['pages'],
			'categories' => $categories,
			'popular_articles' => $popularArticles,
		));

		$this->theme->setHierarchy(array(
			$user->getUser() // user-{user}.php
		));
		$this->theme->render('user');
	}
}
