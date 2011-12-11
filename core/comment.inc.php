<?php
/*
 * Comment class
 * Deals with both comment retrieval and comment submission
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
class Comment {
	private $id; // id of comment
	private $article; // article id comment is on
	private $content; // content of comment
    private $name; // name of author of comment
	private $user; // username of author of comment [internal] [TODO] be user object
    private $reply; // comment object of the comment this comment is replying to
    private $external = false; // if comment is external or not. Default false
    private $timestamp; // unix timestamp of comment submission time
    private $active; // whether comment is active
    private $ip; // ip of commenter [external]
    private $pending; // if comment is pending [external]
    private $spam; // if comment is spam [external]
    private $likes; // number of comment likes
    private $dislikes; // number of comment dislikes
    private $db; // grab global $db variable for database connection
	
    /*
     * Constructor for Comment class
     * If initialised with an id then store relevant data
     * Do nothing if not
     *
     * $id - ID of comment
     *
     * Returns comment object. Returns false if something goes wrong.
     */
	public function __construct($id=NULL) {
        global $db;
        $this->db = $db;
        $this->db->cache_queries = true;
        if($id != NULL) {
            $this->id = $id;
            if($id < EXTERNAL_COMMENT_ID) { // if id is less than external comment id start
                $this->external = false; // comment is internal
                $sql = "SELECT `article`,`user`,`comment` as content,UNIX_TIMESTAMP(`timestamp`) as timestamp,`active`,`reply`,`likes`,`dislikes` FROM `comment` WHERE id=$id";
                $comment = $this->db->get_row($sql);
                foreach($comment as $key => $value) {
                    $this->{$key} = $value; // store each row into object
                }
                if($this->reply) {
                    $this->reply = new Comment($this->reply); // initialise new comment as reply
                }
                $this->name = get_vname_by_uname_db($this->user);
                $this->db->cache_queries = false;
                return $this;
            } else {
                $this->external = true; // comment is external
                $sql = "SELECT `article`,`name`,`comment` as content,UNIX_TIMESTAMP(`timestamp`) as timestamp,`active`,`IP`,`pending`,`reply`,`spam`,`likes`,`dislikes` FROM `comment_ext` WHERE id=$id";
                $comment = $this->db->get_row($sql);
                foreach($comment as $key => $value) {
                    $this->{$key} = $value; // store each row into object
                }
                if($this->reply) {
                    $this->reply = new Comment($this->reply); // initialise new comment as reply
                }
                $this->db->cache_queries = false;
                return $this;
            }
        }
	}

    /*
     * Getter functions
     */
    public function getID()         { return $this->id; }
    public function getArticle()    { return $this->article; }
    public function getUser()       { return $this->user; }
    public function getTimestamp()  { return $this->timestamp; }
    public function getLikes()      { return $this->likes; }
    public function getDislikes()   { return $this->dislikes; }

    /*
     * Public: Get comment content with reply link
     */
    public function getContent() { 
        $output = '';
        // Add link to reply comment
        if($this->reply) { 
            $output .= '<a href="'.curPageURLNonSecure().'#comment'.$this->reply->getID().'" id="replyLink">';
            $output .= '@'.$this->reply->getName().':</a> '; 
        } 
        $output .= nl2br(trim($this->content)); 
        return $output;
    }

    /*
     * Public: Get commenter's name
     */
    public function getName() {
        if($this->name) { // if external commenter has a name
            return $this->name;
        } else {
            return 'Anonymous'; // else return Anonymous
        }
    }

    /*
     * Public: Get comment object of the comment this comment is replying to
     *
     * Returns comment object of reply. Returns false if no reply
     */
    public function getReply() {
        if($this->reply) {
            return $this->reply;
        } else {
            return false;
        }
    }

    /*
     * Public: Get comment replying to's id
     *
     * Returns the id of the comment this comment is replying to
     */
    public function getReplyID() {
        if($this->reply) {
            return $this->reply->getID();
        } else {
            return NULL;
        }
    }

    /*
     * Public: Check if comment is from author of article
     *
     * Returns true if is author. False if not.
     */
    public function byAuthor() {
        if(in_array($this->user, get_article_authors_uname($this->article)) ) {
            return true;
        } else {
            return false;
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
        if(!$this->external && !$this->active || $this->external && !$this->active && !$this->pending) { // if comment that is rejected
            return true; 
        } else {
            return false;
        }
    }

    /*
     * Public: Check if comment is pending approval
     */
    public function isPending() {
        if($this->external && $this->active && $this->pending && $this->ip == $_SERVER['REMOTE_ADDR']) { // if comment is pending for this ip address
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
        $sql = "SELECT COUNT(*) FROM `comment_like` WHERE user='$user' AND comment=".$this->id;
        $count = $this->db->get_var($sql);
        return $count;
    }

    /*
     * Setter functions
     */

    /*
     * Private: Set object ID
     *
     * $id - id of comment
     *
     * Returns id 
     */
    private function setID($id) {
        $this->id = $id;
        return $this->id;
    }

    /*
     * Public: Set comment as external or not
     *
     * $external - flag for if comment is external or not
     *
     * Returns external flag
     */
    public function setExternal($external) {
        $this->external = $external;
        return $this->external;
    }

    /*
     * Public: Set comment article
     *
     * $article - id of article
     *
     * Returns id of article
     */
    public function setArticle($article) {
        $this->article = $article;
        return $this->article;
    }

    /*
     * Public: Set comment content
     *
     * $content - comment content (no need to be escaped etc)
     *
     * Returns content
     */
    public function setContent($content) {
        $this->content = $content;
        return $this->content;
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
     * Public: Set name of commenter (external only)
     *
     * $name - name of commenter
     *
     * Returns name
     */
    public function setName($name) {
        $this->name = $name;
        return $this->name;
    }

    /*
     * Public: Set reply of comment
     *
     * $reply - id of comment that this comment is replying to
     *
     * Returns reply id
     */
    public function setReply($reply) {
        $this->reply = new Comment(mysql_real_escape_string($reply));
        return $this->reply;
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
            // check spam using akismet
            require_once('inc/akismet.class.php');

            $akismet = new Akismet(STANDARD_URL, AKISMET_API_KEY);
            $akismet->setCommentAuthor($this->name);
            //$akismet->setCommentAuthorEmail($email);
            $akismet->setCommentContent($this->comment);
            $akismet->setPermalink(full_article_url($this->article));

            if($akismet->isCommentSpam()) { // if comment is spam
                $name = $this->db->escape($this->name);
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
     * Private: Email authors of article
     */
    private function emailAuthors() {
        $authors = get_article_authors_uname($this->article);
        if(in_array($this->user, $authors)) { // if author of comment is one of the authors
            $authors = array_diff($authors, array($this->user)); // remove them from the author list
        }
        $email = new Email();
        foreach($authors as $author) {
            if(!($emailAddress = get_user_email($author)) && !LOCAL) {
                $emailAddress = ldap_get_mail($author);
            }
            $email->setTo($emailAddress);
        }

        $email->setSubject($this->getName().' has commented on '.get_article_title($this->article));

        ob_start();
        $comment = $this;
        include('views/emails/comment_notification.php');
        $message = ob_get_contents();
        ob_end_clean();

        $email->setContent($message);

        $email->send();
    }

    /*
     * Private: Email comment author with reply
     */
    private function emailReply() {
        $email = new Email();
        $email->setTo(get_user_email_full($this->reply->getUser()));
        $email->setSubject($this->getName().' has replied to your comment on '.get_article_title($this->article));

        ob_start();
        $comment = $this->reply;
        $reply = $this;
        include('views/emails/comment_reply_notification.php');
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
        include('views/emails/new_external_comment.php');
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
            $sql = "INSERT INTO `comment_like` (user,comment,binlike) VALUES ('$user','".$this->id."','1')";
            $this->db->query($sql);

            $this->likes += 1;
            if(!$this->external) { // internal comment
                $sql = "UPDATE `comment` "; 
            } else {
                $sql = "UPDATE `comment_ext` ";
            }
            $sql .= "SET likes = ".$this->likes." WHERE id = ".$this->id;
            $this->db->query($sql);

            return $this->likes;
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
            $sql = "INSERT INTO `comment_like` (user,comment,binlike) VALUES ('$user','".$this->id."','0')";
            $this->db->query($sql);

            $this->dislikes += 1;
            if(!$this->external) { // internal comment
                $sql = "UPDATE `comment` "; 
            } else {
                $sql = "UPDATE `comment_ext` ";
            }
            $sql .= "SET dislikes = ".$this->dislikes." WHERE id = ".$this->id;
            $this->db->query($sql);

            return $this->dislikes;
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
}

?>
