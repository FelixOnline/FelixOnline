<?php
/*
 * Comment class
 * Deals with both comment retrieval and comment submission
 *
 * Fields:
 *      id
 *      article
 *      user
 *      comment
 *      timestamp
 *      active
 *      reply
 *      likes
 *      dislikes
 *
 *      IP
 *      pending
 *      spam
 *
 * Comment flags:
 *      Internal:
 *          active - comment is active or not
 *
 *      External:
 *          active | pending | spam  
 *             0   |    0    |   0      rejected comment
 *             1   |    0    |   0      approved comment
 *             1   |    1    |   0      pending comment
 *             0   |    0    |   1      spam comment
 *             0   |    1    |   0      INVALID
 *             1   |    0    |   1      INVALID
 *             0   |    1    |   1      INVALID
 *             1   |    1    |   1      INVALID
 *
 * Examples
 *      // Get comment
 *      $comment = new Comment(300);
 *      echo $comment->getContent(); 
 *
 *      // Submit comment
 *      $comment = new Comment();
 *      $comment->setExternal(false);
 *      $comment->setArticle(100);
 *      $comment->setContent('Hello world');
 *      $comment->setUser('felix');
 *      if($comment->insert()) echo 'Success!';
 */
class Comment extends BaseModel {
	private $article; // article class comment is on
	private $user; // user class
	private $reply; // comment class of reply
    private $external = false; // if comment is external or not. Default false
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
                $this->reply = new Comment($this->fields['reply']); // initialise new comment as reply
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
     * Depreciated: Process a database query
     *
     * $sql - SQL command as a string
     *
     * Returns the result of the mysql query
     */
    private function dbquery($sql) {
		global $cid,$dbok;
		if (!$cid || !$dbok)
			die("Database error: ".mysql_error($cid));
		if ($sql!==NULL && ($c=mysql_num_rows($rsc=mysql_query($sql,$cid)))) {
            return $rsc;
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
        $sql = "SELECT COUNT(*) FROM `comment_like` WHERE user='$user' AND comment=".$this->getId();
        $count = $this->db->get_var($sql);
        return $count;
    }

    /*
     * Public: Set user
     *
     * $username - username of user commenting
     *
     * Returns user
     */
    public function setUser($username) {
        $this->user = $username;
        $this->name = get_vname_by_uname_db($this->user);
        return $this->user;
    }

    /*
     * Public: Check if comment already exists
     *
     * Returns the number of rows that the query will return
     *  i.e. 0 for none found. >0 if found
     */
    public function commentExists() {
        if(!$this->external) {
            $sql = "SELECT COUNT(*) FROM `comment` WHERE article=".$this->article." AND user='".$this->user."' AND comment='".$this->content."' AND `active`=1";
        } else {
            $sql = "SELECT COUNT(*) FROM `comment_ext` WHERE article=".$this->article." AND name='".$this->name."' AND comment='".$this->content."'";
        }
        return $this->db->get_var($sql);
    }

    /* 
     * Public: Insert new comment into database
     *
     * Returns id of new comment
     */
    public function insert() {
        $content = $this->db->escape($this->content);
        if(!$this->external) { // if internal
            $sql = "INSERT INTO `comment` (article,user,comment,reply) VALUES ('".$this->article."','".$this->user."','".$content."','".$this->getReplyID()."')"; // insert comment into database
            $this->db->query($sql); // execute query
            $this->id = $this->db->insert_id; // get id of inserted comment

            if($this->reply && !$this->reply->isExternal()) { // if comment is replying to a comment 
                $this->emailReply();
            }

            /* email authors of article */
            $this->emailAuthors();

            return $this->id; // return new comment id
        } else { // if external comment
            $name = $this->db->escape($this->name);
            // check spam using akismet
            require_once('inc/akismet.class.php');

            $akismet = new Akismet(STANDARD_URL, AKISMET_API_KEY);
            $akismet->setCommentAuthor($this->name);
            //$akismet->setCommentAuthorEmail($email);
            $akismet->setCommentContent($this->comment);
            $akismet->setPermalink(full_article_url($this->article));

            if($akismet->isCommentSpam()) { // if comment is spam
                $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending,reply,spam) VALUES ('".$this->article."','".$name."','".$content."',0,'".$_SERVER['REMOTE_ADDR']."',0,'".$this->getReplyID()."',1)";
                $this->db->query($sql);
                $this->id = $this->db->insert_id; // get id of inserted comment

                $sql = "INSERT IGNORE INTO `comment_spam` (IP, date) VALUES ('".$_SERVER['REMOTE_ADDR']."', DATE_ADD(NOW(), INTERVAL 2 MONTH))"; // insert comment ip into comment_spam
                $this->db->query($sql);

                return 'spam';
            } else {
                $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending,reply) VALUES ('".$this->article."','".$name."','".$content."',1,'".$_SERVER['REMOTE_ADDR']."',1,'".$this->getReplyID()."')";
                $this->db->query($sql);
                $this->id = $this->db->insert_id; // get id of inserted comment

                $this->emailExternalComment();
                return $this->id;
            }
        }
    }

    /*
     * Public: Email authors of article
     */
    public function emailAuthors() {
        $authors = get_article_authors_uname($this->article);
        if(in_array($this->user, $authors)) { // if author of comment is one of the authors
            $authors = array_diff($authors, array($this->user)); // remove them from the author list
        }
        foreach($authors as $author) {
            if(!($emailAddress = get_user_email($author)) && !LOCAL) {
                $emailAddress = ldap_get_mail($author);
            }
            $email = new Email();
            $email->setTo($emailAddress);
            $email->setUniqueID($author);

            $email->setSubject($this->getName().' has commented on "'.get_article_title($this->article).'"');

            ob_start();
            $comment = $this;
            $user = $author;
            include(BASE_DIRECTORY.'/views/emails/comment_notification.php');
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
        $email = new Email();
        $email->setTo(get_user_email_full($this->reply->getUser()));
        $email->setSubject($this->getName().' has replied to your comment on "'.get_article_title($this->article).'"');

        ob_start();
        $comment = $this->reply;
        $reply = $this;
        include(BASE_DIRECTORY.'/views/emails/comment_reply_notification.php');
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
        $email->setSubject('New comment to approve on "'.get_article_title($this->article).'"');

        ob_start();
        $comment = $this;
        include(BASE_DIRECTORY.'/views/emails/new_external_comment.php');
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
            Cache::clear('comment-'.$this->fields['article']);

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
            Cache::clear('comment-'.$this->fields['article']);

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
}

