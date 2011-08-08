<?php
    /*
     * Create a config.inc.php with the information below to run on a local dev machine
     */

    $db = "DB_TABLE";
    $host = "localhost";
    $user = "DB_USER";
    $pass = "DB_PASSWORD";
    $cid = mysql_connect($host,$user,$pass);
    $dbok = mysql_select_db($db,$cid);

    /*
     * Change these urls to your local versions, e.g http://localhost/felix
     */
    define('STANDARD_URL','http://felixonline.co.uk/');
    define('BASE_URL','http://felixonline.co.uk/');
    define('ADMIN_URL','http://felixonline.co.uk/engine/');

    define('PRODUCTION_FLAG', true); // if set to true css and js will be minified etc..
?>
