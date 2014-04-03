<?php
/*
 * Current User class
 */
class CurrentUser extends User {
	protected $session; // current session id
	protected $ip; // user ip address
	
	/*
	 * Create current user object
	 * Store session id and ip address into object
	 */
	function __construct() {
		global $db;
		global $safesql;
		$this->db = $db;
		$this->safesql = $safesql;

		session_name("felix"); // set session name
		if (session_status() == PHP_SESSION_NONE) {
			session_start(); // start session
		}
		$this->session = session_id(); // store session id into $session variable
		$this->ip = $_SERVER['REMOTE_ADDR'];
		if($this->isLoggedIn()) {
			$this->setUser($this->isLoggedIn());
		}
	}

	/*
	 * Public: Resets the session cookie, regenerating its ID, and ensures old session data is removed
	 */
	public function resetToGuest() {
		// the true parameter clears the current session
		session_destroy();
		session_start();
		session_regenerate_id(true);

		$this->session = session_id();
	}

	/*
	 * Public: Removes the permanent cookie, and removes associated database entries
	 */
	public function removeCookie() {
		if (array_key_exists('felixonline', $_COOKIE)) {
			$sql = $this->safesql->query(
				"DELETE FROM cookies
				WHERE hash = '%s'",
				array(
					$_COOKIE['felixonline']
				));

			$this->db->query($sql);
		}

		// also remove any expired cookies for anyone
		// TODO move to cron
		$sql = $this->safesql->query(
			"DELETE FROM cookies
			WHERE expires < NOW()", array());

		$this->db->query($sql);

		setcookie('felixonline', '', time() - 42000, '/');
	}

	/*
	 * Public: Check if user is currently logged in
	 *
	 * Returns boolean
	 */
	public function isLoggedIn() {
		if(isset($_SESSION['felix']) && $_SESSION['felix']['loggedin'] && $this->isSessionRecent()){
			return $_SESSION['felix']['uname'];
		} else {
			// n.b. the session is cleared by isSessionRecent if invalid
			
			return $this->loginFromCookie();
		}
	}

	/*
	 * Public: Check if there is a valid permanent cookie, if so log in with it
	 *
	 * Returns false if failed, username otherwise
	 * TODO make sure there isn't redundant code
	 */
	public function loginFromCookie() {
		// is there a cookie?
		if (!array_key_exists('felixonline', $_COOKIE)) {
			return false;
		}

		$cookiehash = $_COOKIE['felixonline'];

		$sql = $this->safesql->query(
			"SELECT user
			FROM `cookies`
			WHERE hash='%s'
			AND UNIX_TIMESTAMP(expires) > UNIX_TIMESTAMP()
			ORDER BY expires ASC
			LIMIT 1",
			array(
				$cookiehash
			));

		$cookie = $this->db->get_row($sql);
		if (!$cookie) {
			$this->removeCookie();
			return false;
		}

		$username = $cookie->user;

		// Reset session ID
		$this->resetToGuest();

		// Create session
		$sql = $this->safesql->query(
			"INSERT INTO `login` 
			(
				session_id,
				ip,
				browser,
				user,
				logged_in
			) VALUES (
				'%s',
				'%s',
				'%s',
				'%s',
				1
			)",
			array(
				$this->getSession(),
				$_SERVER['REMOTE_ADDR'],
				$_SERVER['HTTP_USER_AGENT'],
				$username,
			));
		$this->db->query($sql);

		parent::__construct($username);

		$_SESSION['felix']['vname'] = $this->getName();
		//$_SESSION['felix']['name'] = $this->getForename();
		$_SESSION['felix']['uname'] = $this->getUser();
		$_SESSION['felix']['loggedin'] = true;

		return $_SESSION['felix']['uname'];
	}

	/*
	 * Public: Check if the session is recent (the last visited time is updated
	 * on every visit, if this is greater than two hours then we need to log in
	 * again, unless the cookie is valid
	 */
	public function isSessionRecent() {
		if (!$_SESSION['felix']['loggedin']) {
			return false; // If we have no session, this method is meaningless.
		}

		$sql = $this->safesql->query(
			"SELECT
				TIMESTAMPDIFF(SECOND,timestamp,NOW()) AS timediff,
				ip,
				browser
			FROM `login`
			WHERE session_id='%s'
			AND logged_in=1
			AND valid=1
			AND user='%s'
			ORDER BY timediff ASC
			LIMIT 1",
			array(
				$this->session,
				$_SESSION['felix']['uname'],
			));

		$user = $this->db->get_row($sql);

		if (
			$user->timediff <= SESSION_LENGTH 
			&& $user->ip == $_SERVER['REMOTE_ADDR']
			&& $user->browser == $_SERVER['HTTP_USER_AGENT']
		) {
			return true;
		} else {
			$this->resetToGuest(); // Clear invalid session data
			// N.B. Do not delete cookies here!! If the session is invalid
			// it may have expired, but then we may be able to log in again
			// from the cookie
			return false;
		}
	}

	/*
	 * Public: Set user
	 */
	public function setUser($username) {
		$username = strtolower($username);
		
		try {
			parent::__construct($username);
		} catch (NotFoundException $e) {
			// User does not yet exist in our database...
			// It'll be created later so carry on
		}

		$sql = $this->safesql->query(
			"UPDATE login
			SET timestamp = NOW()
			WHERE session_id='%s'
			AND logged_in=1
			AND ip='%s'
			AND browser='%s'
			AND valid=1
			AND TIMESTAMPDIFF(SECOND,timestamp,NOW()) <= %i",
			array(
				$this->session,
				$_SERVER['REMOTE_ADDR'],
				$_SERVER['HTTP_USER_AGENT'],
				SESSION_LENGTH,
			));
		$this->db->query($sql); // if this fails, it doesn't matter, we will
								// just be auto logged out after a while
		
	}

	public function getSession() {
		return $this->session;
	}

	public function getUser() {
		if(isset($this->fields['user']) && $this->fields['user']) {
			return $this->fields['user'];
		} else {
			return false;
		}
	}

	/*
	 * Public: Update user details
	 */
	public function updateDetails($username) {
		$username = strtolower($username);

		/* update user details */
		$name = $this->updateName($username);
		$info = $this->updateInfo($username);

		// TODO can you get this from ldap?
		$email = $username . '@imperial.ac.uk';

		$sql = $this->safesql->query(
			"INSERT INTO `user` 
				(user,name,visits,ip,info,email) 
			VALUES (
				'%s',
				'%s',
				1,
				'%s',
				'%s',
				'%s'
			) 
			ON DUPLICATE KEY 
			UPDATE 
				name='%s',
				visits=visits+1,
				ip='%s',
				timestamp=NOW(),
				info='%s'",
			array(
				$username,
				$name,
				$_SERVER['REMOTE_ADDR'],
				$info,
				$email,
				$name,
				$_SERVER['REMOTE_ADDR'],
				$info,
			));
			// note that this updated the last access time and the ip
			// of the last access for this user, this is separate from the
			// session
		return $this->db->query($sql);
	}

	/*
	 * Update user's name from ldap
	 */
	private function updateName($uname) {
		if(!LOCAL) {
			$ds = ldap_connect("addressbook.ic.ac.uk");
			$r = ldap_bind($ds);
			$justthese = array("gecos");
			$sr = ldap_search(
				$ds,
				"ou=People, ou=everyone, dc=ic, dc=ac, dc=uk",
				"uid=$uname",
				$justthese
			);
			$info = ldap_get_entries($ds, $sr);
			if ($info["count"] > 0) {
				$this->setName($info[0]['gecos'][0]);
				return ($info[0]['gecos'][0]);
			} else {
				return false;
			}
		} else {
			$name = $uname;
			try {
				$name = $this->getName();
			} catch (InternalException $e) {
				// User does not yet exist in our database...
				// It'll be created later so carry on
			}
			return $name;
		}
	}

	/*
	 * Update user's info from ldap
	 *
	 * Returns json encoded array
	 */
	private function updateInfo($uname) {
		$info = '';
		if(!LOCAL) { // if on union server
			$ds = ldap_connect("addressbook.ic.ac.uk");
			$r = ldap_bind($ds);
			$justthese = array("o");
			$sr = ldap_search(
				$ds,
				"ou=People, ou=shibboleth, dc=ic, dc=ac, dc=uk",
				"uid=$uname",
				$justthese
			);
			$info = ldap_get_entries($ds, $sr);
			if ($info["count"] > 0) {
				$info = json_encode(explode('|', $info[0]['o'][0]));
				$this->setInfo($info);
			} else {
				return false;
			}
		}
		return $info;
	}

	public function getRole() {
		if(isset($this->fields['role']) && $this->fields['role']) {
			return $this->fields['role'];
		} else {
			return 0;
		}
	}
}
