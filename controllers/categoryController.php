<?php

use FelixOnline\Exceptions;

class CategoryController extends BaseController
{
	public static function fetch($category, $pagenum = 1) {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

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

		if($pagenum == 1) {
			$counter = \FelixOnline\Core\Settings::get('articles_per_cat_page');
			$startat = 0;
		} else {
			$counter = \FelixOnline\Core\Settings::get('articles_per_second_cat_page');
			$startat = \FelixOnline\Core\Settings::get('articles_per_cat_page') + ($pagenum - 1) * \FelixOnline\Core\Settings::get('articles_per_second_cat_page');
		}

		$manager = (new \FelixOnline\Core\ArticleManager())
			->filter('published < NOW()')
			->filter('category = %i', array($category->getId()))
			->order(array('published', 'id'), 'DESC')
			->limit($startat, $counter);

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

		if(count($data['articles']) == 0 && $data['category']->getChildren()) {
			// Special case - show summary of children

			$app = \FelixOnline\Core\App::getInstance();
			$currentuser = $app['currentuser'];

			$articles = array();

			foreach($data['category']->getChildren() as $child) {
				if($child->getSecret() && !$currentuser->isLoggedIn()) {
					continue;
				}

				$manager = (new \FelixOnline\Core\ArticleManager())
					->filter('published < NOW()')
					->filter('category = %i', array($child->getId()))
					->order(array('published', 'id'), 'DESC')
					->limit(0, \FelixOnline\Core\Settings::get('articles_per_summary_section'))
					->values();

				if(!is_array($manager)) {
					$articles[$child->getCat()] = array();
				} else {
					$articles[$child->getCat()] = $manager;
				}
			}

			$this->theme->appendData(array(
				'category' => $data['category'],
				'children' => $data['category']->getChildren(),
				'articles' => $articles
			));

			$this->theme->setHierarchy(array(
				$data['category']->getCat() // category-{cat}.php
			));

			$this->theme->render('category_summary');
		} else {
			$this->theme->appendData(array(
				'category' => $data['category'],
				'pagenum' => $data['pagenum'],
				'articles' => $data['articles'],
				'pages' => $data['pages'],
			));

			$this->theme->setHierarchy(array(
				$data['category']->getCat() // category-{cat}.php
			));

			if($pagenum == 1) {
				$this->theme->render('category_page1');
			} else {
				$this->theme->render('category');
			}
		}
	}
}
