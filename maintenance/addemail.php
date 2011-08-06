<?php

	require_once('../preview/inc/common.inc.php');
	
	$email = mysql_real_escape_string($_POST['email']);
	
	$sql = "INSERT INTO `preview_email` (email,ip) VALUES ('$email','".$_SERVER['REMOTE_ADDR']."')"; 
	mysql_query($sql,$cid) or die(mysql_error());
	
	// Send confirmation email
	$headers = "From: ".EMAIL_FROM_ADDR."\r\n" .
    'Reply-To: '.EMAIL_REPLYTO_ADDR."\r\n" .
    'X-Mailer: PHP/' . phpversion();
	
	$to = $email;
	$subject = "Confirmation of subscription to Felix Online";
	$body = "Hey there!\n\nThank you for subscribing to receive updates on the new Felix website. We are currently hard at work getting everything ready and you will get to see it soon!\n\nLots of love,\nFelix\n\nfelix@imperial.ac.uk\nhttp://www.facebook.com/FelixImperial\nhttp://twitter.com/feliximperial\n";
	mail($to,$subject,$body,$headers);
	echo $email;
?>