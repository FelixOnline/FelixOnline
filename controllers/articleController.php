<?php
	
class ArticleController extends BaseController
{
	private $article;

	public function postComment($article, $name, $email, $message, $reply = false) {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		$comment = new \FelixOnline\Core\Comment();
		$comment->setArticle($article->getId());

		$errors = array();
		$validationcode = false;

		if ($article->canComment($currentuser)) {
			if ($email == '' || !is_email($email)) {
				$errors[] = 'Please enter a valid email address.';
			} elseif($message == '') {
				$errors[] = 'Please enter a comment if you\'d like to post one.';
			} else {
				$comment->setComment($message);
				$comment->setName($name);
				$comment->setEmail($email);

				if($reply) {
					$comment->setReply($reply);
				}

				if($currentuser->isLoggedIn()) {
					$comment->setUser($currentuser->getUser());
				}

				if ($comment->commentExists()) { // if comment already exists
					$errors[] = 'This comment has already been posted.';
				} else {
					$id = $comment->save();

					if ($comment->getSpam() == 1) {
						$errors[] = 'We are sorry, your comment has been automatically flagged as spam. Please let us know if you think this is incorrect.';
					}

					// Create a validation code, if it returns false one is not needed
					$validationcode = \FelixOnline\Core\EmailValidation::create(strtolower($email));

					if(!$validationcode) {
						// We may still not be validated
						if(!\FelixOnline\Core\EmailValidation::isEmailValidated(strtolower($email))) {
							$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\EmailValidation', 'email_validation');
							$manager->filter('email = "%s"', array(strtolower($email)));
							$values = $manager->one();

							$validationcode = $values->getCode();
						}

						// This will still be false if the user is already validated
					}

					// We have a validation code (probably) - now get the user validated
					if($currentuser->isLoggedIn()) {
						// Auto validate logged in users

						$validationcode = false;

						$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\EmailValidation', 'email_validation');
						$manager->filter('email = "%s"', array(strtolower($email)));
						$values = $manager->one();

						if($values) {
							$values->setConfirmed(1)->save();
						}
					} elseif($validationcode) {
						// If the user is still not validated, ask them to validate

						// Send an email
						$app = \FelixOnline\Core\App::getInstance();

						// Create message
						$message = \Swift_Message::newInstance()
							->setSubject('Please verify your email address')
							->setFrom(array(\FelixOnline\Core\Settings::get('email_replyto_addr') => \FelixOnline\Core\Settings::get('email_replyto_name')));

						// Get content
						ob_start();
						$data = array(
							'app' => $app,
							'name' => $name,
							'code' => STANDARD_URL.'validate/'.$validationcode,
						);

						// Render email template
						call_user_func(function() use($data) {
							extract($data);
							include realpath(__DIR__ . '/../templates/') . '/email_validation.php';
						});

						$content = ob_get_contents();
						ob_end_clean();

						$message->setBody($content, 'text/html')
							->setTo(array(
								$email => $name,
							));

						// Send email
						$app['email']->send($message);
					}
				}
			}
		} else {
			$errors[] = 'Sorry, you are not able to comment on this article.';
		}

		return array("errors" => $errors,
			"comment" => $comment,
			"validationcode" => $validationcode);
	}

	function GET($matches)
	{
		try {
			$article = new \FelixOnline\Core\Article($matches['id']);
		} catch(Exception $e) {
			throw new NotFoundException(
				"Article not found",
				$matches,
				'ArticleController'
			);
		}

		self::checkAccess($article);

		if(array_key_exists('poll', $_GET) && array_key_exists('option', $_GET)) {
			try {
				$poll = new \FelixOnline\Core\Poll($_GET['poll']);

				self::voteOnPoll($article, $poll, $_GET['option']);
			} catch(Exception $e) { }

			// reload page
			Utility::redirect($article->getURL().'#poll-'.$poll->getId());

			exit;
		}

		return self::renderArticle($article);
	}

