<?php

class NotFoundController extends BaseController {
	function GET($matches) {
		global $timing;
		$timing->log('404 controller');
		$this->theme->appendData(array('e' => $matches[0]));
		$this->theme->render('404_page');
	} 
}

?>
