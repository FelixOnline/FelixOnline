<?php
    $db = "DB_ARCHIVE";
    $host = "localhost";
    $user = "DB_USER";
    $pass = "DB_PASSWORD";
    $cid_archive = mysql_connect($host,$user,$pass);
    mysql_select_db($db,$cid_archive);
?>
