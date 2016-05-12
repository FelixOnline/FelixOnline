<?php
// Hook setup
$hooks->addAction('rate_comment', 'rate_comment');
$hooks->addAction('report_abuse', 'report_abuse');
$hooks->addAction('post_comment', 'post_comment');

$hooks->addAction('profile_change', 'profile_change');

$hooks->addAction('poll_vote', 'poll_vote');

$hooks->addAction('get_category_page', 'get_category_page');
$hooks->addAction('get_user_page', 'get_user_page');
$hooks->addAction('get_search_page', 'get_search_page');
$hooks->addAction('get_topic_page', 'get_topic_page');

$hooks->addAction('contact_us', 'contact_us');

$hooks->addAction('liveblog_archive', 'liveblog_archive', false);
$hooks->addAction('liveblog_image', 'liveblog_image', false);

/* HELPERS */
function _get_theme() {
	return new \FelixOnline\Core\Theme('modern6');
}

function _refresh_comment($comment) {
	$theme = _get_theme();

	// Render the output to a buffer
	ob_start();
	// Reload the comment object to get the correct timestamp and so forth
	$theme->render('components/article/main_comment', array('comment' => $comment, 'article' => $comment->getArticle()));
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}

function _fetch_articles($data) {
	$articles = array();

	foreach($data['articles'] as $article) {
		if(!array_key_exists(date('Y-m', $article->getDate()), $articles)) {
			$articles[date('Y-m', $article->getDate())] = array();
		}

		$theme = _get_theme();

		// Render the output to a buffer
		ob_start();

		if(isset($data['category'])) {
			$theme->setHierarchy(array(
				$data['category']->getCat() // block_date-{cat}.php
			));
		}

		$theme->render('components/category/block_normal', array(
			'article' => $article,
			'show_category' => $data['categories'],
			'headshot' => $data['headshots']));

		$articles[date('Y-m', $article->getDate())][] = ob_get_contents();

		krsort($articles);

		ob_end_clean();
	}

	return $articles;
}

/* PAGES */
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
		
		return array("error" => true, "details" => 'There has been an issue with some of your data, please check the highlighted fields and try again', validator => true, validator_data => $failed_fields);
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

