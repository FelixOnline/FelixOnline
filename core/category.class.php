<?php
/*
 * Category class
 *
 * Fields:
 *      id              - 
 *      label           -
 *      cat             -
 *      uri             - [depreciated]
 *      colourclass     - [depreciated]
 *      active          -
 *      top_slider_1    -
 *      top_slider_2    -
 *      top_slider_3    -
 *      top_slider_4    -
 *      top_sidebar_1   -
 *      top_sidebar_2   -
 *      top_sidebar_3   -
 *      top_sidebar_4   -
 *      email           -
 *      twitter         -
 *      description     -
 *      hidden          -
 */
class Category extends BaseModel {
    protected $db;
    private $editors = array();

    function __construct($cat=NULL) {
        global $db;
        $this->db = $db;
        if($cat !== NULL) {
            $sql = "SELECT
                    id,
                    label,
                    cat,
                    uri,
                    colourclass,
                    active,
                    top_slider_1,
                    top_slider_2,
                    top_slider_3,
                    top_slider_4,
                    top_sidebar_1,
                    top_sidebar_2,
                    top_sidebar_3,
                    top_sidebar_4,
                    email,
                    twitter,
                    description,
                    hidden
                FROM category
                WHERE cat='".$cat."'";
            parent::__construct($this->db->get_row($sql));
            return $this;
        } else {
        }
    }

    /*
     * Public: Get category url
     */
    public function getURL() {
        return STANDARD_URL.$this->getCat().'/';
    }

    /*
     * Public: Get category editors
     *
     * Returns array of user objects
     */
    public function getEditors() {
        if(!$this->editors) {
            $sql = "SELECT 
                        user 
                    FROM `category_author` 
                    WHERE category='".$this->getId()."' 
                    AND admin=1
            ";
            $editors = $this->db->get_results($sql);
            foreach($editors as $key => $object) {
                $this->editors[] = new User($object->user);
            }
        }
        return $this->editors;
    }

    /*
     * Public: Get category articles
     *
     * $page - page number to limit article list
     *
     * Returns dbobject
     */
    public function getArticles($page) {
        $sql = "SELECT 
                    id 
                FROM `article` 
                WHERE published < NOW() 
                AND category=".$this->getId()." 
                ORDER BY published DESC 
                LIMIT ".(($page-1)*ARTICLES_PER_CAT_PAGE)
                .",".ARTICLES_PER_CAT_PAGE;
        return $this->db->get_results($sql);
    }
}
?>
