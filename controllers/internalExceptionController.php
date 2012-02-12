<?php

class InternalExceptionController extends BaseController {
    function GET($matches) {
        global $timing;
        $timing->log('500 controller');
		$this->theme->appendData(array('e' => $matches[0]));
        $this->theme->render('500_page');
    } 
}

?>
