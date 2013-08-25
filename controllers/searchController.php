<?php

class SearchController extends BaseController {
	function GET($matches) {
		global $timing;
		$timing->log('Search controller');
		$this->theme->setHierarchy(array(
			'search'
		));
		$this->theme->render('search');
	} 
}