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
                $sql = "UPDATE login
                        SET session_id = '".session_id()."'
                        WHERE session_id='".$this->session."'
                        AND logged_in=0
                        AND ip='".$_SERVER['REMOTE_ADDR']."'
                        AND browser='".$_SERVER['HTTP_USER_AGENT']."'
                        AND valid=1
                        AND TIMESTAMPDIFF(SECOND,timestamp,NOW()) <=
                            ".SESSION_LENGTH."
                ";
                
                $this->session = session_id(); // The session ID auth is using
                                               // is now the one our session
                                               // has
                $this->db->query($sql);

                $currentuser->setUser($username);
                $this->login();
                
                if(isset($_GET['remember'])) {
                    $this->setCookie();
                }
                
                $this->redirect($_GET['goto']);
            } else {
                echo 'Fail';
            }
        }
        echo Utility::currentPageURL();
    }

    /*
     * 
     */
    function POST($matches) {
        global $currentuser;
        if(isset($_POST['username']) && isset($_POST['password'])) {
            if($this->authenticate($_POST['username'], $_POST['password'])) {
                $currentuser->setUser($_POST['username']); // not needed
                $this->logSession();
                $session = $currentuser->getSession();
                
                // Close the session here, as we do not want lingering sessions on the auth server
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                ); // Remove session ID
                session_destroy(); // Remove all session data

                $this->redirect(STANDARD_URL.'login', array(
                    'session' => $session,
                    'remember' => $_POST['remember'],
                    'goto' => $_GET['goto']
                ));
            } else {
                // send back to $goto but with fail flag
                $this->redirect($_GET['goto'], array(
                    'login' => 'fail'
                ));
            }
        } else if(isset($_POST['logout'])) {
            $this->logout();
            $this->redirect($_GET['goto']);
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
        $sql = "INSERT INTO `login` 
                (
                    session_id,
                    ip,
                    browser,
                    user
                ) VALUES (
                    '".$currentuser->getSession()."',
                    '".$_SERVER['REMOTE_ADDR']."',
                    '".$_SERVER['HTTP_USER_AGENT']."',
                    '".$currentuser->getUser()."'
                )
        ";
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
        $sql = "SELECT 
                    user, 
                    TIMESTAMPDIFF(SECOND,timestamp,NOW()) AS timediff, 
                    ip, 
                    browser 
                FROM `login` 
                WHERE session_id='$session' 
                AND valid=1 
                AND logged_in=0 
                ORDER BY timediff ASC 
                LIMIT 1
        ";
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
        $this->destroySession($currentuser->getSession());
        $currentuser->resetToGuest();

        if(isset($_COOKIE['felixonline']))
            setcookie("felixonline", "", time(), "/");
        if(isset($_COOKIE['felixonlinesession']))
            setcookie("felixonlinesession", "", time(), "/");
    }

    /*
     * Private: Destroy a session
     */
    private function destroySession() {
        global $currentuser;
        $sql = "DELETE FROM login 
                WHERE user='".$currentuser->getUser()."'
                AND session_id='".$currentuser->getSession()."'
                AND ip='".$_SERVER['REMOTE_ADDR']."'
                AND browser='".$_SERVER['HTTP_USER_AGENT']."'";
        return $this->db->query($sql);
	}

    /*
     * Private: Destroy sessions
     */
    private function destroySessions() {
        global $currentuser;
        $sql = "DELETE FROM login 
                WHERE user='".$currentuser->getUser()."'";
        return $this->db->query($sql);
    }

    /*
     * Private: Destroy old sessions
     */
    private function destroyOldSessions() {
        global $currentuser;
        $sql = "DELETE FROM login 
                WHERE user='".$currentuser->getUser()."'
				AND valid=0
				OR logged_in=0
                OR TIMESTAMPDIFF(SECOND,timestamp,NOW()) >
                    ".SESSION_LENGTH.";
        ";
        return $this->db->query($sql);
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

}
?>
