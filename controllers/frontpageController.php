<?php

use FelixOnline\Core;
class FrontpageController extends BaseController {
	function GET($matches) {
		global $timing;
		$timing->log('Frontpage controller');

		$spinner = new Core\FrontpageManager();
		$spinner = $spinner->getSection('featured');

		$thisweek = new Core\FrontpageManager();
		$thisweek = $thisweek->getSection('b');

		$this->theme->render('frontpage', array(
			'spinner' => $spinner,
			'thisweek' => $thisweek
		));
	}
}
