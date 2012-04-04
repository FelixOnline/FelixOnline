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

$action = $_REQUEST['action'];
if($action = $hooks->getAction($action)) {
    // check csrf
    $check = $_REQUEST['check'];
    $token = $_REQUEST['token'];
    try {
        Validator::Check(
            array('csrf' => $token), 
            array('csrf' => 
                array(
                    'val_1007' => $check
                )
            )
        ); 
        $return = call_user_func($action);
    } catch (ValidatorException $e) {
        $return = json_encode(array(error => true, details => $e->getMessage().' '.json_encode($e->getData())));
    }
    // Check if it is an ajax request
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo $return;
    } else {
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    die();
}
