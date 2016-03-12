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
	static function restoreSession($session, $remember = false) {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		// check if session is recent and that ip is the same
		$loginCheck = $currentuser->restoreSession($session);

		if($loginCheck === TRUE) {
			if($remember == 'rememberme') {
				$currentuser->setCookie();
			}
			
			return true;
		} else {
			throw new FrontendException("Internal error - the session is not valid for this user.");
		}
		// show main exception page if something goes wrong here - do not catch!!!
	}

	function GET($matches) {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		if(isset($_GET['session'])) { // catch login
			if(self::restoreSession($_GET['session'], $_GET['remember'])) {
				Utility::redirect($_GET['goto'], null, $_GET['hash']);
			}
		} elseif(isset($matches['logout'])) {
			$this->logout();
			if($_GET['goto']) {
				$goto = $_GET['goto'];
			} else {
				$goto = STANDARD_URL;
			}

			Utility::redirect($goto);
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

	static function createSession($username, $password, $commenttype = null, $comment = null) {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		if(self::authenticate($username, $password)) {
			$currentuser->setUser($username);
			$currentuser->createSession();

			// comment like/dislike
			if(isset($commenttype) && isset($comment)) {
				$comment = new \FelixOnline\Core\Comment($comment);

				if(!$comment->userLikedOrDislikedComment($currentuser)) {
					if($commenttype == 'like') {
						$comment->likeComment($currentuser);
					} else if($commenttype == 'dislike') {
						$comment->dislikeComment($currentuser);
					}
				}
				$hash = $comment->getId();
			} else {
				$hash = '';
			}

			$currentuser->syncLdap(); // Update email etc.

			// Close the session here, as we do not want lingering sessions on the auth server
			$session = $currentuser->stashSession();

			return array("session" => $session, "hash" => $hash);
		} else {
			throw new Exceptions\InternalException("Invalid credentials");
			// Catch this elsewhere
		}
	}

	function POST($matches) {
		if(isset($_POST['username']) && isset($_POST['password'])) {
			try {
				$session = self::createSession($_POST['username'], $_POST['password'], $_POST['commenttype'], $_POST['comment']);

		 		Utility::redirect(STANDARD_URL.'login/', array(
					'session' => $session['session'],
					'remember' => $_POST['remember'],
					'goto' => $_GET['goto'],
					'hash' => $session['hash']
				), $session['hash']);
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
	 * Authenticate user
	 */
	static function authenticate($username, $password) {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

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
			set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext) {
				throw new \FelixOnline\Exceptions\ErrorHandlerException($errstr, array('errno' => $errno, 'file' => $errfile, 'line' => $errline, 'context' => $errcontext));
			});
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
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		$currentuser->resetSession();
		$currentuser->removeCookie();
	}
}
