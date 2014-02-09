<?php
	
class CategoryController extends BaseController
{
	function GET($matches)
	{
		$category = (new \FelixOnline\Core\CategoryManager())
			->filter('cat = "%s"', array($matches['cat']))
			->one();

		if(!isset($matches['page']) || !$matches['page']) {
			$pagenum = 1;
		} else {
			$pagenum = $matches['page'];
		}

		$manager = (new \FelixOnline\Core\ArticleManager())
			->filter('published < NOW()')
			->filter('category = %i', array($category->getId()))
			->order('published', 'DESC')
			->limit(($pagenum - 1) * ARTICLES_PER_CAT_PAGE, ARTICLES_PER_CAT_PAGE);

		$count = $manager->count();
		$pages = ceil(($count - ARTICLES_PER_CAT_PAGE) / (ARTICLES_PER_SECOND_CAT_PAGE)) + 1;

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
