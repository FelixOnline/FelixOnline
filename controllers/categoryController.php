<?php

use FelixOnline\Exceptions;

class CategoryController extends BaseController
{
	public static function fetch($category, $pagenum = 1) {
		global $currentuser;
		
		$category = (new \FelixOnline\Core\CategoryManager())
			->filter('cat = "%s"', array($category))
			->filter('active = 1')
			->one();

		if($category->getSecret() && !$currentuser->isLoggedIn()) {
			// Cannot see articles in inactive categories
			throw new NotFoundException(
				"Category is not accessible",
				$matches,
				'CategoryController'
			);
		}

		$manager = (new \FelixOnline\Core\ArticleManager())
			->filter('published < NOW()')
			->filter('category = %i', array($category->getId()))
			->order(array('published', 'id'), 'DESC')
			->limit(($pagenum - 1) * \FelixOnline\Core\Settings::get('articles_per_cat_page'), \FelixOnline\Core\Settings::get('articles_per_cat_page'));

		$count = $manager->count();
		$pages = ceil(($count - \FelixOnline\Core\Settings::get('articles_per_cat_page')) / (\FelixOnline\Core\Settings::get('articles_per_second_cat_page'))) + 1;

		$articles = $manager->values();
			
		if (is_null($articles)) {
			$articles = [];
		}

		return array('category' => $category,
			'pagenum' => $pagenum,
			'articles' => $articles,
			'pages' => $pages);
	}

	function GET($matches)
	{
		try {
			if(!isset($matches['page'])) {
				$pagenum = 1;
			} else {
				$pagenum = $matches['page'];
			}

			$data = self::fetch($matches['cat'], $pagenum);
		} catch (Exceptions\InternalException $e) {
			throw new NotFoundException(
				$e->getMessage(),
				$matches,
				'CategoryController',
				FrontendException::EXCEPTION_NOTFOUND,
				$e
			);
		}

		$this->theme->appendData(array(
			'category' => $data['category'],
			'pagenum' => $data['pagenum'],
			'articles' => $data['articles'],
			'pages' => $data['pages'],
		));

		$this->theme->setHierarchy(array(
			$data['category']->getCat() // category-{cat}.php
		));
		$this->theme->render('category');
	}
}
