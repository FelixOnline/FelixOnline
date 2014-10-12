<?php
/*
 * Page Controller
 * Handles all page requests
 */
class PageController extends BaseController {
	private $page;
	function GET($matches) {
		$page = substr($matches[0], 1);

		// Remove trailing slash
		if (substr($matches[0], -1) == '/') {
			$page = substr($page, 0, -1);
		}

		$this->page = new Page($page);
		$this->theme->appendData(array(
			'page' => $this->page
		));
		$this->theme->setHierarchy(array(
			$this->page->getSlug() // page-{slug}.php
		));
		$this->theme->render('page', array('csrf_token' => $this->page->getToken()));
	}
}
