<?php

class FrontpageController extends BaseController {
    function GET($matches) {
        $this->theme->render('frontpage');
    } 
}

?>
