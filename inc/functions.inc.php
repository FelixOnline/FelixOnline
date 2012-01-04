<?php
/*
 * Functions
 */

/*
 * Get current page url
 *
 * Returns string
 */
function currentPageURL() {
    return 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}

?>
