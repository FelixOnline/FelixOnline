<?php 
	require_once('mobiledetect.php');
	
	require_once('inc/common.inc.php'); 
	
	/* AUTHENTICATION */
	if (strstr($_SERVER['HTTP_HOST'],"union.ic.ac.uk") !== false) 
		header("Location: ".STANDARD_URL.substr($_SERVER['REQUEST_URI'],(1+strrpos($_SERVER['REQUEST_URI'],"/"))));
	session_name("felix");
	session_start();
	//header('Content-type: text/html; charset=utf-8');
	$session = session_id();
	if ($_SERVER['SERVER_NAME'] == AUTHENTICATION_SERVER) {
		if ($_POST['login']) {
			if (pam_auth($_POST['username'], $_POST['password'])) {
				set_session($session,$_POST['username']);
				$loc = strpos($_GET['goto'],'?') ? ('Location: '.$_GET['goto'].'&session='.$session) : ('Location: '.$_GET['goto'].'?session='.$session);
				if($_POST['remember'])
					$loc .= '&remember=true';
				if($_POST['comment']) {
					if ($_POST['commenttype'] == 'like') $type = 1;
					else $type = 0;
					like_comment($_POST['comment'],$_POST['username'],$type);
					$loc .= '#'.$_POST['comment'];
				}
				header($loc);
				return;
			}
			else {
				logout();
				$loc = strpos($_GET['goto'],'?') ? 'Location: '.$_GET['goto'].'&session='.$session.'&login=FAIL' : 'Location: '.$_GET['goto'].'?session='.$session.'&login=FAIL';
				header($loc);
				return;
			}
		}
		else
			header('Location: '.STANDARD_URL);
	}
	/* Check if user has been remembered */
	if(isset($_COOKIE['felixonline']))
		re_login($_COOKIE['felixonline']);
		
	if (($session = $_GET['session']) && is_session_recent($session) && ($_GET['login'] != 'FAIL')) {
		login(get_user_from_session($session));
		if (isset($_GET['remember']))
			setcookie('felixonline', $_SESSION['felix']['uname'], time()+60*60*24*30, "/");
	}
	
	if ($_POST['logout'])
		logout();

	$session_param1 = "?session=".session_id();
	$session_param2 = "&session=".session_id();
?>

<?php include('header.php'); ?>

<?php if ($_GET['media']) {
		include_once('media.php');
		} else if ($_GET['issuearchive']) {
			include_once('archive.php');
		} else if ($_GET['publications']) {
			include_once('publications.php');
		} else {
?>
	
<?php include('navigation.php'); ?>
	
<?php 
	//  Change display dependant on $_GET variable
	$get = array_shift(array_keys($_GET));
	switch ($get) {
		case "article":
			include_once('page.php');
			break;
		case "cat":
			include_once('section.php');
			break;
		case "id":
			include_once('user.php');
			break;
		case "media":
			include_once('media.php');
			break;
		case "search":
			include_once('search.php');
			break;
		case "contact":
			include_once('contact.php');
			break;
		case "":
			include_once('frontpage.php'); 
			break;
		case "session":
			include_once('frontpage.php'); 
			break;
		default:
			include_once('404.php');
			break;
	}
	
?>

<?php } // end of media page statement?>

<?php include('footer.php'); ?>