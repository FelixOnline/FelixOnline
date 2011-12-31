<?php

class IndexController extends BaseController {
    function GET($matches) {
        $this->theme->render('frontpage');
    } 
}

?>
