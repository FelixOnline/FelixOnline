<?php
/*
 * Felix Online
 */

ob_start();
 
require_once('inc/timing.inc.php');
$timing = new Timing('log-themes');

/* If the url is on the union servers then redirect to custom url */
if (strstr($_SERVER['HTTP_HOST'],"union.ic.ac.uk") !== false) {
    header("Location: ".STANDARD_URL.substr($_SERVER['REQUEST_URI'],(1+strrpos($_SERVER['REQUEST_URI'],"/"))));
}
 
// set up Felix Online environment
require_once('bootstrap.php');

$currentuser = new CurrentUser();

$hooks = new Hooks();

/*
 * Routes
 */
$urls = array(
    '/' => 'FrontpageController',
    '/user/(?P<user>[a-zA-Z0-9_-]+)' => 'UserController',
    '/user/(?P<user>[a-zA-Z0-9_-]+)/(?P<page>[0-9]+)' => 'UserController',
    '/media/(?P<type>[a-zA-Z0-9_-]+)' => 'MediaController',
    '/media/(?P<type>[a-zA-Z0-9_-]+)/(?P<id>[0-9]+)/.*' => 'MediaController',
    '/search' => 'SearchController',
    '/(?P<cat>[a-zA-Z]+)' => 'CategoryController',
    '/(?P<cat>[a-zA-Z]+)/(?P<page>[0-9]+)' => 'CategoryController',
    '/(?P<cat>[a-zA-Z]+)/(?P<id>[0-9]+)/(?P<title>[a-zA-Z0-9_-]+)/.*' => 'ArticleController',
    '/login/.*' => 'AuthController',
    '/logout/.*' => 'AuthController',
);

/*
 * Add pages to routes
 */
$sql = "SELECT * FROM `pages`";
$pages = $db->get_results($sql);
foreach($pages as $key => $page) {
    $urls['/'.$page->slug] = 'pageController'; 
}

/*
 * Include Controllers
 */
require_once(BASE_DIRECTORY.'/controllers/baseController.php');
foreach (glob(BASE_DIRECTORY.'/controllers/*.php') as $filename) {
    require_once($filename);
}
$timing->log('After setup');

try {
	// try mapping request to urls
    glue::stick($urls);
} catch (NotFoundException $e) {
	$prior_exception = $e;
    try {
    	if(!isset($theme)) {
    		trigger_error('The themes class has not been loaded');
    	}
    	
    	$theme->render('404');
    } catch (Exception $e) {
    	ob_end_clean();
    	ob_start();
    	echo $prior_exception->getMessage().'<pre>'.$prior_exception->getTraceAsString().'</pre>';
		ob_end_flush();
		exit();
    }
} catch (InternalException $e) {
	$prior_exception = $e;
    try {
    	if(!isset($theme)) {
    		trigger_error('The themes class has not been loaded');
    	}
    	
    	$theme->render('500');
    } catch (Exception $e) {
    	ob_end_clean();
    	ob_start();
    	echo $prior_exception->getMessage().'<pre>'.$prior_exception->getTraceAsString().'</pre>';
		ob_end_flush();
		exit();
    }
}

ob_end_flush();
?>
