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
class AuthController extends BaseController {
	private $session; // session id used in login

	/*
	 *
	 */
	function GET($matches) {
		global $currentuser;
		if(isset($_GET['session'])) { // catch login
			$this->session = $_GET['session']; // Auth server's session ID
			
			// check if session is recent and that ip is the same
			if($username = $this->checkLogin($this->session)) {
				// Regenerate our session ID
				$currentuser->resetToGuest();

				// Remove any remaining cookies
				$currentuser->removeCookie();

				// Correct session ID - the one from the auth server is not
				// the one on this server
				$sql = $this->safesql->query("UPDATE login
						SET session_id = '%s'
						WHERE session_id='%s'
						AND logged_in=0
						AND ip='%s'
						AND browser='%s'
						AND valid=1
						AND TIMESTAMPDIFF(SECOND,timestamp,NOW()) <= %i",
						array(session_id(),
							$this->session,
							$_SERVER['REMOTE_ADDR'],
							$_SERVER['HTTP_USER_AGENT'],
							SESSION_LENGTH));

				$this->session = session_id(); // The session ID auth is using
											   // is now the one our session
											   // has
				$this->db->query($sql);

				$currentuser->setUser($username);
				$this->login();
				
				if($_GET['remember'] == 'rememberme') {
					$this->setCookie();
				}
				
				Utility::redirect($_GET['goto']);
			} else {
				throw new LoginException("Internal error", $this->session, LOGIN_EXCEPTION_SESSION);
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
					$currentuser->setUser($_POST['username']); // not needed
					$this->logSession($_POST['username']);
					$session = $currentuser->getSession();
					$currentuser->updateDetails($_POST['username']); // update user details

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
					$params = session_get_cookie_params();
					setcookie(session_name(), '', time() - 42000,
						$params["path"], $params["domain"],
						$params["secure"], $params["httponly"]
					); // Remove session ID
					session_destroy(); // Remove all session data
	
					Utility::redirect(STANDARD_URL.'login/', array(
						'session' => $session,
						'remember' => $_POST['remember'],
						'goto' => $_GET['goto']
					), $hash);
			  } else {
					throw new LoginException("Invalid credentials", $_POST['username'], LOGIN_EXCEPTION_CREDENTIALS);
					// Catch this elsewhere
		 	  }
	 		} catch (LoginException $e) {
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
	 * Private: Log session in database
	 */
	private function logSession($username) {
		global $currentuser;
		$sql = $this->safesql->query("INSERT INTO `login` 
									(
										session_id,
										ip,
										browser,
										user
									) VALUES (%q)",
									array(
										array($currentuser->getSession(),
												$_SERVER['REMOTE_ADDR'],
												$_SERVER['HTTP_USER_AGENT'],
												$username)));

		return $this->db->query($sql);
	}

	/*
	 * Private: Check login
	 * Check login redirection 
	 *
	 * $session - session id
	 *
	 * Returns username of user if successful
	 */
	private function checkLogin($session) {
		$sql = $this->safesql->query("SELECT 
										user, 
										TIMESTAMPDIFF(SECOND,timestamp,NOW()) AS timediff, 
										ip, 
										browser 
									FROM `login` 
									WHERE session_id='%s' 
									AND valid=1 
									AND logged_in=0 
									ORDER BY timediff ASC 
									LIMIT 1",
									array($session));
		$login = $this->db->get_row($sql);
		if(
			$login->timediff <= LOGIN_CHECK_LENGTH 
			&& $login->ip == $_SERVER['REMOTE_ADDR'] 
			&& $login->browser == $_SERVER['HTTP_USER_AGENT']
		) {
			return $login->user;
		} else {
			return false;
		}
	}

	/*
	 * Private: Login
	 * Login current user
	 */
	private function login() {
		global $currentuser;
		$_SESSION['felix']['vname'] = $currentuser->getName();
		//$_SESSION['felix']['name'] = $currentuser->getForename();
		$_SESSION['felix']['uname'] = $currentuser->getUser();
		$_SESSION['felix']['loggedin'] = true;
		$this->setLoggedIn();
		$this->destroyOldSessions();
	}

	/*
	 * Private: Logout
	 */
	private function logout() {
		global $currentuser;
		$this->destroySession();
		$currentuser->resetToGuest();

		$currentuser->removeCookie();
	}

	/*
	 * Private: Destroy the current session
	 */
	private function destroySession() {
		global $currentuser;
				
		$sql = $this->safesql->query("UPDATE login
				SET valid = 0
				WHERE session_id='%s'
				AND ip='%s'
				AND browser='%s'
				AND user='%s'",
				array($currentuser->getSession(),
					$_SERVER['REMOTE_ADDR'],
					$_SERVER['HTTP_USER_AGENT'],
					$currentuser->getUser()));

		return $this->db->query($sql);
	}

	/*
	 * Private: Destroy all user sessions
	 */
	private function destroySessions() {
		global $currentuser;

		$sql = $this->safesql->query("UPDATE `login` 
				SET valid = 0
				WHERE user='%s'",
				array($currentuser->getUser()));
				
		return $this->db->query($sql);
	}

	/*
	 * Private: Destroy old sessions
	 */
	private function destroyOldSessions() {
		global $currentuser;
		$sql = "DELETE FROM cookies 
				WHERE UNIX_TIMESTAMP() > UNIX_TIMESTAMP(expires)
		";
		$this->db->query($sql);
		$sql = $this->safesql->query("UPDATE `login` 
				SET valid = 0
				WHERE user='%s'
				AND logged_in=0
				OR TIMESTAMPDIFF(SECOND,timestamp,NOW()) > %i",
				array($currentuser->getUser(),
						SESSION_LENGTH));

		return $this->db->query($sql);
	}
	
	/*
	 * Private: Get user from session
	 * Get the user from the session id
	 */
	private function getUserFromSession($session) {
		$sql = $this->safesql->query("SELECT user FROM login WHERE session_id='%s'", array($session));
		return $this->db->get_var($sql);
	}

	/*
	 * Private: Set session as logged in
	 */
	private function setLoggedIn() {
		global $currentuser;
		$sql = $this->safesql->query("UPDATE 
										login 
									SET 
										logged_in=1 
									WHERE 
										user='%s' 
										AND session_id = '%s'",
									array($currentuser->getUser(), $this->session));

		return $this->db->query($sql);
	}

	/*
	 * Private: Create cookie
	 */
	private function setCookie() {
		global $currentuser;

		$hash = hash('sha256', mt_rand());

		$expiry_time = time() + COOKIE_LENGTH;

		setcookie('felixonline', $hash, $expiry_time, '/');
		$sql = $this->safesql->query("INSERT INTO `cookies` 
									(
										hash,
										user,
										expires
									) VALUES (
										'%s',
										'%s',
										FROM_UNIXTIME(%i)
									)",
									array($hash, $currentuser->getUser(), $expiry_time));

		return $this->db->query($sql);
	}

}
