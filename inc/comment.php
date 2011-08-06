<?php
	global $cid;
	$extfailflag = false;
	$article = $_GET['article'];
	
	// ReCapatcha
	$publickey = "6LdbYL4SAAAAAKufkLBCRiEmbTRawSFaWDDJwQwB";
	$privatekey = "6LdbYL4SAAAAAOAUmQ4QSXUbSYm1LIkgbvqZBWXU";
	
	$uname = is_logged_in();
	$errorinsert = false;
	$errorduplicate = false;
	
	// user comment 
	if ($_POST['articlecomment']) {
		$comment = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['comment']));
		$replyName = $_POST['replyName'];
		$replyComment = $_POST['replyComment'];
				
		if (!check_comment_exists($article,$uname,$comment)) {
			if ($id = insert_comment($article,$uname,$comment,$replyName,$replyComment)) {
				// redirect to comment
				header('Location: '.full_article_url($article).'#comment'.$id);
			} else {
				$errorinsert = true;
			}
		} else
			$errorduplicate = true;
	
	// external comment
	} else if ($_POST['articlecomment_ext']) {
		$comment = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['comment']));
		$name = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['name']));
		$email = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['email']));
		$url = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['url']));
		$replyName = $_POST['replyName']; // get comment author
		$replyComment = $_POST['replyComment']; // get comment id
		
		// check aksimet spam
		require_once('akismet.class.php');
		
		$WordPressAPIKey = '4c2ddc0022f0';
		$MyBlogURL = 'http://felixonline.co.uk';
		 
		$akismet = new Akismet($MyBlogURL ,$WordPressAPIKey);
		$akismet->setCommentAuthor($name);
		$akismet->setCommentAuthorEmail($email);
		$akismet->setCommentAuthorURL($url);
		$akismet->setCommentContent($comment);
		$akismet->setPermalink(full_article_url($article));
		 
		if($akismet->isCommentSpam()) {
			// store the comment but mark it as spam (in case of a mis-diagnosis)
			if ($id = insert_comment_spam($article,$name,$comment,$replyName,$replyComment)) {
				// redirect to comment 
				header('Location: '.full_article_url($article).'#comment'.$id);
			} else {
				$errorinsert = true;
			}
		} else {
			// store the comment normally
			if($id = insert_comment_external($article,$name,$comment,$replyName,$replyComment)) {
				// redirect to comment 
				header('Location: '.full_article_url($article).'#comment'.$id);
			}
		}
	}
?>
