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
 *      $comment->setAuthor('jk708');
 *      if($comment->insert()) echo 'Success!';
 */
class Comment {
	private $id; // id of comment
	private $article; // article id comment is on
	private $content; // content of comment
    private $name; // name of author of comment
	private $user; // username of author of comment [internal] [TODO] be user object
	private $extAuthor; // name of author of comment [external]
    private $reply; // id of comment that this comment is replying to [TODO] be comment object
    private $external; // if comment is external or not
    private $time; // unix timestamp of comment submission time
    private $active; // whether comment is active
    private $ip; // ip of commenter [external]
    private $pending; // if comment is pending [external]
    private $spam; // if comment is spam [external]
	
    /*
     * Constructor for Comment class
     * If initialised with an id then store relevant data
     * Do nothing if not
     *
     * $id          - ID of comment
     * $external    - Boolean on whether comment is external or not. True if it is an external comment, false if not
     *
     * Returns comment object. Returns false if something goes wrong.
     */
	public function __construct($id=NULL) {
        if($id != NULL) {
            $this->id = $id;
            if($id >= EXTERNAL_COMMENT_ID) { // if id is greater than, or equal to, external comment id start
                $this->external = true;
            } else {
                $this->external = false;
            }
            if(!$external) { // if comment is internal
                $sql = "SELECT `article`,`user`,`comment`,UNIX_TIMESTAMP(`timestamp`),`active`,`reply` FROM `comment` WHERE id=$id";
                if($rsc = $this->dbquery($sql)) {
                    list(
                        $this->article,
                        $this->user,
                        $this->content,
                        $this->time,
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
                $sql = "SELECT `article`,`name`,`comment`,UNIX_TIMESTAMP(`timestamp`),`active`,`IP`,`pending`,`reply`,`spam` FROM `comment_ext` WHERE id=$id";
                if($rsc = $this->dbquery($sql)) {
                    list(
                        $this->article,
                        $this->name,
                        $this->content,
                        $this->time,
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
    public function getContent()    { return html_entity_decode(nl2br($this->content)); }
    public function getUser()       { return $this->username; }

    /*
     * Public: Get commenter's name
     */
    public function getName() {
        if($this->isExternal()) { // if commenter is external
            if($this->name) { // if external commenter has a name
                return $this->name;
            } else {
                return 'Anonymous'; // else return Anonymous
            }
        } else {
            return $this->name; // return username
        }
    }

    /*
     * Public: Get comment reply
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
     * Setter functions
     */

    /*
     * Private: Set object ID
     *
     * $id - id of comment
     *
     * Returns comment object
     */
    private function setId($id) {
        $this->id = $id;
        return $this;
    }

	public function print_this() {
		print_r($this);
	}
}

?>
