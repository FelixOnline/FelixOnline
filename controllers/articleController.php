<?php
	
class ArticleController extends BaseController {
	private $article;

	function GET($matches) {
		$this->article = new Article($matches['id']);
		$this->theme->appendData(array(
			'article' => $this->article
		));
		$this->theme->setHierarchy(array(
			'id', /* article-{id}.php */
			'category-cat' /* article-{cat}.php */
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
			$comment->setExternal(true);
			$comment->setContent($_POST['comment']);
			$comment->setName($_POST['name']);
			if(isset($_POST['replyComment'])) {
				$comment->setReply($_POST['replyComment']);
			}

			// ReCapatcha
			//A. Load the Recaptcha Libary
			require_once('inc/recaptchalib.php');
			 
			//B. Recaptcha Looks for the POST to confirm 
			$resp = recaptcha_check_answer(RECAPTCHA_PRIVATE_KEY,
											$_SERVER["REMOTE_ADDR"],
											$_POST["recaptcha_challenge_field"],
											$_POST["recaptcha_response_field"]);
			 
			//C. If if the User's authentication is valid, echo "success" to the Ajax
			if ($resp->is_valid) {
				if ($comment->commentExists()) { // if comment already exists
					$errorduplicate = true;
				} else {
					if($id = $comment->save()) {
						if($id == 'spam') {
							$errorspam = true;
						} else {
							Utility::redirect(Utility::currentPageURL(), 
												'', 
												'comment'.$id);
							exit;
						}
					} else {
						$errorinsert = true;
					}
				}
			} else {
				$errorrecapatcha = true; 
			}
		}
		
		$this->theme->appendData(array(
			'article' => $this->article,
			'errorduplicate' => $errorduplicate,
			'errorspam' => $errorspam,
			'errorrecapatcha' => $errorrecapatcha,
			'errorinsert' => $errorinsert
		));
		$this->theme->setHierarchy(array(
			'id', /* article-{id}.php */
			'category-cat' /* article-{cat}.php */
		));
		$this->theme->render('article');
	}
}

?>
