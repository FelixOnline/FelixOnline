<?php
/*
 * Utility Class
 *
 * Collection of static functions
 */
class Utility {
    /*
     * Public Static: Get current page url
     *
     * Returns string
     */
    public static function currentPageURL() {
        return 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }

    /*
     * Public Static: Trim text
     *
     * $string - String to trim
     * $limit - Character limit for string
     *
     * Returns string
     */
    public static function trimText($string, $limit) {
        $string = strip_tags($string); // strip tags
        if(strlen($string) <= $limit) {
            return $string;
        } else {
            return substr($string, 0, $limit).' ... ';
        }
    }
}
?>
