<?php
/*
 * Comment class
 * Deals with both comment retrieval and comment submission
 *
 * Fields:
 *	  id
 *	  article
 *	  user
 *	  comment
 *	  timestamp
 *	  active
 *	  reply
 *	  likes
 *	  dislikes
 *
 *	  IP
 *	  pending
 *	  spam
 *
 * Comment flags:
 *	  Internal:
 *		  active - comment is active or not
 *
 *	  External:
 *		  active | pending | spam  
 *			 0   |	0	|   0	  rejected comment
 *			 1   |	0	|   0	  approved comment
 *			 1   |	1	|   0	  pending comment
 *			 0   |	0	|   1	  spam comment
 *			 0   |	1	|   0	  INVALID
 *			 1   |	0	|   1	  INVALID
 *			 0   |	1	|   1	  INVALID
 *			 1   |	1	|   1	  INVALID
 *
 * Examples
 *	  // Get comment
 *	  $comment = new Comment(300);
 *	  echo $comment->getContent(); 
 *
 *	  // Submit comment
 *	  $comment = new Comment();
 *	  $comment->setExternal(false); // internal comment
 *	  $comment->setArticle(100); // article id
 *	  $comment->setContent('Hello world');
 *	  $comment->setUser('felix');
 *	  if($id = $comment->save()) echo 'Success!';
 */
class Comment extends BaseModel {
	private $article; // article class comment is on
	private $user; // user class
	private $reply; // comment class of reply
	private $external = false; // if comment is external or not. Default false
	private $commentsToApprove;
	protected $db;
	protected $transformers = array(
		'content' => parent::TRANSFORMER_NO_HTML);
	
	/*
	 * Constructor for Comment class
	 * If initialised with an id then store relevant data
	 * Do nothing if not
	 *
	 * $id - ID of comment
	 *
	 * Returns comment object.
	 */
	public function __construct($id=NULL) {
		global $db;
		global $safesql;
		$this->db = $db;
		$this->safesql = $safesql;

		if($id != NULL) {
			if($id < EXTERNAL_COMMENT_ID) { // if comment is internal
				$this->external = false; // comment is internal
				$sql = $this->safesql->query(
					"SELECT 
						id, 
						`article`,
						`user`,
						`comment` as content,
						UNIX_TIMESTAMP(`timestamp`) as timestamp,
						`active`,
						`reply`,
						`likes`,
						`dislikes` 
					FROM `comment` 
					WHERE id=%i",
					array(
						$id
					));

				parent::__construct($this->db->get_row($sql), 'Comment (Internal)', $id);

				return $this;
			} else {
				$this->external = true; // comment is external
				$sql = $this->safesql->query(
					"SELECT 
						id, 
						`article`,
						`name`,
						`comment` as content,
						UNIX_TIMESTAMP(`timestamp`) as timestamp,
						`active`,
						`IP` as ip,
						`pending`,
						`reply`,
						`spam`,
						`likes`,
						`dislikes` 
					FROM `comment_ext` 
					WHERE id=%i",
					array(
						$id
					));

				$this->transfomers['name'] = parent::TRANSFORMER_NO_HTML;

				parent::__construct($this->db->get_row($sql), 'Comment (External)', $id);
				return $this;
			}
		} else {
			$this->setFieldFilters(array(
				'content' => 'comment',
				'ip' => 'IP'
			));
		}
	}

	/*
	 * Public: Get article class that comment is on
	 */
	public function getArticle() { 
		if(!$this->article) {
			$this->article = new Article($this->fields['article']);
		}
		return $this->article;
	}

	/*
	 * Public: Get user class
	 */
	public function getUser() {
		if(!$this->user) {
			$this->user = new User($this->fields['user']);
		}
		return $this->user;
	}

	/*
	 * Public: Get comment object of the comment this comment is replying to
	 *
	 * Returns comment object of reply. Returns false if no reply
	 */
	public function getReply() {
		if($this->fields['reply']) {
			if(!$this->reply) {
				try {
					$this->reply = new Comment($this->fields['reply']); // initialise new comment as reply
				} catch (Exception $e) {
					return false;
				}
			}
			return $this->reply;
		} else {
			return false;
		}
	}

