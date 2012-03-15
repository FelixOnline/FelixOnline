<?php
/*
 * Blog post class
 *
 * Fields:
 *      id:         - id of blog post
 *      blog:       - id of blog that post is on
 *      content:    - content of post
 *      timestamp:  - timestamp of post (updates on modification)
 *      author:     - uname of post author
 *      type:       - type of post [optional]
 *      meta:       - JSON encoded array of post meta [optional]
 */
class BlogPost extends BaseModel {
    protected $db;
    protected $author; // post author

    function __construct($id=NULL) {
        global $db;
        $this->db = $db;
        if($id !== NULL) {
            $sql = "SELECT
                        `id`,
                        `blog`,
                        `content`,
                        UNIX_TIMESTAMP(timestamp) as timestamp,
                        `author`,
                        `type`,
                        `meta`
                    FROM `blog_post`
                    WHERE id='".$id."'";
            parent::__construct($this->db->get_row($sql), 'BlogPost', $slug);
            return $this;
        } else {
            // initialise new blog post
        }
    }

    /*
     * Get author
     *
     * Returns user object of post author
     */
    public function getAuthor() {
        if(!$this->author) {
            $this->author = new User($this->fields['author']);
        }
        return $this->author;
    }
}
?>
