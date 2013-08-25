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
		session_name("felix"); // set session name
		session_start(); // start session
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
	function removeCookie() {
		global $db;

		if (array_key_exists('felixonline', $_COOKIE)) {
			$sql = "DELETE FROM cookies
					WHERE hash = '".$db->escape($_COOKIE['felixonline'])."'
			";

			$db->query($sql);
		}

		// also remove any expired cookies for anyone
		$sql = "DELETE FROM cookies
				WHERE expires < NOW();
		";

		$db->query($sql);

		setcookie('felixonline', '', time() - 42000, '/', '.'.STANDARD_SERVER);
	}

	/*
	 * Public: Check if user is currently logged in
	 *
	 * Returns boolean
	 */
	public function isLoggedIn() {
		if($_SESSION['felix']['loggedin'] && $this->isSessionRecent()){
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
		global $db;
		
		// is there a cookie?
		if (!array_key_exists('felixonline', $_COOKIE)) {
			return false;
		}

		$cookiehash = $_COOKIE['felixonline'];

		$sql = "SELECT user
				FROM `cookies`
				WHERE hash='".$db->escape($cookiehash)."'
				AND UNIX_TIMESTAMP(expires) > UNIX_TIMESTAMP()
				ORDER BY expires ASC
				LIMIT 1
		";

		$cookie = $db->get_row($sql);
		if (!$cookie) {
			$this->removeCookie();
			return false;
		}

		$username = $cookie->user;

		// Reset session ID
		$this->resetToGuest();

		// Create session
		$sql = "INSERT INTO `login` 
				(
					session_id,
					ip,
					browser,
					user,
					logged_in
				) VALUES (
					'".$db->escape($this->getSession())."',
					'".$db->escape($_SERVER['REMOTE_ADDR'])."',
					'".$db->escape($_SERVER['HTTP_USER_AGENT'])."',
					'".$db->escape($username)."',
					1
				)
		";
		$db->query($sql);

		parent::__construct($username); // TODO construct doesn't accept a username

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

		global $db;
		$sql = "SELECT
					TIMESTAMPDIFF(SECOND,timestamp,NOW()) AS timediff,
					ip,
					browser
				FROM `login`
				WHERE session_id='".$db->escape($this->session)."'
				AND logged_in=1
				AND valid=1
				AND user='".$db->escape($_SESSION['felix']['uname'])."'
				ORDER BY timediff ASC
				LIMIT 1
		";

		$user = $db->get_row($sql);

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

		$sql = "UPDATE login
				SET timestamp = NOW()
				WHERE session_id='".$this->db->escape($this->session)."'
				AND logged_in=1
				AND ip='".$this->db->escape($_SERVER['REMOTE_ADDR'])."'
				AND browser='".$this->db->escape($_SERVER['HTTP_USER_AGENT'])."'
				AND valid=1
				AND TIMESTAMPDIFF(SECOND,timestamp,NOW()) <=
					".$this->db->escape(SESSION_LENGTH)."
		";
		$this->db->query($sql); // if this fails, it doesn't matter, we will
								// just be auto logged out after a while
		
	}

	public function getSession() {
		return $this->session;
	}

	public function getUser() {
		if($this->fields['user']) {
			return $this->fields['user'];
		} else {
			return false;
		}
	}

	/*
	 * Public: Update user details
	 */
	public function updateDetails($username) {
		/* update user details */
		$name = $this->updateName($username);
		$info = $this->updateInfo($username);

		$sql = "INSERT INTO `user` 
			(user,name,visits,ip,info) 
			VALUES (
				'".$this->db->escape($username)."',
				'".$this->db->escape($name)."',
				1,
				'".$_SERVER['REMOTE_ADDR']."',
				'".$this->db->escape($info)."'
			) 
			ON DUPLICATE KEY 
			UPDATE 
				name='".$this->db->escape($name)."',
				visits=visits+1,
				ip='".$this->db->escape($_SERVER['REMOTE_ADDR'])."',
				timestamp=NOW(),
				info='".$this->db->escape($info)."'
				";
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
			$sr = ldap_search($ds, "ou=People, ou=everyone, dc=ic, dc=ac, dc=uk", "uid=$uname", $justthese);
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
			$ds=ldap_connect("addressbook.ic.ac.uk");
			$r=ldap_bind($ds);
			$justthese = array("o");
			$sr=ldap_search($ds, "ou=People, ou=shibboleth, dc=ic, dc=ac, dc=uk", "uid=$uname", $justthese);
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
		if($this->fields['role']) {
			return $this->fields['role'];
		} else {
			return 0;
		}
	}
}
