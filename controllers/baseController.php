<?php

class BaseController {
    protected $theme; // placeholder for theme class

    function __construct() {
        global $theme;
        $this->theme = $theme;
    }
}
?>
