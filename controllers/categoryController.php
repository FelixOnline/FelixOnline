<?php
	
class CategoryController extends BaseController {
	function GET($matches) {
		$category = new Category($matches['cat']);
		if(!$matches['page']) {
			$pagenum = 1;
		} else {
			$pagenum = $matches['page'];
		}
		$this->theme->appendData(array(
			'category' => $category,
			'pagenum' => $pagenum
		));
		$this->theme->setHierarchy(array(
			$category->getCat() // category-{cat}.php
		));
		$this->theme->render('category');
	}
}

?>
