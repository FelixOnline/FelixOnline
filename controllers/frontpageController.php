<?php

class FrontpageController extends BaseController {
	function GET($matches) {
		global $timing;
		$timing->log('Frontpage controller');

        $frontpage = new Frontpage();
        $this->theme->render('frontpage', array(
            'frontpage' => $frontpage
        ));
    }
}
