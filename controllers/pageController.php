<?php
/*
 * Page Controller
 * Handles all page requests
 */
class PageController extends BaseController {
    private $page;
    function GET($matches) {
        $page = substr($matches[0], 1, -1);
        $this->page = new Page($page);
        $this->theme->appendData(array(
            'page' => $this->page
        ));
        $this->theme->setHierarchy(array(
            'slug' /* page-{slug}.php */
        ));
        $this->theme->render('page');
    }
}

?>
