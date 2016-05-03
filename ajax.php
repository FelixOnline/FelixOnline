<?php
/*
 * Handle all ajax requests
 */

/*
 * Load Felix Online environment
 */
require_once('bootstrap.php');

$currentuser = new \FelixOnline\Core\CurrentUser();

/*
 * Set up hooks
 */
$hooks = new \FelixOnline\Core\Hooks();
$theme = new \FelixOnline\Core\Theme(\FelixOnline\Core\Settings::get('current_theme'));

$clean_request = array();
foreach($_POST as $key => $val) {
	$clean_request[$key] = htmlspecialchars_decode($val);
}

$action = $clean_request['action'];

if($action = $hooks->getAction($action)) {
	if($hooks->isProtected($clean_request['action'])) { 
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
				$return = array("error" => true, "details" => 'A security error has occured, this page will now be reloaded', "reload" => true);
			} else {
				$return = array("error" => true, "details" => $e->getMessage().' '.json_encode($e->getData()));
			}
		}

		$return['newtoken'] = Utility::generateCSRFToken($check);
	} else {
		$return = call_user_func($action, $clean_request);
	}

	// Check if it is an ajax request
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		if(isset($return['error']) && $return['error']) {
			header("HTTP/1.1 500 Internal Server Error");
		} else {
			header("HTTP/1.1 200 OK");
		}

		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
		header("Content-Type: text/json", false);

		$return = json_encode($return);
		echo $return;
	} else {
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	die();
}
