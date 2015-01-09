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

		$this->theme->appendData(array(
			'article' => $article
		));

		$this->theme->setHierarchy(array(
			$article->getId(), // article-{id}.php
			$article->getCategory()->getCat(), // article-{cat}.php
		));

		// Log article visit
		$article->logVisit();

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
