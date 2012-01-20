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
     * Private: Check if user has just logged in
     */

    /*
     * Public: Check if user is currently logged in
     *
     * Returns boolean
     */
    public function isLoggedIn() {
        if($_SESSION['felix']['loggedin']){
            return $_SESSION['felix']['uname'];
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
        /* update user details */
        $this->updateName();
        $sql = "INSERT INTO `user` 
            (user,name,visits,ip) 
            VALUES (
                '$username',
                '".$this->getName()."',
                1,
                '".$_SERVER['REMOTE_ADDR']."'
            ) 
            ON DUPLICATE KEY 
            UPDATE 
                name='".$this->getName()."',
                visits=visits+1,
                ip='".$_SERVER['REMOTE_ADDR']."',
                timestamp=NOW()";
        return $this->db->query($sql);
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
     * Update user's name from ldap
     */
    private function updateName() {
        if(!LOCAL) {
            $ds = ldap_connect("addressbook.ic.ac.uk");
            $r = ldap_bind($ds);
            $justthese = array("gecos");
            $sr = ldap_search($ds, "ou=People, ou=everyone, dc=ic, dc=ac, dc=uk", "uid=$uname", $justthese);
            $info = ldap_get_entries($ds, $sr);
            if ($info["count"] > 0)
                $this->setName($info[0]['gecos'][0]);
            else
                return false;
        }
    }
}
?>
