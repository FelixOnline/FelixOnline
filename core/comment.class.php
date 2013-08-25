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
		$this->db = $db;
		if($id != NULL) {
			if($id < EXTERNAL_COMMENT_ID) { // if comment is internal
				$this->external = false; // comment is internal
				$sql = "SELECT 
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
						WHERE id=$id";
				parent::__construct($this->db->get_row($sql), 'Comment (Internal)', $id);
				//$this->name = get_vname_by_uname_db($this->user);
				return $this;
			} else {
				$this->external = true; // comment is external
				$sql = "SELECT 
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
						WHERE id=$id";
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
		if($this->isExternal() && $this->getActive() && $this->getPending() && $this->getIp() == $_SERVER['REMOTE_ADDR']) { // if comment is pending for this ip address
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
		$sql = "SELECT 
					COUNT(*) 
				FROM `comment_like` 
				WHERE user='$user' 
				AND comment=".$this->getId();
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
			$sql = "SELECT 
						COUNT(*) 
					FROM `comment` 
					WHERE article=".$this->getArticle()->getId()." 
					AND user='".$this->getUser()->getUser()."' 
					AND comment='".$this->getContent()."' 
					AND `active`=1";
		} else {
			$sql = "SELECT 
						COUNT(*) 
					FROM `comment_ext` 
					WHERE article=".$this->getArticle()->getId()." 
					AND name='".$this->getName()."' 
					AND comment='".$this->getContent()."'";
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
		if(!$this->external) { // if internal
			$this->setDbtable('comment');
		} else {
			$this->setDbtable('comment_ext');
			$this->setIp($_SERVER['REMOTE_ADDR']);
			// check spam using akismet
			require_once('inc/akismet.class.php');

			$akismet = new Akismet(STANDARD_URL, AKISMET_API_KEY);
			$akismet->setCommentAuthor($this->getName());
			//$akismet->setCommentAuthorEmail($email);
			$akismet->setCommentContent($this->getContent());
			$akismet->setPermalink($this->getArticle()->getURL());

			if($spam = $akismet->isCommentSpam()) { // if comment is spam
				$this->setActive(0);
				$this->setPending(0);
				$this->setSpam(1);

				$sql = "INSERT IGNORE INTO 
							`comment_spam` 
						(
							IP, 
							date
						) VALUES (
							'".$_SERVER['REMOTE_ADDR']."', 
							DATE_ADD(NOW(), INTERVAL 2 MONTH)
						)"; // insert comment ip into comment_spam
				$this->db->query($sql);
			} else {
				$this->setActive(1);
				$this->setPending(1);
				$this->setSpam(0);
			}
		}

		parent::save();
		$this->setId($this->db->insert_id); // get id of inserted comment

		if($this->isExternal()) {
			if(!$spam) {
				$this->emailExternalComment();
			}
		} else {
			if($this->getReply()) { // if comment is replying to an internal comment 
				$this->emailReply();
			}

			/* email authors of article */
			$this->emailAuthors();
		}

		// clear cache
		//Cache::clear('comment-'.$this->fields['article']);
		
		if($this->isExternal() && $this->getSpam() == 1) {
			return 'spam';
		} else {
			return $this->getId(); // return new comment id
		}
	}

	/*
	 * Public: Email authors of article
	 */
	public function emailAuthors() {
		$authors = $this->getArticle()->getAuthors();
		if(in_array($this->getUser(), $authors)) { // if author of comment is one of the authors
			$authors = array_diff($authors, array($this->getUser())); // remove them from the author list
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
			$sql = "INSERT INTO `comment_like` 
					(
						user,
						comment,
						binlike
					) VALUES (
						'".$this->db->escape($user)."',
						'".$this->getId()."',
						'1'
					)";
			$this->db->query($sql);

			$likes = $this->getLikes() + 1;
			if(!$this->external) { // internal comment
				$sql = "UPDATE `comment` "; 
			} else {
				$sql = "UPDATE `comment_ext` ";
			}
			$sql .= "SET likes = ".$likes." WHERE id = ".$this->getId();
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
			$sql = "INSERT INTO `comment_like` 
					(
						user,
						comment,
						binlike
					) VALUES (
						'".$this->db->escape($user)."',
						'".$this->getId()."',
						'0'
					)";
			$this->db->query($sql);

			$dislikes = $this->getDislikes() + 1;
			if(!$this->external) { // internal comment
				$sql = "UPDATE `comment` "; 
			} else {
				$sql = "UPDATE `comment_ext` ";
			}
			$sql .= "SET dislikes = ".$dislikes." WHERE id = ".$this->getId();
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
			$sql = "SELECT 
						COUNT(id) 
					FROM comment_ext 
					WHERE ACTIVE=1 
					AND pending=1";
			$this->commentsToApprove = $this->db->get_var($sql);
		}
		return $this->commentsToApprove;
	}
	
	/*
	 * Helpers
	 */
	public static function getRecentComments($num_to_get) {
		global $db;
		
		$sql = "SELECT * FROM (
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
			ORDER BY timestamp DESC LIMIT ".$num_to_get;

		$recent_comments = $db->get_results($sql);
		return $recent_comments;
	}
}

