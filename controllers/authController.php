<?php
/*
 * Auth controller
 * Handles authenticating users
 *
 * Auth flow
 * 
 * User posts to https url
 * -> authenticates username and password using pam_auth
 * -> sets session and stores it in database
 * -> redirects user back to login page with session id and flags for remember and where the user came from (goto)
 * -> 
 */

use FelixOnline\Exceptions;

class AuthController extends BaseController {
	/*
	 *
	 */
	function GET($matches) {
		global $currentuser;
		if(isset($_GET['session'])) { // catch login
			// check if session is recent and that ip is the same
			if($currentuser->restoreSession($_GET['session'])) {
				if($_GET['remember'] == 'rememberme') {
					$currentuser->setCookie();
				}
				
				Utility::redirect($_GET['goto']);
			} else {
				throw new FrontendException("Internal error - the session is not valid for this user");
			}
			// show main exception page if something goes wrong here - do not catch!!!
		} elseif(isset($_GET['logout'])) {
			$this->logout();
			Utility::redirect($_GET['goto']);
		} else {
			if($currentuser->isLoggedIn()) {
				Utility::redirect(STANDARD_URL);
			} else {
				// insert login page here
				$failed = 0;
				if (isset($_GET['failed'])) { 
					$failed = 1;
				} 
				$this->theme->render('login', array('failed' => $failed));
			}
		}
	}

	/*
	 * 
	 */
	function POST($matches) {
		global $currentuser;
		if(isset($_POST['username']) && isset($_POST['password'])) {
			try {
  			  if($this->authenticate($_POST['username'], $_POST['password'])) {
  			  		$currentuser->setUser($_POST['username']);
  			  		$currentuser->createSession();

					// comment like/dislike
					if(isset($_POST['commenttype']) && isset($_POST['comment'])) {
						$comment = new \FelixOnline\Core\Comment($_POST['comment']);
						if($_POST['commenttype'] == 'like') {
							$comment->likeComment($currentuser);
						} else if($_POST['commenttype'] == 'dislike') {
							$comment->dislikeComment($currentuser);
						}
						$hash = $comment->getId();
					}
					
					// Close the session here, as we do not want lingering sessions on the auth server
					$session = $currentuser->stashSession();
	
					Utility::redirect(STANDARD_URL.'login/', array(
						'session' => $session,
						'remember' => $_POST['remember'],
						'goto' => $_GET['goto']
					), $hash);
			  } else {
					throw new Exceptions\InternalException("Invalid credentials");
					// Catch this elsewhere
		 	  }
	 		} catch (Exceptions\InternalException $e) {
				Utility::redirect(STANDARD_URL.'login', array(
					'failed' => true
				));
			}
		}
		if(isset($_POST['logout'])) {
			$this->logout();
			Utility::redirect($_GET['goto']);
		}
	}

	/*
	 * Private: Authenticate user
	 */
	private function authenticate($username, $password) {
		global $currentuser;
		if(!LOCAL) { // if executed on union servers
			/* authenticate user using global function pam_auth - returns true
			 * if user is Imperial student, false if not (Union server only)
			 */
			
			// disable error handler here temporarily
			restore_error_handler();
			$check = pam_auth(
				$username, 
				$password
			);
			set_error_handler('errorhandler', E_ALL & ~E_NOTICE);
		} else {
			if ($username == 'bad' || $password == 'bad') {
				$check = false; // manual bad case
			} else {
		   		$check = true; // override check
			}
		}
		return $check;
	}

	/*
	 * Private: Logout
	 */
	private function logout() {
		global $currentuser;

		$currentuser->resetSession();
		$currentuser->removeCookie();
	}
}
