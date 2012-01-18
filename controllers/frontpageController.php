<?php

class FrontpageController extends BaseController {
    function GET($matches) {
        global $timing;
        $timing->log('Frontpage controller');
        $this->theme->setParentPage('frontpage');
        $this->theme->render('frontpage');
    } 
}

?>
