<?php
/*
 * Felix Online
 */

ob_start();

try {
	require_once('bootstrap.php');
	
	require_once('inc/exceptions.inc.php');
	require_once('inc/timing.inc.php');
	$timing = new Timing('log-themes');
	
	/* If the url is on the union servers then redirect to custom url */
	if (strstr($_SERVER['HTTP_HOST'],"union.ic.ac.uk") !== false) {
	    header("Location: ".STANDARD_URL.substr($_SERVER['REQUEST_URI'],(1+strrpos($_SERVER['REQUEST_URI'],"/"))));
	}
	
	$currentuser = new CurrentUser();
	
	$hooks = new Hooks();
	
	/*
	 * Routes
	 */
	$urls = array(
	    '/' => 'FrontpageController',
	    '/user/(?P<user>[a-zA-Z0-9_-]+)' => 'UserController',
	    '/user/(?P<user>[a-zA-Z0-9_-]+)/(?P<page>[0-9]+)' => 'UserController',
	    '/search' => 'SearchController',
	    '/(?P<cat>[a-zA-Z]+)' => 'CategoryController',
	    '/(?P<cat>[a-zA-Z]+)/(?P<page>[0-9]+)' => 'CategoryController',
	    '/(?P<cat>[a-zA-Z]+)/(?P<id>[0-9]+)/(?P<title>[a-zA-Z0-9_-]+)/.*' => 'ArticleController',
	    '/login/.*' => 'AuthController',
	    '/logout/.*' => 'AuthController'
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
     * Sites
     */
    $media = array(
	    '/media' => 'MediaController',
	    '/media/(?P<type>[a-zA-Z0-9_-]+)' => 'MediaController',
	    '/media/(?P<type>[a-zA-Z0-9_-]+)/(?P<id>[0-9]+)/.*' => 'MediaController'
    );

    foreach($media as $route => $controller) {
	    $urls[$route] = $controller; 
    }

} catch (Exception $e) {
	$prior_exception = null;
	require('errors/index.php');
	exit();
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
	// If any exception which amounts to something not being found is raised
	$prior_exception = $e;
    try {
    	// First attempt to show nice 404 page. Throw away current theme data
    	ob_end_clean();
    	ob_start();
    	$controller = new NotFoundController();
		$controller->GET(array($prior_exception));
    	ob_end_flush();
    	// End execution
    	exit();
    } catch (Exception $e) {
    	// there is an exception in the above code - time to bail out and display the emergency error page
    	ob_end_clean();
    	ob_start();
    	require('errors/index.php');
		ob_end_flush();
		// End execution
		exit();
    }
} catch (InternalException $e) {
	// If something bad happened
	$prior_exception = $e;
    try {
    	// First attempt to show nice 500 page. Throw away current theme data
    	ob_end_clean();
    	ob_start();
    	$controller = new InternalExceptionController();
		$controller->GET(array($prior_exception));
    	ob_end_flush();
		// End execution
    	exit();
    } catch (Exception $e) {
    	// there is an exception in the above code - time to bail out and display the emergency error page
    	ob_end_clean();
    	ob_start();
    	require('errors/index.php');
		ob_end_flush();
		// End execution
		exit();
    }
}

ob_end_flush();
?>
