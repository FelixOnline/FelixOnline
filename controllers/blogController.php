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

		// get mustache renderer
		$mustache = new Mustache_Engine(array(
			'cache' => CACHE_DIRECTORY,
			'loader' => new Mustache_Loader_FilesystemLoader(BASE_DIRECTORY.'/themes/' . THEME_NAME . '/templates/liveblog'),
		));


		$this->theme->appendData(array(
			'blog' => $blog,
			'posts' => $posts,
			'mustache' => $mustache,
		));
		$this->theme->setHierarchy(array(
			$blog->getSlug() // page-{slug}.php
		));
		$this->theme->render('blog');
	}
}
