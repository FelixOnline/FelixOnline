<?php
	/*
	 * Site constants
	 * 
	 * To change constants define them in config.inc.php
	 */

	/* SYSTEM */
	if(!defined('STANDARD_URL'))					define('STANDARD_URL','http://felixonline.co.uk/'); // standard site url
	if(!defined('ADMIN_URL'))					   define('ADMIN_URL','http://felixonline.co.uk/engine/'); // url of engine page
	if(!defined('PRODUCTION_FLAG'))				 define('PRODUCTION_FLAG', true); // if set to true css and js will be minified etc..
	if(!defined('SESSION_LENGTH'))				  define('SESSION_LENGTH',7200); // session length
	if(!defined('LOGIN_CHECK_LENGTH'))			  define('LOGIN_CHECK_LENGTH',300); // length to allow login check (5mins)
	if(!defined('COOKIE_LENGTH'))				   define('COOKIE_LENGTH', 2592000); // cookie length (30 days) (60*60*24*30)
	if(!defined('AUTHENTICATION_SERVER'))		   define('AUTHENTICATION_SERVER','dougal.union.ic.ac.uk'); // authentication server
	if(!defined('AUTHENTICATION_PATH'))			 define('AUTHENTICATION_PATH','https://dougal.union.ic.ac.uk/media/felix/'); // authentication path
	if(!defined('DEFAULT_IMG_URI'))				 define('DEFAULT_IMG_URI','defaultimage.jpg'); // default image
	if(!defined('POPULAR_ARTICLES'))				define('POPULAR_ARTICLES',5); // used for commented and viewed
	if(!defined('ARTICLES_PER_CAT_PAGE'))		   define('ARTICLES_PER_CAT_PAGE',8); // number of articles on the first category page
	if(!defined('ARTICLES_PER_SECOND_CAT_PAGE'))	define('ARTICLES_PER_SECOND_CAT_PAGE',10); // number of articles on the second category page
	if(!defined('ARTICLES_PER_USER_PAGE'))		  define('ARTICLES_PER_USER_PAGE',8); // number of articles on user page
	if(!defined('NUMBER_OF_PAGES_IN_PAGE_LIST'))	define('NUMBER_OF_PAGES_IN_PAGE_LIST',14); // Max number of pages to show in list of pages
	if(!defined('NUMBER_OF_POPULAR_ARTICLES_USER')) define('NUMBER_OF_POPULAR_ARTICLES_USER',5); // max number of popular articles on user page
	if(!defined('IMAGE_URL'))					   define('IMAGE_URL', 'http://img.felixonline.co.uk/'); // image url 
	if(!defined('LOCAL'))						   define('LOCAL', false); // if true then site is hosted locally - don't use pam_auth etc. 
	if(!defined('SERVER_ENV'))						define('SERVER_ENV', 'production'); // server environment

	/* FRONT PAGE */
	if(!defined('NEWS_CATEGORY_ID'))				define('NEWS_CATEGORY_ID', 1); // for front page
	if(!defined('COMMENT_CATEGORY_ID'))				define('COMMENT_CATEGORY_ID', 2); // for front page
	if(!defined('SPORT_CATEGORY_ID'))				define('SPORT_CATEGORY_ID', 18); // for front page
	if(!defined('CANDS_CATEGORY_ID'))				define('CANDS_CATEGORY_ID', 23); // for front page - clubs and socs

	/* RSS */
	define('RSS_IMG',(IMAGE_URL.'/800/600/'.DEFAULT_IMG_URI));
	define('RSS_NAME','Felix Online');
	define('RSS_DESCRIPTION','Latest articles from Felix Online');
	define('RSS_COPYRIGHT',('(c) Felix Online | '.date('Y')));
	define('RSS_ARTICLES',30);

	/* COMMENTS */
	if(!defined('EXTERNAL_COMMENT_ID'))			 define('EXTERNAL_COMMENT_ID', 80000000); // external comment id start
	if(!defined('AKISMET_API_KEY'))				 define('AKISMET_API_KEY', 'KEY');

	/* EMAIL */ // To move to Core/API
	define('EMAIL_FROM_ADDR','Felix Online <no-reply@imperial.ac.uk>'); // can't just 'make one up'... must be a valid address if @imperial.ac.uk!
	define('EMAIL_REPLYTO_ADDR','no-reply@imperial.ac.uk');
