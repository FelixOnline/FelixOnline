<?php
	
class ArticleController extends BaseController {
	private $article;

	function GET($matches) {
		$this->article = new Article($matches['id']);
		$this->theme->appendData(array(
			'article' => $this->article
		));
		$this->theme->setHierarchy(array(
			$this->article->getId(), // article-{id}.php
			$this->article->getCategoryCat(), // article-{cat}.php
		));

		// Log article visit
		$this->article->logVisit();

		$this->theme->render('article');
	}

	function POST($matches) {
		global $currentuser;
		$this->article = new Article($matches['id']);
		$comment = new Comment();
		$comment->setArticle($matches['id']);

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
				$comment->setExternal(true);
				$comment->setContent($_POST['comment']);
				$comment->setName($_POST['name']);
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
			} catch (ExternalException $e) {
				$errorconnection = true;
			}
		}
		
		$this->theme->appendData(array(
			'article' => $this->article,
			'errorduplicate' => $errorduplicate,
			'errorspam' => $errorspam,
			'errorinsert' => $errorinsert,
			'errorconnection' => $errorconnection,
		));
		$this->theme->setHierarchy(array(
			$this->article->getId(),
			$this->article->getCategoryCat(),
		));
		$this->theme->render('article');
	}
}
