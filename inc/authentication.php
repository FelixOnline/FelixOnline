<?php
/* 
 * AUTHENTICATION 
 *
 * Felix Online user authentication
 *
 */

/* TODO */
if (strstr($_SERVER['HTTP_HOST'],"union.ic.ac.uk") !== false)
    header("Location: ".STANDARD_URL.substr($_SERVER['REQUEST_URI'],(1+strrpos($_SERVER['REQUEST_URI'],"/"))));

session_name("felix"); // set session name
session_start(); // start session
$session = session_id(); // store session id into $session variable

//header('Content-type: text/html; charset=utf-8'); // not needed? TODO

/* Auth start */
if ($_SERVER['SERVER_NAME'] == AUTHENTICATION_SERVER) { // if current server is the same as the defined authentication server (const.inc.php) i.e. dougal.union.ic.ac.uk
    if ($_POST['login']) { // if someone is trying to log in
        if (pam_auth($_POST['username'], $_POST['password'])) { // authenticate user using global function pam_auth - returns true if user is Imperial student, false if not (Union server only)
            set_session($session, $_POST['username']); // assign the username to the session
            //$loc = strpos($_GET['goto'],'?') ? ('Location: '.$_GET['goto'].'&session='.$session) : ('Location: '.$_GET['goto'].'?session='.$session);
            if(strpos($_GET['goto'], '?')) { // if $_GET['goto'] contains a ?
                $loc = ('Location: '.$_GET['goto'].'&session='.$session); // location becomes $_GET['goto'] url with session_id appended
            } else {
                $loc = ('Location: '.$_GET['goto'].'?session='.$session); // location is $_GET['goto'] url with session_id appended as first $_GET
            }
            if($_POST['remember']) { // if the user has chosen to 'remember me' option
                $loc .= '&remember=true'; // append remember flag to location
            }
            if($_POST['comment']) { // if the user was in the process of liking/disliking a comment
                if ($_POST['commenttype'] == 'like') $type = 1; // if it was a like then $type is 1
                else $type = 0;
                like_comment($_POST['comment'],$_POST['username'],$type); // like/dislike the comment 
                $loc .= '#'.$_POST['comment']; // append html anchor of comment to location
            }
            header($loc); // got to $loc (location)
            return;
        } else { // if authentication fails
            logout(); // make sure the there is no residual trace of user in session or cookie
            $loc = strpos($_GET['goto'],'?') ? 'Location: '.$_GET['goto'].'&session='.$session.'&login=FAIL' : 'Location: '.$_GET['goto'].'?session='.$session.'&login=FAIL'; // append session to location and login fail flag
            header($loc); // go to location
            return;
        }
    } else { // if on the authentication server but not logging in
        header('Location: '.STANDARD_URL); // return to homepage
    }
}

if (($session = $_GET['session']) && is_session_recent($session) && ($_GET['login'] != 'FAIL')) {
    login(get_user_from_session($session));
    if (isset($_GET['remember'])) {
        setcookie('felixonline', $_SESSION['felix']['uname'], time()+60*60*24*30, "/");
    }
}

/* Check if user has been remembered */
/*
 * Store the session id in cookie and then check for last 30 days
 * Mysql , time created and time last used
 */
if(isset($_COOKIE['felixonline'])) {
    re_login($_COOKIE['felixonline']); // re_login the user
}

/* Loggin out */
if ($_POST['logout']) {
    logout();
}

/* TODO */
$session_param1 = "?session=".session_id();
$session_param2 = "&session=".session_id();

?>
