<?php
/*
**    @Title: Recaptcha Validation
**    @Date Modified: 6/01/09
**    Instructions: Place this code in a file called "ajax.recaptcha.php"
*/
 
//A. Load the Recaptcha Libary
require_once('recaptchalib.php');
 
$privatekey = "6LdbYL4SAAAAAOAUmQ4QSXUbSYm1LIkgbvqZBWXU";
 
//B. Recaptcha Looks for the POST to confirm 
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
 
//C. If if the User's authentication is valid, echo "success" to the Ajax
if ($resp->is_valid) {
	echo "success";
} else {
    die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
       "(reCAPTCHA said: " . $resp->error . ")");
}
?>