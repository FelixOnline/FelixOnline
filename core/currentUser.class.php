<?php
/*
 * Current User class
 */
class CurrentUser extends User {
    protected $session;
    protected $ip;
    
    function __construct() {
        session_name("felix"); // set session name
        session_start(); // start session
        $this->session = session_id(); // store session id into $session variable
        $this->isLoggedIn();
    }

    public function isLoggedIn() {
        if($_SESSION['felix']['loggedin']){
            parent::__construct($_SESSION['felix']['uname']);
        } else {
            return false;
        }
    }
}
?>
