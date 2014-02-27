<?php
/*
 * Blog Controller
 * Handles all blog requests
 */
class BlogController extends BaseController {
	function GET($matches) {
		$slug = substr($matches[0], 1);

		// Remove trailing slash
		if (substr($slug, -1) == '/') {
			$slug = substr($slug, 0, -1);
		}

		$blog = new Blog($slug);

		// get blog posts for page
		$posts = $blog->getPosts(1);
		
		$this->theme->appendData(array(
			'blog' => $blog,
			'posts' => $posts,
		));
		$this->theme->setHierarchy(array(
			$blog->getSlug() // page-{slug}.php
		));
		$this->theme->render('blog');
	}
}
