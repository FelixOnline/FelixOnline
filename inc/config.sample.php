<?php
    $db = "DB_TABLE";
    $host = "localhost";
    $user = "DB_USER";
    $pass = "DB_PASSWORD";
    $cid = mysql_connect($host,$user,$pass);
    $dbok = mysql_select_db($db,$cid);

	define('STANDARD_URL','http://felixonline.co.uk/');
	define('BASE_URL','http://felixonline.co.uk/');
	define('ADMIN_URL','http://felixonline.co.uk/engine/');

	define('DEVELOPMENT_FLAG', false); // if set to true css and js won't be minified etc..
?>
