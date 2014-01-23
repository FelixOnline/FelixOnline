<?php
	
class CategoryController extends BaseController
{
	function GET($matches)
	{
		$catManager = new \FelixOnline\Core\CategoryManager();

		$category = $catManager
			->filter('cat = "%s"', array($matches['cat']))
			->one();

		if(!$matches['page']) {
			$pagenum = 1;
		} else {
			$pagenum = $matches['page'];
		}

		$m = new \FelixOnline\Core\ArticleManager();

		$articles = $m->filter('published < NOW()')
			->filter('category = %i', array($category->getId()))
			->order('published', 'DESC')
			->limit(($pagenum - 1) * ARTICLES_PER_CAT_PAGE, ARTICLES_PER_CAT_PAGE)
			->values();
			
		if (is_null($articles)) {
			$articles = [];
		}

		$this->theme->appendData(array(
			'category' => $category,
			'pagenum' => $pagenum,
			'articles' => $articles,
		));

		$this->theme->setHierarchy(array(
			$category->getCat() // category-{cat}.php
		));
		$this->theme->render('category');
	}
}