/* COMMENTS */
function rate_comment($data) {
	$app = \FelixOnline\Core\App::getInstance();

	$comment = $data['comment'];
	$action = $data['type'];
	$comment = new \FelixOnline\Core\Comment($comment);
	switch($action) {
		case 'like':
			$comment->likeComment($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
			break;
		case 'dislike':
			$comment->dislikeComment($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
			break;
	}

	return array('content' => _refresh_comment($comment));
}

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

function post_comment($data) {
	require_once(BASE_DIRECTORY.'/controllers/baseController.php');
	require_once(BASE_DIRECTORY.'/controllers/articleController.php');

	try {
		$article = new \FelixOnline\Core\Article($data['article']);

		ArticleController::checkAccess($article);

		$status = ArticleController::postComment($article, $data['name'], $data['email'], $data['comment'], $data['reply_to']);
	} catch(\Exception $e) {
		return (array('error' => true, 'details' => 'An internal error occured so your comment could not be posted.', 'clearform' => false));
	}

	if(count($status['errors']) > 0) {
		return (array('error' => true, 'details' => implode(' ', $status['errors']), 'clearform' => false));
	}

	if($status['validationcode']) {
		return (array('error' => false, 'details' => 'Thank you for your comment, you now need to validate your email before your comment will show up. Check your inbox for a validation code.', 'clearform' => true));
	}

	$comment = new \FelixOnline\Core\Comment($status['comment']->getId());
	$output = _refresh_comment($comment);

	return (array('error' => false, 'content' => $output, 'comment_id' => $comment->getId(), 'clearform' => true));
}

/* USER */
function profile_change($data) {
	$app = \FelixOnline\Core\App::getInstance();
	$currentuser = $app['currentuser'];

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

/* PAGINATION */
function get_category_page($ajaxdata) {
	require_once(BASE_DIRECTORY.'/controllers/baseController.php');
	require_once(BASE_DIRECTORY.'/controllers/categoryController.php');

	try {
		$data = CategoryController::fetch($ajaxdata['key'], $ajaxdata['page']);
		$data['categories'] = $ajaxdata['categories'];
		$data['headshots'] = $ajaxdata['headshots'];
	} catch(\Exception $e) {
		return (array('error' => true, 'details' => $e->getMessage()));
	}

	$theme = _get_theme();

	$articles = _fetch_articles($data);

	ob_start();

	$theme->render('components/helpers/pagination', array(
			'pagenum' => $data['pagenum'],
			'class' => $data['category'],
			'pages' => $data['pages'],
			'span' => \FelixOnline\Core\Settings::get('number_of_pages_in_page_list'),
			'type' => 'category',
			'key' => $data['category']->getCat()));

	$paginator = ob_get_contents();

	ob_end_clean();

	return (array('error' => false, 'paginator' => $paginator, 'articles' => $articles, 'cat' => $data['category']->getCat()));
}

function get_user_page($ajaxdata) {
	require_once(BASE_DIRECTORY.'/controllers/baseController.php');
	require_once(BASE_DIRECTORY.'/controllers/userController.php');

	try {
		$data = UserController::fetch($ajaxdata['key'], $ajaxdata['page']);
		$data['categories'] = $ajaxdata['categories'];
		$data['headshots'] = $ajaxdata['headshots'];
	} catch(\Exception $e) {
		return (array('error' => true, 'details' => $e->getMessage()));
	}

	$theme = _get_theme();

	$articles = _fetch_articles($data);

	ob_start();

	$theme->render('components/helpers/pagination', array(
					'pagenum' => $data['pagenum'],
					'class' => $data['user'],
					'pages' => $data['pages'],
					'span' => \FelixOnline\Core\Settings::get('articles_per_user_page'),
					'type' => 'user',
					'key' => $data['user']->getUser()));

	$paginator = ob_get_contents();

	ob_end_clean();

	return (array('error' => false, 'paginator' => $paginator, 'articles' => $articles, 'cat' => 'felix_default'));
}

function get_topic_page($ajaxdata) {
	require_once(BASE_DIRECTORY.'/controllers/baseController.php');
	require_once(BASE_DIRECTORY.'/controllers/topicController.php');

	try {
		$data = TopicController::fetch($ajaxdata['key'], $ajaxdata['page']);
		$data['categories'] = $ajaxdata['categories'];
		$data['headshots'] = $ajaxdata['headshots'];
	} catch(\Exception $e) {
		return (array('error' => true, 'details' => $e->getMessage()));
	}

	$theme = _get_theme();

	$articles = _fetch_articles($data);

	ob_start();

	$theme->render('components/helpers/pagination', array(
			'pagenum' => $data['pagenum'],
			'class' => $data['topic'],
			'pages' => $data['pages'],
			'span' => \FelixOnline\Core\Settings::get('number_of_pages_in_page_list'),
			'type' => 'topic',
			'key' => $data['topic']->getSlug()));

	$paginator = ob_get_contents();

	ob_end_clean();

	return (array('error' => false, 'paginator' => $paginator, 'articles' => $articles, 'topic' => $data['topic']->getSlug(), 'cat' => 'felix_default'));
}

function get_search_page($ajaxdata) {
	require_once(BASE_DIRECTORY.'/controllers/baseController.php');
	require_once(BASE_DIRECTORY.'/controllers/searchController.php');

	try {
		$data = SearchController::fetch($ajaxdata['key'], $ajaxdata['page']);
	} catch(\Exception $e) {
		return (array('error' => true, 'details' => $e->getMessage()));
	}

	$data = array('articles' => $data['articles']['articles'],
			'page' => $data['page'],
			'query' => $data['query']);

	$data['categories'] = $ajaxdata['categories'];
	$data['headshots'] = $ajaxdata['headshots'];

	$theme = _get_theme();

	$articles = _fetch_articles($data);

	ob_start();

	$theme->render('components/helpers/pagination_search', array(
		'page' => $data['page'],
		'query' => $data['query']
	));

	$paginator = ob_get_contents();

	ob_end_clean();

	return (array('error' => false, 'paginator' => $paginator, 'articles' => $articles, 'cat' => 'felix_default'));
}

/* ARTICLES */
function poll_vote($data) {
	require_once(BASE_DIRECTORY.'/controllers/baseController.php');
	require_once(BASE_DIRECTORY.'/controllers/articleController.php');

	try {
		$article = new \FelixOnline\Core\Article($data['article']);

		ArticleController::checkAccess($article);

		$poll = new \FelixOnline\Core\Poll($data['poll']);

		$status = ArticleController::voteOnPoll($article, $poll, $data['option']);
	} catch(\Exception $e) {
		return (array('error' => true, 'details' => 'An internal error occured so your vote could not be cast.'));
	}

	$theme = new \FelixOnline\Core\Theme('modern');

	// Render the output to a buffer
	ob_start();

	$poll = $status['poll'];

	// The poll will not render if the bottom/top status is incorrect
	if($poll->getLocation() != 0) {
		$bottom = true;
	} elseif($poll->getLocation() != 1) {
		$bottom = false;
	}

	$theme->render('components/article/main_poll', array('poll' => $poll, 'article' => $article, 'bottom' => $bottom));

	$output = ob_get_contents();

	ob_end_clean();

	return (array('error' => false, 'content' => $output));
}

/* LIVEBLOG */
function liveblog_archive($data) {
	// Get historic posts

	$return = array();

	require_once(BASE_DIRECTORY.'/controllers/baseController.php');

	$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\BlogPost', 'blog_post');

	$manager = $manager->filter('blog = %i', array($data['blogId']));

	if($data['startAt']) {
		$manager= $manager->filter('id < %i', array($data['startAt']));
	}

	$results = $manager->order('timestamp', 'DESC')->limit(0, 15)->values();

	if(is_null($results)) {
		return(array('error' => true, 'details' => 'There are no more posts.', 'noposts' => true));
	}

	foreach($results as $result) {
		$return[] = array('type' => 'post', 'post' => array('id' => $result->getId(), 'breaking' => $result->getBreaking(), 'title' => $result->getTitle(), 'timestamp' => $result->getTimestamp(), 'data' => json_decode($result->getContent())->data[0]));
	}

	return (array('error' => false, 'posts' => $return));
}

function liveblog_image($data) {
	// Get picture info

	$return = array();

	try {
		$image = new \FelixOnline\Core\Image($data['imageId']);
	} catch(\Exception $e) {
		return(array('error' => true, 'details' => 'The image does not exist'));
	}

	return (array('error' => false, 'width' => $image->getWidth(), 'height' => $image->getHeight(), 'tall' => $image->isTall(), 'url' => $image->getUrl()));
}

