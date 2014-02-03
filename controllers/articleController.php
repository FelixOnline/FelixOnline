<?php
	
class ArticleController extends BaseController
{
	private $article;

	function GET($matches)
	{
		$article = new \FelixOnline\Core\Article($matches['id']);
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
		$comment = new Comment();
		$comment->setArticle($article->getId());

		/* User comment */
		if ($_POST['articlecomment']) {
			$comment->setExternal(false);
			$comment->setContent($_POST['comment']);
			$comment->setUser($currentuser->isLoggedIn());
			if(isset($_POST['replyComment'])) {
				$comment->setReply($_POST['replyComment']);
			}
			if ($comment->commentExists()) { // if comment already exists
				$errorduplicate = true;
			} else {
				if($id = $comment->save()) { 
					Utility::redirect(Utility::currentPageURL(), 
										'', 
										'comment'.$id);
					exit;
				} else {
					$errorinsert = true;
				}
			}
		}

		/* External comment */
		else if ($_POST['articlecomment_ext']) {
			try {
				if($_POST['email'] == '' || !is_email($_POST['email'])) {
					$erroremail = true;
				} else {
					$comment->setExternal(true);
					$comment->setContent($_POST['comment']);
					$comment->setName($_POST['name']);
					$comment->setEmail($_POST['email']);
					if(isset($_POST['replyComment'])) {
						$comment->setReply($_POST['replyComment']);
					}

					if ($comment->commentExists()) { // if comment already exists
						$errorduplicate = true;
					} else {
						if($id = $comment->save()) {
							if($comment->isExternal() && $comment->getSpam() == 1) {
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
			} catch (ExternalException $e) {
				$errorconnection = true;
			}
		}
		
		$this->theme->appendData(array(
			'article' => $article,
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
