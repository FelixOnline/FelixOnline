<?php

class UserController extends BaseController {
	function GET($matches) {
		try {
			$user = new \FelixOnline\Core\User($matches['user']);
		} catch(Exception $e) {
			throw new NotFoundException(
				"User Not Found",
				$matches,
				'UserController'
			);
		}

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
			($pagenum - 1) * \FelixOnline\Core\Settings::get('articles_per_user_page'),
			\FelixOnline\Core\Settings::get('articles_per_user_page')
		);

		$articleCount = $manager->count();
		$articles = $manager->values();

		if (is_null($articles)) {
			$articles = array();
		}

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

		$pages = ceil(($articleCount - \FelixOnline\Core\Settings::get('articles_per_user_page')) / (\FelixOnline\Core\Settings::get('articles_per_user_page'))) + 1;

		$this->theme->appendData(array(
			'user' => $user,
			'pagenum' => $pagenum,
			'articles' => $articles,
			'article_count' => $articleCount,
			'pages' => $pages,
			'categories' => $categories,
			'popular_articles' => $popularArticles,
		));

		$this->theme->setHierarchy(array(
			$user->getUser() // user-{user}.php
		));
		$this->theme->render('user');
	}
}
