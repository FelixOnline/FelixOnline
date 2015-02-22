<?php
	/*
	 * Site constants
	 * 
	 * To change constants define them in config.inc.php
	 */

	/* SYSTEM */
	if(!defined('STANDARD_URL'))					define('STANDARD_URL','http://felixonline.co.uk/'); // standard site url
	if(!defined('ADMIN_URL'))					 	define('ADMIN_URL','http://felixonline.co.uk/engine/'); // url of engine page
	if(!defined('PRODUCTION_FLAG'))					define('PRODUCTION_FLAG', true); // if set to true css and js will be minified etc..
	if(!defined('AUTHENTICATION_SERVER'))			define('AUTHENTICATION_SERVER','dougal.union.ic.ac.uk'); // authentication server
	if(!defined('AUTHENTICATION_PATH'))				define('AUTHENTICATION_PATH','https://dougal.union.ic.ac.uk/media/felix/'); // authentication path
	if(!defined('DEFAULT_IMG_URI'))					define('DEFAULT_IMG_URI','defaultimage.jpg'); // default image
	if(!defined('CURRENT_THEME'))					define('CURRENT_THEME', 2014);

	/* NAVIGATION */
	if(!defined('POPULAR_ARTICLES'))				define('POPULAR_ARTICLES',5); // used for commented and viewed
	if(!defined('ARTICLES_PER_CAT_PAGE'))		  	define('ARTICLES_PER_CAT_PAGE',8); // number of articles on the first category page
	if(!defined('ARTICLES_PER_SECOND_CAT_PAGE'))	define('ARTICLES_PER_SECOND_CAT_PAGE',10); // number of articles on the second category page
	if(!defined('ARTICLES_PER_USER_PAGE'))		 	define('ARTICLES_PER_USER_PAGE',8); // number of articles on user page
	if(!defined('NUMBER_OF_PAGES_IN_PAGE_LIST'))	define('NUMBER_OF_PAGES_IN_PAGE_LIST',14); // Max number of pages to show in list of pages
	if(!defined('NUMBER_OF_POPULAR_ARTICLES_USER')) define('NUMBER_OF_POPULAR_ARTICLES_USER',5); // max number of popular articles on user page

	/* FRONT PAGE */
	if(!defined('NEWS_CATEGORY_ID'))				define('NEWS_CATEGORY_ID', 1); // for front page
	if(!defined('COMMENT_CATEGORY_ID'))				define('COMMENT_CATEGORY_ID', 2); // for front page
	if(!defined('SPORT_CATEGORY_ID'))				define('SPORT_CATEGORY_ID', 18); // for front page
	if(!defined('CANDS_CATEGORY_ID'))				define('CANDS_CATEGORY_ID', 23); // for front page - clubs and socs

	/* RSS */
	if(!defined('RSS_IMG'))							define('RSS_IMG',(IMAGE_URL.'/800/600/'.DEFAULT_IMG_URI));
	if(!defined('RSS_NAME'))						define('RSS_NAME','Felix Online');
	if(!defined('RSS_DESCRIPTION'))					define('RSS_DESCRIPTION','Latest articles from Felix Online');
	if(!defined('RSS_COPYRIGHT'))					define('RSS_COPYRIGHT',('(c) Felix Online | '.date('Y')));
	if(!defined('RSS_ARTICLES'))					define('RSS_ARTICLES',30);