	/*
	 * Public: Get comment content with reply link
	 */
	public function getContent() { 
		$output = '';
		// Add link to reply comment
		if($this->getReply()) { 
			$output .= '<a href="'.Utility::currentPageURL().'#comment'.$this->getReply()->getId().'" id="replyLink">';
			$output .= '@'.$this->getReply()->getName().':</a> '; 
		} 
		$output .= nl2br(trim($this->fields['content'])); 
		return $output;
	}

	/*
	 * Public: Get commenter's name
	 */
	public function getName() {
		if($this->isExternal()) {
			if($this->fields['name']) { // if external commenter has a name
				return $this->fields['name'];
			} else {
				return 'Anonymous'; // else return Anonymous
			}
		} else {
			return $this->getUser()->getName();
		}
	}

	/*
	 * Public: Get url
	 */
	public function getURL() {
		return $this->getArticle()->getURL().'#comment'.$this->getId();
	}

	/*
	 * Public: Check if comment is from author of article
	 *
	 * Returns true if is author. False if not.
	 */
	public function byAuthor() {
		if($this->isExternal()) {
			return false;
		} else {
			if(in_array($this->getUser()->getUser(), $this->getArticle()->getAuthors())) {
				return true;
			} else {
				return false;
			}
		}
	}

	/*
	 * Public: Check if comment is from an external author or not
	 *
	 * Returns true if external, false if internal
	 */
	public function isExternal() {
		if($this->external) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * Public: Check if comment is rejected
	 */
	public function isRejected() {
		if(!$this->isExternal() && !$this->getActive() 
		   || $this->isExternal() && !$this->getActive() && !$this->getPending()) { // if comment that is rejected
			return true; 
		} else {
			return false;
		}
	}

	/*
	 * Public: Check if comment is pending approval
	 */
	public function isPending() {
		if($this->isExternal()
			&& $this->getActive()
			&& $this->getPending()
			&& $this->getIp() == $_SERVER['REMOTE_ADDR']
		) { // if comment is pending for this ip address
			return true;
		} else {
			return false;
		} 
	}

	/*
	 * Public: Check if current user has liked or disliked the comment
	 *
	 * $user - username of current user
	 *
	 * Returns true or false
	 */
	public function userLikedComment($user) {
		$sql = $this->safesql->query(
			"SELECT 
				COUNT(*) 
			FROM `comment_like` 
			WHERE user='%s' 
			AND comment=%i",
			array(
				$user,
				$this->getId()
			));
		$count = $this->db->get_var($sql);
		return $count;
	}

	/*
	 * Public: Check if comment already exists
	 *
	 * Returns the number of rows that the query will return
	 *  i.e. 0 for none found. >0 if found
	 */
	public function commentExists() {
		if(!$this->external) {
			$sql = $this->safesql->query(
				"SELECT 
					COUNT(*) 
				FROM `comment` 
				WHERE article=%i 
				AND user='%s' 
				AND comment='%s' 
				AND `active`=1",
				array(
					$this->getArticle()->getId(),
					$this->getUser()->getUser(),
					$this->getContent(),
				));
		} else {
			$sql = $this->safesql->query(
				"SELECT 
					COUNT(*) 
				FROM `comment_ext` 
				WHERE article=%i 
				AND name='%s' 
				AND comment='%s'",
				array(
					$this->getArticle()->getId(),
					$this->getName(),
					$this->getContent(),
				));
		}
		return $this->db->get_var($sql);
	}

	/*
	 * Public: Set external
	 * Set whether comment is external or not
	 */
	public function setExternal($external) {
		$this->external = $external;
		return $this->external;
	}

	/* 
	 * Public: Save new comment into database
	 *
	 * Returns id of new comment
	 */
	public function save() {
		global $akismet;
		if(!$this->isExternal()) { // if internal
			$this->setDbtable('comment');
		} else {
			$this->setDbtable('comment_ext');
			$this->setIp($_SERVER['REMOTE_ADDR']);

			// check spam using akismet
			$key_check = $akismet->keyCheck(AKISMET_API_KEY, STANDARD_URL);

			if ($key_check == false) {
				throw new ExternalException('Akismet key is invalid');
			}

			$check = $akismet->check(array(
				'permalink' => $this->getArticle()->getURL(),
				'comment_type' => 'comment',
				'comment_author' => $this->getName(),
				'comment_content' => $this->getContent(),
				'user_ip' => $this->getIp(),
				'user_agent' => $_SERVER['HTTP_USER_AGENT'],
				'referrer' => $_SERVER['HTTP_REFERER'],
			));

			if ($check == true) { // if comment is spam
				$this->setActive(0);
				$this->setPending(0);
				$this->setSpam(1);

				$sql = $this->safesql->query(
					"INSERT IGNORE INTO `comment_spam` 
					(
						IP, 
						date
					) VALUES (
						'%s', 
						DATE_ADD(NOW(), INTERVAL 2 MONTH)
					)",
					array(
						$_SERVER['REMOTE_ADDR'],
					)); // insert comment ip into comment_spam
				$this->db->query($sql);
			} else { // Not spam
				$this->setActive(1);
				$this->setPending(1);
				$this->setSpam(0);
			}
		}

		// check for akismet errors
		if (!is_null($akismet->getError())) {
			throw new ExternalException($akismet->getError());
		}

		parent::save();
		$this->setId($this->db->insert_id); // get id of inserted comment

		// Send emails

		if ($this->isExternal()) {
			// If pending comment
			if (!$this->getSpam() && $this->getPending() && $this->getActive()) {
				$this->emailExternalComment();
			}
		} else { // internal emails
			if ($this->getReply()) { // if comment is replying to an internal comment 
				$this->emailReply();
			}

			/* email authors of article */
			$this->emailAuthors();
		}
		
		return $this->getId(); // return new comment id
	}

	/*
	 * Public: Email authors of article
	 */
	public function emailAuthors() {
		$authors = $this->getArticle()->getAuthors();
		if(in_array($this->getUser(), $authors)) { // if author of comment is one of the authors
			$key = array_search($this->getUser(), $authors);
			unset($authors[$key]); // remove them from the author list
		}
		foreach($authors as $author) {
			$emailAddress = $author->getEmail(true); // get email address of user
			$email = new Email();
			$email->setTo($emailAddress);
			$email->setUniqueID($author->getUser());

			$email->setSubject($this->getName().' has commented on "'.$this->getArticle()->getTitle().'"');

			ob_start();
			$comment = $this;
			$user = $author;
			include(BASE_DIRECTORY.'/templates/emails/comment_notification.php');
			$message = ob_get_contents();
			ob_end_clean();

			$email->setContent($message);

			$email->send();
		}
	}

	/*
	 * Private: Email comment author with reply
	 */
	private function emailReply() {
		if($this->getReply()->isExternal()) { // check that comment replied to isn't external
			return false;
		}
		$email = new Email();
		$email->setTo($this->getReply()->getUser()->getEmail(true));
		$email->setSubject($this->getName().' has replied to your comment on "'.$this->getArticle()->getTitle().'"');

		ob_start();
		$comment = $this->getReply();
		$reply = $this;
		include(BASE_DIRECTORY.'/templates/emails/comment_reply_notification.php');
		$message = ob_get_contents();
		ob_end_clean();

		$email->setContent($message);

		return $email->send();
	}

	/*
	 * Private: Email felix on new external comment
	 */
	private function emailExternalComment() {
		/* Send email */
		$email = new Email();
		$email->setTo(EMAIL_EXTCOMMENT_NOTIFYADDR);
		$email->setSubject('New comment to approve on "'.$this->getArticle()->getTitle().'"');

		ob_start();
		$comment = $this;
		include(BASE_DIRECTORY.'/templates/emails/new_external_comment.php');
		$message = ob_get_contents();
		ob_end_clean();

		$email->setContent($message);
		return $email->send();
	}

	/*
	 * Public: Like comment
	 *
	 * $user - string username of user liking comment
	 *
	 * Returns number of likes
	 */
	public function likeComment($user) {
		if(!$this->userLikedComment($user)) { // check user hasn't already liked the comment
			$sql = $this->safesql->query(
				"INSERT INTO `comment_like` 
				(
					user,
					comment,
					binlike
				) VALUES (
					'%s',
					%i,
					'1'
				)",
				array(
					$user,
					$this->getId(),
				));
			$this->db->query($sql);

			$likes = $this->getLikes() + 1;
			if(!$this->external) { // internal comment
				$sql = "UPDATE `comment` "; 
			} else {
				$sql = "UPDATE `comment_ext` ";
			}
			$sql .= "SET likes = %i WHERE id = %i";
			$sql = $this->safesql->query($sql, array(
				$likes,
				$this->getId(),
			));
			$this->db->query($sql);

			// clear comment
			//Cache::clear('comment-'.$this->fields['article']);

			return $likes;
		} else {
			return false;
		}
	}

	/*
	 * Public: Dislike comment
	 *
	 * $user - string username of user disliking comment
	 *
	 * Returns number of dislikes
	 */
	public function dislikeComment($user) {
		if(!$this->userLikedComment($user)) { // check user hasn't already liked the comment
			$sql = $this->safesql->query(
				"INSERT INTO `comment_like` 
				(
					user,
					comment,
					binlike
				) VALUES (
					'%s',
					%i,
					'0'
				)",
				array(
					$user,
					$this->getId(),
				));
			$this->db->query($sql);

			$dislikes = $this->getDislikes() + 1;
			if(!$this->external) { // internal comment
				$sql = "UPDATE `comment` "; 
			} else {
				$sql = "UPDATE `comment_ext` ";
			}
			$sql .= "SET dislikes = %i WHERE id = %i";
			$sql = $this->safesql->query($sql, array(
				$dislikes,
				$this->getId(),
			));
			$this->db->query($sql);
			
			// clear comment
			//Cache::clear('comment-'.$this->fields['article']);

			return $dislikes;
		} else {
			return false;
		}
	}

	/*
	 * Utility functions
	 */

	public function printThis() {
		print_r($this);
	}

	private function log($name, $content) {
		$file = 'emails/'.date('Y-m-d H:i:s').' '.$name.'.txt';
		$fh = fopen($file, 'a');
		if(is_string($content)) {
			$body = $content."\r\n";
		} else {
			ob_start();
			print_r($content);
			$content = ob_get_contents();
			ob_end_clean();
			$body = $content."\r\n";
		}
		fwrite($fh, $body);
		fclose($fh);
	}

	private function getCommentsToApprove() {
		if(!$this->commentsToApprove) {
			$sql = $this->safesql->query(
				"SELECT 
					COUNT(id) 
				FROM comment_ext 
				WHERE ACTIVE=1 
				AND pending=1", array());
			$this->commentsToApprove = $this->db->get_var($sql);
		}
		return $this->commentsToApprove;
	}
	
	/*
	 * Helpers
	 */
	public static function getRecentComments($num_to_get) {
		global $db;
		global $safesql;
		
		$sql = $safesql->query(
			"SELECT * FROM (
				SELECT comment.article,
					comment.id,
					comment.user,
					user.name,
					comment.comment,
					UNIX_TIMESTAMP(comment.timestamp) AS timestamp 
				FROM `comment` LEFT JOIN `user` ON (comment.user=user.user) 
				WHERE active=1 
				UNION SELECT comment_ext.article,
					comment_ext.id,
					comment_ext.name,
					comment_ext.comment,
					'ext',
					UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp 
				FROM `comment_ext` 
				WHERE active=1 
				AND pending=0
			) AS t 
			ORDER BY timestamp DESC LIMIT %i",
			array(
				$num_to_get,
			));

		$recent_comments = $db->get_results($sql);
		return $recent_comments;
	}
}
