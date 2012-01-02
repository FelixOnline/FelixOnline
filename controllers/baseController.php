<?php
/*
 * Base Controller
 */
class BaseController {
    protected $theme; // placeholder for theme class

    function __construct() {
        /*
         * Set theme here so that it can be overridden by a controller if necessary
         */
        $this->theme = new Theme('classic');

        /*
         * Start current user
         */
        $this->currentUser = new CurrentUser();
    }
}
?>
