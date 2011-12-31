<?php
/* 
 * Sets up the Felix Online environment 
 */

// define current working directory
if(!defined('BASE_DIRECTORY')) define('BASE_DIRECTORY', dirname(__FILE__));

require_once(BASE_DIRECTORY.'/inc/ez_sql_core.php');
require_once(BASE_DIRECTORY.'/inc/ez_sql_mysql.php');
require_once(BASE_DIRECTORY.'/glue.php');
require_once(BASE_DIRECTORY.'/inc/config.inc.php');
require_once(BASE_DIRECTORY.'/inc/const.inc.php');

/*
 * Models
 */
require_once(BASE_DIRECTORY.'/core/baseModel.class.php');
require_once(BASE_DIRECTORY.'/core/article.class.php');
require_once(BASE_DIRECTORY.'/core/email.class.php');
require_once(BASE_DIRECTORY.'/core/comment.class.php');
require_once(BASE_DIRECTORY.'/core/theme.class.php');

/*
 * Controllers
 */
require_once(BASE_DIRECTORY.'/controllers/baseController.php');
require_once(BASE_DIRECTORY.'/controllers/articleController.php');
require_once(BASE_DIRECTORY.'/controllers/archiveController.php');
require_once(BASE_DIRECTORY.'/controllers/indexController.php');
require_once(BASE_DIRECTORY.'/controllers/categoryController.php');
require_once(BASE_DIRECTORY.'/controllers/mediaController.php');
require_once(BASE_DIRECTORY.'/controllers/pageController.php');
require_once(BASE_DIRECTORY.'/controllers/searchController.php');
require_once(BASE_DIRECTORY.'/controllers/userController.php');

require_once(BASE_DIRECTORY.'/inc/authentication.php');
require_once(BASE_DIRECTORY.'/inc/rss.inc.php');

?>
