<?php
	
class ArticleController extends BaseController
{
	private $article;

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

		global $currentuser;

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

		if(array_key_exists('poll', $_GET) && array_key_exists('option', $_GET)) {
			try {
				$poll = new \FelixOnline\Core\Poll($_GET['poll']);

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
				$option = new \FelixOnline\Core\PollOption($_GET['option']);

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
			} catch(Exception $e) { }

			// reload page
			Utility::redirect($article->getURL().'#poll-'.$poll->getId());

			exit;
		}

		$converter = new \Sioen\Converter();

		$text = $converter->toHTML($article->getContent());
		$text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $text); // Some <p>^B</p> tags can get through some times. Should not happen with the current migration script

		// More text tidying
		$text = strip_tags($text, '<p><a><div><b><i><br><blockquote><object><param><embed><li><ul><ol><strong><img><h1><h2><h3><h4><h5><h6><em><iframe><strike>'); // Gets rid of html tags except <p><a><div>
		$text = preg_replace('/(<br(| |\/|( \/))>)/i', '', $text); // strip br tag
		$text = preg_replace('#<div[^>]*(?:/>|>(?:\s|&nbsp;)*</div>)#im', '', $text); // Removes empty html div tags
		$text = preg_replace('#<span*(?:/>|>(?:\s|&nbsp;)[^>]*</span>)#im', '', $text); // Removes empty html span tags
		$text = preg_replace('#<p[^>]*(?:/>|>(?:\s|&nbsp;)*</p>)#im', '', $text); // Removes empty html p tags
		$text = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $text); // Remove style attributes

		$this->theme->appendData(array(
			'article' => $article,
			'text' => $text
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
		global $currentuser;
		$article = new \FelixOnline\Core\Article($matches['id']);
		$comment = new \FelixOnline\Core\Comment();
		$comment->setArticle($article->getId());

		$errorduplicate = $errorspam = $erroremail = $errorinsert = $errorconnection = $errorempty = false;

		if ($article->canComment($currentuser) && $_POST['comment'] != '') {
			try {
				if ($_POST['email'] == '' || !is_email($_POST['email'])) {
					$erroremail = true;
				} else {
					$comment->setComment($_POST['comment']);
					if($currentuser->isLoggedIn()): $comment->setUser($currentuser->getUser()); endif;
					$comment->setName($_POST['name']);
					$comment->setEmail($_POST['email']);
					if(isset($_POST['replyComment'])) {
						$comment->setReply($_POST['replyComment']);
					}

					if($_POST['comment'] == '') {
						$errorempty = true;
					} elseif ($comment->commentExists()) { // if comment already exists
						$errorduplicate = true;
					} else {
						if ($id = $comment->save()) {
							if ($comment->getSpam() == 1) {
								$errorspam = true;
							}
						} else {
							$errorinsert = true;
						}
					}

					// Create a validation code, if it returns false one is not needed
					$validationcode = \FelixOnline\Core\EmailValidation::create(strtolower($_POST['email']));

					if(!$validationcode) {
						// We may still not be validated
						if(!\FelixOnline\Core\EmailValidation::isEmailValidated(strtolower($_POST['email']))) {
							$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\EmailValidation', 'email_validation');
							$manager->filter('email = "%s"', array(strtolower($_POST['email'])));
							$values = $manager->one();

							$validationcode = $values->getCode();
						}
					}

					if($currentuser->isLoggedIn()) {
						// Auto validate logged in users

						$validationcode = false;

						$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\EmailValidation', 'email_validation');
						$manager->filter('email = "%s"', array(strtolower($_POST['email'])));
						$values = $manager->one();

						if($values) {
							$values->setConfirmed(1)->save();
						}
					} elseif($validationcode) {
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
							'name' => $_POST['name'],
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
								$_POST['email'] => $_POST['name'],
							));

						// Send email
						$app['email']->send($message);
					}
				}
			} catch (\FelixOnline\Exceptions\InternalException $e) {
				$errorconnection = true;
			}
		}

		if($_POST['comment'] == '') {
			$errorempty = true;
		}

		if(!$errorempty && !$errorduplicate && !$erroremail && !$errorspam && !$validationcode && !$errorinsert && !$errorconnection) {
			Utility::redirect(
				Utility::currentPageURL(), 
				'', 
				'comment'.$id
			);
			exit;
		}
		
		$converter = new \Sioen\Converter();

		$text = $converter->toHTML($article->getContent());
		$text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $text); // Some <p>^B</p> tags can get through some times. Should not happen with the current migration script

		// More text tidying
		$text = strip_tags($text, '<p><a><div><b><i><br><blockquote><object><param><embed><li><ul><ol><strong><img><h1><h2><h3><h4><h5><h6><em><iframe><strike>'); // Gets rid of html tags except <p><a><div>
		$text = preg_replace('/(<br(| |\/|( \/))>)/i', '', $text); // strip br tag
		$text = preg_replace('#<div[^>]*(?:/>|>(?:\s|&nbsp;)*</div>)#im', '', $text); // Removes empty html div tags
		$text = preg_replace('#<span*(?:/>|>(?:\s|&nbsp;)[^>]*</span>)#im', '', $text); // Removes empty html span tags
		$text = preg_replace('#<p[^>]*(?:/>|>(?:\s|&nbsp;)*</p>)#im', '', $text); // Removes empty html p tags
		$text = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $text); // Remove style attributes

		$this->theme->appendData(array(
			'article' => $article,
			'text' => $text,
			'errorempty' => $errorempty,
			'errorduplicate' => $errorduplicate,
			'errorspam' => $errorspam,
			'erroremail' => $erroremail,
			'errorinsert' => $errorinsert,
			'errorconnection' => $errorconnection,
			'validationcode' => $validationcode
		));
		$this->theme->setHierarchy(array(
			$article->getId(),
			$article->getCategory()->getCat(),
		));
		$this->theme->render('article');
	}
}
