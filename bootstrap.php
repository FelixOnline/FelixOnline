<?php
/* 
 * Sets up the Felix Online environment 
 */

// define current working directory
if(!defined('BASE_DIRECTORY')) define('BASE_DIRECTORY', dirname(__FILE__));
if(!defined('CACHE_DIRECTORY')) define('CACHE_DIRECTORY', BASE_DIRECTORY.'/cache/');

require_once(BASE_DIRECTORY.'/inc/ez_sql_core.php');
require_once(BASE_DIRECTORY.'/inc/ez_sql_mysql.php');
require_once(BASE_DIRECTORY.'/glue.php');
require_once(BASE_DIRECTORY.'/inc/config.inc.php');
require_once(BASE_DIRECTORY.'/inc/const.inc.php');
require_once(BASE_DIRECTORY.'/inc/functions.inc.php'); // TODO move to utilities
require_once(BASE_DIRECTORY.'/inc/validator.inc.php');
require_once(BASE_DIRECTORY.'/inc/is_email.inc.php');

/*
 * Models
 */
require_once(BASE_DIRECTORY.'/core/baseModel.class.php');
require_once(BASE_DIRECTORY.'/core/user.class.php');
require_once(BASE_DIRECTORY.'/core/frontpage.class.php');
foreach (glob(BASE_DIRECTORY.'/core/*.php') as $filename) {
	require_once($filename);
}

//require_once(BASE_DIRECTORY.'/inc/authentication.php');
//require_once(BASE_DIRECTORY.'/inc/rss.inc.php');

