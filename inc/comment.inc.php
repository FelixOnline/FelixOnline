<?php

/*
 * Comment class
 *  Deals with comment submission
 */
class Comment {
	private $id; // id of comment
	private $article; // article id comment is on
	private $content; // content of comment
	private $author; // [array] author of comment
    private $reply; // [array] info of comment that this comment is replying to
	
	public function __construct($content,$uname,$article,$replyName,$replyComment) {
        $this->content = $content;
        $this->author['uname'] = $uname;
	}
	
	public function print_this() {
		print_r($this);
	}
	
}

?>
