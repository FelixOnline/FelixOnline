<?php
/*
 * Handle all ajax requests
 */

/*
 * Load Felix Online environment
 */
require_once('bootstrap.php');
require_once('inc/exceptions.inc.php');

$currentuser = new CurrentUser();

/*
 * Set up hooks
 */
$hooks = new Hooks();
$theme = new Theme('classic'); // TODO

$clean_request = array();
foreach($_POST as $key => $val) {
	$clean_request[$key] = htmlspecialchars_decode($val);
}

$action = $clean_request['action'];

if($action = $hooks->getAction($action)) {
	// check csrf
	$check = $clean_request['check'];
	$token = $clean_request['token'];
	try {
		Validator::Check(
			array('csrf' => $token), 
			array('csrf' => 
				array(
					'val_1007' => $check
				)
			)
		); 
		$return = call_user_func($action, $clean_request);
	} catch (ValidatorException $e) {
		if($e->getMessage() == 1 && key($e->getData()) == 'csrf') {
			$return = array(error => true, details => 'A security error has occured, this page will now be reloaded', reload => true);
		} else {
			$return = array(error => true, details => $e->getMessage().' '.json_encode($e->getData()));
		}
	}
	// Check if it is an ajax request
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$return['newtoken'] = Utility::generateCSRFToken($check);
		$return = json_encode($return);
		echo $return;
	} else {
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	die();
}
