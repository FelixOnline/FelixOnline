<?php
/*
 * Base Controller
 */
class BaseController {
    protected $theme; // placeholder for theme class
    protected $db;

    function __construct() {
        global $db;
        $this->db = $db;

        /*
         * Set theme here so that it can be overridden by a controller if necessary
         */
        $theme = new Theme('classic');
        $this->theme = $theme->getClass();
        $this->theme->setSite('main');
    }
}
?>
