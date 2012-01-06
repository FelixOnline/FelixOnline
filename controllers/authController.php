<?php
/*
 * Auth controller
 * Handles authenticating users
 */
class AuthController extends BaseController {
    /*
     *
     */
    function GET($matches) {
        var_dump($matches);
        echo Utility::currentPageURL();
    }

    /*
     * 
     */
    function POST($matches) {
        global $currentuser;
        $this->login($_POST['username'], $_POST['password']);
    }

    /*
     * Private: Login user
     */
    private function login($username, $password) {
        global $currentuser;
        if(!LOCAL) { // if executed on union servers
            /* authenticate user using global function pam_auth - returns true if user is Imperial student, false if not (Union server only) */
            $check = pam_auth(
                $_POST['username'], 
                $_POST['password']
            );
        } else {
            $check = true;
        }
        if($check) {
            $currentuser->setUser($_POST['username']);
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
     * Private: Redirect to location with specific GET params
     *
     * $goto - url to redirect to
     * $params - array of parameters to add to url
     */
    private function redirect($goto, $params) {
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
        header('Location: '.$goto);
    }

    private function logSession() {
        global $currentuser;
        $sql = "INSERT INTO `login` (session_id,user) VALUES ('".$currentuser->getSession()."','".$currentuser->getUser()."')";
        return $db->query($sql);
    }
}
?>
