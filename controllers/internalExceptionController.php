<?php

class InternalExceptionController extends BaseController {
    function GET($matches) {
        global $timing;
        $timing->log('500 controller');
        $this->theme->render(505);
    } 
}

?>
