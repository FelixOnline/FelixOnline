<?php

use FelixOnline\Exceptions;

class CategoryController extends BaseController
{
	function GET($matches)
	{
		try {
			$category = (new \FelixOnline\Core\CategoryManager())
				->filter('cat = "%s"', array($matches['cat']))
				->one();
		} catch (Exceptions\InternalException $e) {
			throw new NotFoundException(
				$e->getMessage(),
				$matches,
				'CategoryController',
				FrontendException::EXCEPTION_NOTFOUND,
				$e
			);
		}

		if(!isset($matches['page']) || !$matches['page']) {
			$pagenum = 1;
		} else {
			$pagenum = $matches['page'];
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

		$this->theme->appendData(array(
			'category' => $category,
			'pagenum' => $pagenum,
			'articles' => $articles,
			'pages' => $pages,
		));

		$this->theme->setHierarchy(array(
			$category->getCat() // category-{cat}.php
		));
		$this->theme->render('category');
	}
}
