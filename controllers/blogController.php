<?php
/*
 * Blog Controller
 * Handles all blog requests
 */
class BlogController extends BaseController {
	private $blog;
	function GET($matches) {
		$blog = substr($matches[0], 1, -1);
		$this->blog = new Blog($blog);
		$this->theme->appendData(array(
			'blog' => $this->blog
		));
		$this->theme->setHierarchy(array(
			'slug' /* page-{slug}.php */
		));
		$this->theme->render('blog');
	}
}

?>
