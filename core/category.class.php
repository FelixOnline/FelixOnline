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
class Category extends BaseModel
{
    protected $db;
    private $editors = array();
    private $count; // number of articles in catgeory
    private $stories; // array of top story objects

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
            parent::__construct($this->db->get_row($sql), 'Category', $cat);
            return $this;
        } else {
        }
    }

    /*
     * Public: Get category url
     */
    public function getURL($pagenum = NULL) {
        $output = STANDARD_URL.$this->getCat().'/';
        if($pagenum != NULL) {
            $output .= $pagenum.'/';
        }
        return $output;
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
            if (is_null($editors)) {
            	$this->editors = null;
            } else {
	            foreach ($editors as $key => $object) {
	                $this->editors[] = new User($object->user);
	            }
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
                    WHERE published < NOW() 
                    AND category=".$this->getId();
            $this->count = $this->db->get_var($sql);
        }
        $pages = ceil(($this->count - ARTICLES_PER_CAT_PAGE) / (ARTICLES_PER_SECOND_CAT_PAGE)) + 1;
        return $pages;
    }

    public function getTopStories() {
        if(!$this->stories) {
            $this->stories['top_story_1'] = new Article($this->fields['top_slider_1']);
            $this->stories['top_story_2'] = new Article($this->fields['top_slider_2']);
            $this->stories['top_story_3'] = new Article($this->fields['top_slider_3']);
            $this->stories['top_story_4'] = new Article($this->fields['top_slider_4']);
        }
        return $this->stories;
    }

    /**
     * Static: Get all categories
     */
    public static function getCategories()
    {
        global $db;
        $sql = "SELECT
                    label,
                    cat
                FROM `category`
                WHERE hidden = 0
                AND id > 0
                ORDER BY `order` ASC";
        $cats = $db->get_results($sql);
        return $cats;
    }
}
