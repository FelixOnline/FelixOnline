<?php
/*
 * User class
 *
 * Fields:
 *      user        -
 *      name        -
 *      visits      -
 *      ip          -
 *      timestamp   -
 *      role        -
 *      description -
 *      email       -
 *      facebook    -
 *      twitter     -
 *      websitename -
 *      websiteurl  -
 *      img         -
 */
class User extends BaseModel {
    protected $db;
    private $articles;
    private $count;

    function __construct($uname = NULL) {
        /* initialise db connection and store it in object */
        global $db;
        $this->db = $db;
        if($uname !== NULL) {
            $sql = "SELECT 
                `user`,
                `name`,
                `visits`,
                `ip`,
                UNIX_TIMESTAMP(`timestamp`) as timestamp,
                `role`,
                `description`,
                `email`,
                `facebook`,
                `twitter`,
                `websitename`,
                `websiteurl`,
                `img` 
                FROM `user` 
                WHERE user='".$uname."'";
            parent::__construct($this->db->get_row($sql), 'User', $uname);
            //$this->db->cache_queries = false;
            return $this;
        } else {
        }
    }

    protected function setName($name) {
        $this->fields['name'] = $name;
        return $this->fields['name'];
    }

    /*
     * Public: Get url for user
     *
     * $page - page to link to
     */
    public function getURL($pagenum = NULL) {
        $output = STANDARD_URL.'user/'.$this->getUser().'/'; 
        if($pagenum != NULL) {
            $output .= $pagenum.'/';
        }
        return $output;
    }

    /*
     * Public: Get articles
     * Get all articles from user
     */
    public function getArticles($page = NULL) {
        $sql = "SELECT 
                    id 
                FROM `article` 
                INNER JOIN `article_author` 
                    ON (article.id=article_author.article) 
                WHERE article_author.author='".$this->getUser()."' 
                AND published < NOW()
                ORDER BY article.date DESC
        ";
        if($page) {
            $sql .= " LIMIT ".($page-1)*ARTICLES_PER_USER_PAGE.",".ARTICLES_PER_USER_PAGE;
        }
        $this->articles = $this->db->get_results($sql);    
        return $this->articles;
    }

    /*
     * Public: Get number of pages in a category
     *
     * Returns int 
     */
    public function getNumPages() {
        if(!$this->count) {
            $sql = "SELECT 
                        COUNT(id) as count 
                    FROM `article` 
                    INNER JOIN `article_author` 
                        ON (article.id=article_author.article) 
                    WHERE article_author.author='".$this->getUser()."' 
                    AND published < NOW()
                    ORDER BY article.date DESC
            ";
            $this->count = $this->db->get_var($sql);
        }
        $pages = ceil(($this->count - ARTICLES_PER_USER_PAGE) / (ARTICLES_PER_USER_PAGE)) + 1;
        return $pages;
    }
}
?>
