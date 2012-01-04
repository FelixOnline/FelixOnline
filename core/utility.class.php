<?php
/*
 * Utility Class
 *
 * Collection of static functions
 */
class Utility {
    /*
     * Public: Get current page url
     *
     * Returns string
     */
    public static function currentPageURL() {
        return 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
}
?>
