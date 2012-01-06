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
    }

    /*
     * Private: Check if user has just logged in
     */

    /*
     * Public: Check if user is currently logged in
     *
     * Returns boolean
     */
    public function isLoggedIn() {
        if($_SESSION['felix']['loggedin']){
            //parent::__construct($_SESSION['felix']['uname']);
        } else {
            return false;
        }
    }

    /*
     * Public: Set user
     */
    public function setUser($username) {
        $username = strtolower($username);
        parent::__construct($username);
    }

    public function getSession() {
        return $this->session;
    }
}
?>
