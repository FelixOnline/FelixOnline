<?php
/*
 * Page Controller
 * Handles all page requests
 */
use \FelixOnline\Exceptions;

class PageController extends BaseController {
	function GET($matches) {
		$page = substr($matches[0], 1);

		// Remove trailing slash
		if (substr($matches[0], -1) == '/') {
			$page = substr($page, 0, -1);
		}

		try {
			$page = (new \FelixOnline\Core\PageManager())
				->filter('slug = "%s"', array($page))
				->one();
		} catch (Exceptions\InternalException $e) {
			throw new NotFoundException(
				$e->getMessage(),
				$matches,
				'PageController',
				null,
				$e
			);
		}

		$this->theme->appendData(array(
			'page' => $page
		));
		$this->theme->setHierarchy(array(
			$page->getSlug() // page-{slug}.php
		));
		$this->theme->render('page', array('csrf_token' => $page->getToken()));
	}
}
