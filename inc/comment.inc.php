<?php

/*
 * Comment class
 * Deals with both comment retrieval and comment submission
 *
 * Examples
 *      // Get comment
 *      $comment = new Comment();
 *      $comment->init(300,false);
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
	private $intAuthor; // username of author of comment [internal]
	private $extAuthor; // name of author of comment [external]
    private $reply; // id of comment that this comment is replying to
    private $external; // if comment is external or not
    private $time;
    private $active;
    private $ip;
    private $pending;
    private $spam;
	
    /*
     * Constructor for Comment class
     *
     */
	public function __construct() {
	}

    /*
     * Public: Initialise object with comment id. Only used when dealing with an existing comment.
     *
     * $id          - ID of comment
     * $external    - Boolean on whether comment is external or not
     *
     * Returns comment object
     */
    public function init($id, $external) {
		$this->id = $id;
		$this->external = $external;
        if(!$external) { // if comment is internal
            $sql = "SELECT `article`,`user`,`comment`,UNIX_TIMESTAMP(`timestamp`),`active`,`reply` FROM `comment` WHERE id=$id";
            if($rsc = $this->dbquery($sql)) {
                list(
                    $this->article,
                    $this->intAuthor,
                    $this->content,
                    $this->time,
                    $this->active,
                    $this->reply,
                ) = mysql_fetch_array($rsc);
                return $this;
            } else {
                return false;
            }
        } else {
            $sql = "SELECT `article`,`name`,`comment`,UNIX_TIMESTAMP(`timestamp`),`active`,`IP`,`pending`,`reply`,`spam` FROM `comment_ext` WHERE id=$id";
            if($rsc = $this->dbquery($sql)) {
                list(
                    $this->article,
                    $this->extAuthor,
                    $this->content,
                    $this->time,
                    $this->active,
                    $this->ip,
                    $this->pending,
                    $this->reply,
                    $this->spam,
                ) = mysql_fetch_array($rsc);
                return $this;
            } else {
                return false;
            }
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
    public function getAuthor()     { return $this->author; }

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
