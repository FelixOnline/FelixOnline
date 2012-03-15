<?php
/*
 * Blog Class
 *
 * Fields:
 *      id:         - id of page
 *      name:       - name of blog
 *      slug:       - url slug of page
 *      controller: - name of controller used to handle blog
 */
class Blog extends BaseModel {
    protected $db;
    protected $posts;

    function __construct($slug=NULL) {
        global $db;
        $this->db = $db;
        if($slug !== NULL) {
            $sql = "SELECT
                        `id`,
                        `name`,
                        `slug`,
                        `controller`
                    FROM `blogs`
                    WHERE slug='".$slug."'";
            parent::__construct($this->db->get_row($sql), 'Blog', $slug);
            return $this;
        } else {
            // initialise new blog
        }
    }

    /*
     * Get posts
     *
     * $page - page number to get posts from [optional]
     *
     * returns array of BlogPost objects
     */
    public function getPosts($page = NULL) {
        if(!$this->posts) {
            $sql = "
                SELECT
                    id
                FROM `blog_post`
                WHERE blog = ".$this->getId()."
                ORDER BY timestamp DESC";
            if($page) {
                $sql .= "
                    LIMIT ".(($page-1)*BLOG_POSTS_PER_PAGE)
                    .",".BLOG_POSTS_PER_PAGE;
            }
            $posts = $this->db->get_results($sql);
            foreach($posts as $object) {
                $this->posts[] = new BlogPost($object->id);
            }
        }
        return $this->posts;
    }
}

?>

