<?php
	require_once('inc/common.inc.php'); 
	
	// PHP file to post to using ajax requests
	
	$type= $_POST['type'];

	switch ($type) {
		case 'profilechange':
			change_user();
			break;
		case 'like':
			like_comment_ajax($_POST['user'], $_POST['comment']);
			break;
		case 'dislike':
			dislike_comment_ajax($_POST['user'], $_POST['comment']);
			break;
		case 'sendmessage':
			send_message();
			break;
		case 'other':
			break;
	}

	
	function change_user() {
		$desc = mysql_real_escape_string($_POST['desc']);
		$facebook = mysql_real_escape_string($_POST['facebook']);
		$twitter = mysql_real_escape_string($_POST['twitter']);
		$email = mysql_real_escape_string($_POST['email']);
		$show_email = $_POST['show_email'];
		$webname = mysql_real_escape_string($_POST['webname']);
		if($_POST['weburl'])
			$weburl = addhttp(mysql_real_escape_string($_POST['weburl']));
		$user = mysql_real_escape_string($_POST['user']);
		
		//echo $facebook;
		
		$sql = "UPDATE `user` SET description = '$desc', email = '$email', facebook = '$facebook', twitter = '$twitter', websitename = '$webname', websiteurl = '$weburl' WHERE user='$user'";
		$result = mysql_query($sql) 
			or die(mysql_error()); 
		echo 'Updated!';
	}
	
	function like_comment_ajax($user, $comment) {
        $comment = new Comment($comment);
        $count = $comment->likeComment($user);
		echo $count;
	}
	
	function dislike_comment_ajax($user, $comment) {
        $comment = new Comment($comment);
        $count = $comment->dislikeComment($user);
		echo $count;
	}
	
	function send_message() {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$message = $_POST['message'];
	
		//echo $name.' '.$message;
		
		// multiple recipients
		$to  = 'felix@imperial.ac.uk';

		// subject
		if($name)
			$subject = $name.' sent a message';
		else
			$subject = 'Anonymous message';
		
		$message = wordwrap($message, 70);
		
		if ($email) 
			$headers = 'From: '.$name.' <'.$email.'>' . "\r\n";
		
		// Mail it
		mail($to, $subject, $message, $headers);
	}
?>
