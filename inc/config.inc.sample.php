<?php
    $db = "DB_TABLE";
    $host = "localhost";
    $user = "DB_USER";
    $pass = "DB_PASSWORD";
    $cid = mysql_connect($host,$user,$pass);
    $dbok = mysql_select_db($db,$cid);
?>
