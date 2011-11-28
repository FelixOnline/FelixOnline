<?php

/*
 * Comment class
 * Deals with both comment retrieval and comment submission
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
 *      $comment->setUser('jk708');
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
        if($id != NULL) {
            $this->id = $id;
            if($id < EXTERNAL_COMMENT_ID) { // if id is less than external comment id start
                $this->external = false; // comment is internal
                $sql = "SELECT `article`,`user`,`comment`,UNIX_TIMESTAMP(`timestamp`),`active`,`reply` FROM `comment` WHERE id=$id";
                if($rsc = $this->dbquery($sql)) {
                    list(
                        $this->article,
                        $this->user,
                        $this->content,
                        $this->timestamp,
                        $this->active,
                        $this->reply,
                    ) = mysql_fetch_array($rsc);
                    if($this->reply) {
                        $this->reply = new Comment($this->reply); // initialise new comment as reply
                    }
                    $this->name = get_vname_by_uname_db($this->user);
                    return $this;
                } else {
                    return false;
                }
            } else {
                $this->external = true; // comment is external
                $sql = "SELECT `article`,`name`,`comment`,UNIX_TIMESTAMP(`timestamp`),`active`,`IP`,`pending`,`reply`,`spam` FROM `comment_ext` WHERE id=$id";
                if($rsc = $this->dbquery($sql)) {
                    list(
                        $this->article,
                        $this->name,
                        $this->content,
                        $this->timestamp,
                        $this->active,
                        $this->ip,
                        $this->pending,
                        $this->reply,
                        $this->spam,
                    ) = mysql_fetch_array($rsc);
                    if($this->reply) {
                        $this->reply = new Comment($this->reply); // initialise new comment as reply
                    }
                    return $this;
                } else {
                    return false;
                }
            }
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
     * Private: Process a database query
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
     * Getter functions
     */
    public function getID()         { return $this->id; }
    public function getArticle()    { return $this->article; }
    public function getUser()       { return $this->user; }
    public function getTimestamp()  { return $this->timestamp; }

    /*
     * Public: Get comment content
     */
    public function getContent() { 
        $output = '';
        // Add link to reply comment
        if($this->reply) { 
            $output .= '<a href="'.curPageURLNonSecure().'#comment'.$this->reply->getID().'" id="replyLink">';
            $output .= '@'.$this->reply->getName().':</a> '; 
        } 
        $output .= html_entity_decode(nl2br(trim($this->content))); 
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
     * Setter functions
     */

    /*
     * Private: Set object ID
     *
     * $id - id of comment
     *
     * Returns comment object
     */
    private function setID($id) {
        $this->id = $id;
        return $this;
    }

    /*
     * Public: Set comment as external or not
     *
     * $external - flag for if comment is external or not
     *
     * Returns TODO
     */
    public function setExternal($external) {
        $this->external = $external;
    }

    /*
     * Public: Set comment article
     *
     * $article - id of article
     *
     * Returns TODO
     */
    public function setArticle($article) {
        $this->article = $article;
    }

    /*
     * Public: Set comment content
     *
     * $content - comment content (no need to be escaped etc)
     *
     * Returns TODO
     */
    public function setContent($content) {
        $this->content = mysql_real_escape_string(get_correct_utf8_mysql_string($content));
    }

    /*
     * Public: Set user
     *
     * $username - username of user commenting
     */
    public function setUser($username) {
        $this->user = $username;
    }

    /*
     * Public: Set name of commenter (external only)
     *
     * $name - name of commenter
     */
    public function setName($name) {
        $this->name = mysql_real_escape_string(get_correct_utf8_mysql_string($name));
    }

    /*
     * Public: Set reply of comment
     *
     * $reply - id of comment that this comment is replying to
     */
    public function setReply($reply) {
        $this->reply = new Comment(mysql_real_escape_string($reply));
    }

    /*
     * Public: Check if comment already exists
     *
     * Returns the number of rows that the query will return
     *  i.e. 0 for none found. >0 if found
     */
    public function commentExists() {
        if(!$this->external) {
            $sql = "SELECT id FROM `comment` WHERE article=".$this->article." AND user='".$this->user."' AND comment='".$this->content."' AND `active`=1";
        } else {
            $sql = "SELECT id FROM `comment_ext` WHERE article=".$this->article." AND name='".$this->name."' AND comment='".$this->content."'";
        }
        if($rsc = $this->dbquery($sql)) {
            return mysql_num_rows($rsc);
        }
    }

    /* 
     * Public: Insert new comment into database
     *
     * Returns id of new comment
     */
    public function insert() {
        if(!$this->external) { // if internal
            $sql = "INSERT INTO `comment` (article,user,comment,reply) VALUES ('".$this->article."','".$this->user."','".$this->content."','".$this->getReplyID()."')"; // insert comment into database
            $rsc = $this->dbquery($sql);
            $this->id = mysql_insert_id(); // get id of inserted comment

            if($this->reply && !$this->reply->isExternal()) { // if comment is replying to a comment 
                /* Send email */
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

                $email->send();
            }

            email_article_comment(
                $this->article,
                $this->user,
                $this->content,
                $this->id
            ); // email comment to authors of article

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
                $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending,reply,spam) VALUES ('".$this->article."','".$this->name."','".$this->content."',0,'".$_SERVER['REMOTE_ADDR']."',0,'".$this->getReplyID()."',1)";
                $rsc = $this->dbquery($sql);
                $this->id = mysql_insert_id(); // get id of inserted comment

                // insert comment ip into comment_spam
                $sql = "INSERT IGNORE INTO `comment_spam` (IP, date) VALUES ('".$_SERVER['REMOTE_ADDR']."', DATE_ADD(NOW(), INTERVAL 2 MONTH))";
                $rsc = $this->dbquery($sql);

                return 'spam';
            } else {
                $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending,reply) VALUES ('".$this->article."','".$this->name."','".$this->content."',1,'".$_SERVER['REMOTE_ADDR']."',1,'".$this->getReplyID()."')";
                $rsc = $this->dbquery($sql);
                $this->id = mysql_insert_id(); // get id of inserted comment

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

                if($email->send()) {
                    return $this->id;
                } else {
                    return false;
                }
            }
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
