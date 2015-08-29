<?php

$hooks->addAction('contact_us', 'contact_us');
function contact_us($data) {
	$name = $data['name'];
	$emailaddress = $data['email'];
	$message = $data['message'];
	$app = \FelixOnline\Core\App::getInstance();
	
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

	$email = \Swift_Message::newInstance(); 

	$email->setTo('felix@imperial.ac.uk');

	if($name)
		$email->setSubject($name.' sent a message');
	else
		$email->setSubject('Anonymous message');
	
	$email->setBody($message, 'text/plain');
	
	if($emailaddress) {
		if($name) {
			$email->setFrom(array($emailaddress => $name));
		} else {
			$email->setFrom(array($emailaddress));
		}
	}
	
	// Mail it
	$app['email']->send($email);
	
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

$hooks->addAction('report_abuse', 'report_abuse');
function report_abuse($data) {
	try {
		$comment = $data['comment'];
		$comment = new \FelixOnline\Core\Comment($comment);
		$count = $comment->reportAbuse();
		return array('msg' => 'Thank you, your report has been received. We will look into your report as soon as possible.');
	} catch(Exception $e) {
		return array('msg' => 'Sorry, that didn\'t work. Please try again later or contact us at felix@imperial.ac.uk.');
	}
}

$hooks->addAction('profile_change', 'profile_change');
function profile_change($data) {
	global $currentuser;
	if($currentuser->isLoggedIn()) {
		$user = $currentuser;
		try {
			Validator::Check(array(
				'weburl' => $data['weburl'],
				'facebook' => $data['facebook'],
				'webname' => $data['webname'],
				'twitter' => $data['twitter']
			), array (
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
			
			return array("error" => true,
				"details" => 'There has been an issue with some of your data, please check the highlighted fields and try again',
				"validator" => true,
				"validator_data" => $failed_fields);
		}
		
		if(array_key_exists('email', $data) && $data['email'] == '1') {
			$showEmail = true;
		} else {
			$showEmail = false;
		}

		if(array_key_exists('ldap', $data) && $data['ldap'] == '1') {
			$showLdap = true;
		} else {
			$showLdap = false;
		}

		$user->setUser($currentuser->getUser());
		$user->setShowEmail($showEmail);
		$user->setFacebook(Utility::addhttp($data['facebook']));
		$user->setTwitter($data['twitter']);
		$user->setWebsitename($data['webname']);
		$user->setWebsiteurl(Utility::addhttp($data['weburl']));
		$user->setDescription($data['bio']);
		$user->setShowLdap($showLdap);
		$user->save();

		return (array('error' => false));
	} else {
		return (array('error' => true, 'details' => 'Not logged in'));
	}
}

$hooks->addAction('get_category_page', 'get_category_page');
function get_category_page($data) {
	require_once(BASE_DIRECTORY.'/controllers/baseController.php');
	require_once(BASE_DIRECTORY.'/controllers/categoryController.php');

	try {
		$data = CategoryController::fetch($data['key'], $data['page']);
	} catch(\Exception $e) {
		return (array('error' => true, 'details' => $e->getMessage()));
	}

	$theme = new \FelixOnline\Core\Theme(2014);

	$theme->appendData(array(
		'category' => $data['category'],
		'pagenum' => $data['pagenum'],
		'articles' => $data['articles'],
		'pages' => $data['pages'],
	));

	// Render the output to a buffer
	ob_start();

	$theme->setHierarchy(array(
		$data['category']->getCat() // category-{cat}.php
	));

	$theme->render('components/category_page');

	$theme->render('components/pagination', array(
			'pagenum' => $data['pagenum'],
			'class' => $data['category'],
			'pages' => $data['pages'],
			'span' => \FelixOnline\Core\Settings::get('number_of_pages_in_page_list'),
			'type' => 'category',
			'key' => $data['category']->getCat()));

	$output = ob_get_contents();

	ob_end_clean();

	return (array('error' => false, 'content' => $output));
}

$hooks->addAction('get_user_page', 'get_user_page');
function get_user_page($data) {
	require_once(BASE_DIRECTORY.'/controllers/baseController.php');
	require_once(BASE_DIRECTORY.'/controllers/userController.php');

	try {
		$data = UserController::fetch($data['key'], $data['page']);
	} catch(\Exception $e) {
		return (array('error' => true, 'details' => $e->getMessage()));
	}

	$theme = new \FelixOnline\Core\Theme(2014);

	$theme->appendData(array(
		'user' => $data['user'],
		'pagenum' => $data['pagenum'],
		'articles' => $data['articles'],
		'article_count' => $data['articleCount'],
		'pages' => $data['pages']
	));


	// Render the output to a buffer
	ob_start();

	$theme->setHierarchy(array(
		$data['user']->getUser() // user_page-{user}.php
	));

	$theme->render('components/user_page');

	$theme->render('components/pagination', array(
					'pagenum' => $data['pagenum'],
					'class' => $data['user'],
					'pages' => $data['pages'],
					'span' => \FelixOnline\Core\Settings::get('articles_per_user_page'),
					'type' => 'user',
					'key' => $data['user']->getUser()));

	$output = ob_get_contents();

	ob_end_clean();

	return (array('error' => false, 'content' => $output));
}

$hooks->addAction('get_search_page', 'get_search_page');
function get_search_page($data) {
	require_once(BASE_DIRECTORY.'/controllers/baseController.php');
	require_once(BASE_DIRECTORY.'/controllers/searchController.php');

	try {
		$data = SearchController::fetch($data['key'], $data['page']);
	} catch(\Exception $e) {
		return (array('error' => true, 'details' => $e->getMessage()));
	}

	$theme = new \FelixOnline\Core\Theme(2014);

	$theme->appendData(array(
		'articles' => $data['articles']['articles'],
		'article_count' => $data['articles']['count'],
		'query' => $data['query'],
		'page' => $data['page']
	));


	// Render the output to a buffer
	ob_start();

	$theme->render('components/search_page');

	$theme->render('components/pagination_search', array(
		'page' => $data['page'],
		'query' => $data['query']
	));

	$output = ob_get_contents();

	ob_end_clean();

	return (array('error' => false, 'content' => $output));
}