	function voteOnPoll($article, $poll, $option) {
		// is poll open
		if($poll->getEnded()) {
			throw new Exception('Poll is closed');
		}

		// can user vote
		if(!$poll->canUserRespond()) {
			throw new Exception('Already responded');
		}

		$found = false;

		// validate poll for article
		$articles = $poll->getArticles();
		foreach($articles as $p_article) {
			if($p_article->getArticle()->getId() == $article->getId()) {
				$found = true;
			}
		}

		if(!$found) {
			throw new Exception('Wrong poll');
		}

		// get option
		$option = new \FelixOnline\Core\PollOption($option);

		// is option valid
		if($option->getPoll()->getId() != $poll->getId()) {
			throw new Exception('Invalid option for this poll');
		}

		$response = new \FelixOnline\Core\PollResponse();
		$response->setPoll($poll);
		$response->setOption($option);
		$response->setIp($_SERVER['REMOTE_ADDR']);
		$response->setUseragent($_SERVER['HTTP_USER_AGENT']);
		$response->save();

		return(array("poll" => $poll,
			"article" => $article));
	}

	function checkAccess($article) {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		if(!$article->getPublished()) {
			$authors = $article->getAuthors();

			$isAuthorised = false;

			if(is_array($authors)) {
				foreach($authors as $user) {
					if($currentuser->getUser() == $user->getUser()) {
						$isAuthorised = true;
					}
				}
			}

			if($article->getCategory()->getEditors() != null) {
				foreach($article->getCategory()->getEditors() as $user) {
					if($currentuser->getUser() == $user->getUser()) {
						$isAuthorised = true;
					}
				}
			}

			if(!$isAuthorised) {
				// Cannot see unpublished articles
				throw new NotFoundException(
					"Article not found",
					$matches,
					'ArticleController'
				);
			}
		}

		// Check the category
		$category = $article->getCategory();

		if(!$category->getActive()) {
			// Cannot see articles in inactive categories
			throw new NotFoundException(
				"Category is not active",
				$matches,
				'ArticleController'
			);
		}

		if($category->getSecret() && !$currentuser->isLoggedIn()) {
			// Cannot see articles in inactive categories
			throw new NotFoundException(
				"Category is not accessible",
				$matches,
				'ArticleController'
			);
		}
	}

	function renderArticle($article, $comment_status = null, $comment_email = false) {
		$text = Utility::tidyText($article->getContent());

		$this->theme->appendData(array(
			'article' => $article,
			'text' => $text,
			'comment_status' => $comment_status,
			'comment_validate_email' => $comment_email
		));

		$this->theme->setHierarchy(array(
			$article->getId(), // article-{id}.php
			$article->getCategory()->getCat(), // article-{cat}.php
		));

		// Log article visit
		$article->logVisit();

		// Are there any polls?
		$pollArticles = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\ArticlePolls', 'article_polls')
			->filter("article = %i", array($article->getId()));

		$pollArticles = $pollArticles->values();

		$polls = array();

		if(is_array($pollArticles)) {
			foreach($pollArticles as $pollArticle) {
				$polls[] = $pollArticle->getPoll();
			}
		}

		$this->theme->appendData(array(
			'polls' => $polls
		));

		$this->theme->render('article');
	}

	function POST($matches)
	{
		$article = new \FelixOnline\Core\Article($matches['id']);

		self::checkAccess($article);

		try {
			$status = self::postComment($article, $_POST['name'], $_POST['email'], $_POST['comment'], $_POST['replyComment']);
		} catch(\Exception $e) {
			return self::renderArticle($article, 'A system error occured when posting your comment. Please try again later.');
		}

		if(count($status['errors']) > 0) {
			return self::renderArticle($article, implode(' ', $status['errors']));
		}

		if($status['validationcode']) {
			return self::renderArticle($article, null, true);
		}

		Utility::redirect(
			Utility::currentPageURL(), 
			'', 
			'comment'.$status['comment']->getId()
		);
		exit;	
	}
}
