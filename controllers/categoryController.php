<?php
	
class CategoryController extends BaseController {
	function GET($matches) {
		$category = new Category($matches['cat']);
		if(!$matches['page']) {
			$pagenum = 1;
		} else {
			$pagenum = $matches['page'];
		}

		$articles = $category->getArticles($pagenum);
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
