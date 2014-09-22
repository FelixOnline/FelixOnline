<?php

$hooks->addAction('contact_us', 'contact_us');
function contact_us($data) {
	$name = $data['name'];
	$emailaddress = $data['email'];
	$message = $data['message'];
	
	try {
		Validator::Check(array(
			'email' => $emailaddress,
			'message' => $message
		), array (
			'email' => array(
				Validator::validator_email => null
			),
			'message' => array(
				Validator::validator_notnull => null
			)
		));
	} catch (ValidatorException $e) {
		$failed_fields = array();
		foreach($e->getData() as $failedField => $data) {
			$failed_fields[] = $failedField;
		}
		
		return array(error => true, details => 'There has been an issue with some of your data, please check the highlighted fields and try again', validator => true, validator_data => $failed_fields);
	}

	$email = new Email(); 

	$email->setTo('felix@imperial.ac.uk');

	if($name)
		$email->setSubject($name.' sent a message');
	else
		$email->setSubject('Anonymous message');
	
	$email->setContent($message);
	
	if($emailaddress) {
		if($name) {
			$email->setFrom($emailaddress, $name);
		} else {
			$email->setFrom($emailaddress);
		}
		$email->setReplyTo($emailaddress);
	}
	
	// Mail it
	$email->send();
	
	return array('error' => false, 'success' => 'Your message has been sent, thank you!');
}

$hooks->addAction('like_comment', 'like_comment');
function like_comment($data) {
	global $currentuser;
	if ($currentuser->isLoggedIn()) {
		$comment = $data['comment'];
		$comment = new \FelixOnline\Core\Comment($comment);
		$count = $comment->likeComment($currentuser);
		return array('count' => $count);
	} else {
		return (array('error' => true, 'details' => 'Not logged in'));
	}
}

$hooks->addAction('dislike_comment', 'dislike_comment');
function dislike_comment($data) {
	global $currentuser;
	if ($currentuser->isLoggedIn()) {
		$comment = $data['comment'];
		$comment = new \FelixOnline\Core\Comment($comment);
		$count = $comment->dislikeComment($currentuser);
		return array('count' => $count);
	} else {
		return (array('error' => true, 'details' => 'Not logged in'));
	}
}

$hooks->addAction('profile_change', 'profile_change');
function profile_change($data) {
	global $currentuser;
	if($currentuser->isLoggedIn()) {
		$user = new User();
		try {
			Validator::Check(array(
				'email' => $data['email'],
				'weburl' => $data['weburl'],
				'facebook' => $data['facebook'],
				'webname' => $data['webname'],
				'twitter' => $data['twitter']
			), array (
				'email' => array(
					Validator::validator_email => null,
					Validator::validator_maxlength => 50
				),
				'weburl' => array(
					Validator::validator_maxlength => 50,
					Validator::validator_url => null
				),
				'facebook' => array(
					Validator::validator_maxlength => 50,
					Validator::validator_url => null
				),
				'webname' => array(
					Validator::validator_maxlength => 50,
				),
				'twitter' => array(
					Validator::validator_maxlength => 50
				)
			));
		} catch (ValidatorException $e) {
			$failed_fields = array();
			foreach($e->getData() as $failedField => $data) {
				$failed_fields[] = $failedField;
			}
			
			return array(error => true, details => 'There has been an issue with some of your data, please check the highlighted fields and try again', validator => true, validator_data => $failed_fields);
		}
		
		$user->setUser($currentuser->getUser());
		$user->setDescription($data['desc']);
		$user->setEmail($data['email']);
		$user->setFacebook(Utility::addhttp($data['facebook']));
		$user->setTwitter($data['twitter']);
		$user->setWebsitename($data['webname']);
		$user->setWebsiteurl(Utility::addhttp($data['weburl']));
		$user->save();
		return (array('error' => false));
	} else {
		return (array('error' => true, 'details' => 'Not logged in'));
	}
}
