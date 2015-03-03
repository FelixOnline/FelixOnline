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
			Utility::redirect($article->getURL());

			exit;
		}

		$this->theme->appendData(array(
			'article' => $article
		));

		$this->theme->setHierarchy(array(
			$article->getId(), // article-{id}.php
			$article->getCategory()->getCat(), // article-{cat}.php
		));

		// Log article visit
		$article->logVisit();

		// Are there any polls?
		$polls = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Poll', 'polls');

		$pollArticle = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\ArticlePolls', 'article_polls')
			->filter("article = '%s'", array($article->getId()));
		$polls->join($pollArticle);

		$polls = $polls->values();

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
							} else {
								Utility::redirect(
									Utility::currentPageURL(), 
									'', 
									'comment'.$id
								);
								exit;
							}
						} else {
							$errorinsert = true;
						}
					}
				}
			} catch (\FelixOnline\Exceptions\InternalException $e) {
				$errorconnection = true;
			}
		}

		if($_POST['comment'] == '') {
			$errorempty = true;
		}
		
		$this->theme->appendData(array(
			'article' => $article,
			'errorempty' => $errorempty,
			'errorduplicate' => $errorduplicate,
			'errorspam' => $errorspam,
			'erroremail' => $erroremail,
			'errorinsert' => $errorinsert,
			'errorconnection' => $errorconnection,
		));
		$this->theme->setHierarchy(array(
			$article->getId(),
			$article->getCategory()->getCat(),
		));
		$this->theme->render('article');
	}
}
