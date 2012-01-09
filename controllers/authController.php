<?php
/*
 * Auth controller
 * Handles authenticating users
 */
class AuthController extends BaseController {
    private $session; // session id used in login

    /*
     *
     */
    function GET($matches) {
        global $currentuser;
        var_dump($matches);
        //var_dump($_SERVER);
        if(isset($_GET['session'])) { // catch login
            $this->session = $_GET['session'];
            // check if session is recent and that ip is the same
            if($username = $this->checkLogin($this->session)) {
                $currentuser->setUser($username);
                $this->login();
                $this->redirect($_GET['goto']);
            }
        }
        echo Utility::currentPageURL();
    }

    /*
     * 
     */
    function POST($matches) {
        global $currentuser;
        if($this->authenticate($_POST['username'], $_POST['password'])) {
            $currentuser->setUser($_POST['username']); // not needed
            $this->logSession();
            $this->redirect(STANDARD_URL.'login', array(
                'session' => $currentuser->getSession(),
                'remember' => $_POST['remember'],
                'goto' => $_GET['goto']
            ));
        } else {
            // send back to $goto but with fail flag
            $this->redirect($_GET['goto'], array(
                'login' => 'fail'
            ));
        }
    }

    /*
     * Private: Authenticate user
     */
    private function authenticate($username, $password) {
        global $currentuser;
        if(!LOCAL) { // if executed on union servers
            /* authenticate user using global function pam_auth - returns true if user is Imperial student, false if not (Union server only) */
            $check = pam_auth(
                $_POST['username'], 
                $_POST['password']
            );
        } else {
            $check = true; // override check
        }
        return $check;
    }

    /*
     * Private: Log session in database
     */
    private function logSession() {
        global $currentuser;
        $sql = "INSERT INTO `login` (session_id,ip,browser,user) VALUES ('".$currentuser->getSession()."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['HTTP_USER_AGENT']."','".$currentuser->getUser()."')";
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
        $sql = "SELECT user, TIMESTAMPDIFF(SECOND,timestamp,NOW()) AS timediff, ip, browser FROM `login` WHERE session_id='$session' AND valid=1 AND logged_in=0 ORDER BY timediff ASC LIMIT 1";
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
        $_SESSION['felix']['name'] = $currentuser->getForename();
        $_SESSION['felix']['uname'] = $currentuser->getUser();
        $_SESSION['felix']['loggedin'] = true;
        $this->setLoggedIn();
        //destroy_old_sessions($username);
        //update_login_name($username,$_SESSION['felix']['vname']);
    }

    /*
     * Private: Redirect to location with specific GET params
     *
     * $goto - url to redirect to
     * $params - array of parameters to add to url
     */
    private function redirect($goto, $params = NULL) {
        if($params) {
            $i = 0;
            if(!$goto) $goto = STANDARD_URL;
            foreach($params as $key => $value) {
                if(strpos($goto,'?')) {
                    $goto .= '&'.$key.'='.$value;
                } else if ($i == 0) {
                    $goto .= '?'.$key.'='.$value;
                }
                $i++;
            }
        }
        header('Location: '.$goto);
    }

    /*
     * Private: Get user from session
     * Get the user from the session id
     */
    private function getUserFromSession($session) {
        $sql = "SELECT user FROM login WHERE session_id='".$session."'";
        return $this->db->get_var($sql);
    }

    /*
     * Private: Set session as logged in
     */
    private function setLoggedIn() {
        global $currentuser;
        $sql = "UPDATE login SET logged_in=1 WHERE user='".$currentuser->getUser()."' AND session_id = '".$this->session."'";
        return $this->db->query($sql);
    }

    /*
     * Private: Destroy old sessions
     */
}
?>
