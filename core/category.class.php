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
}
?>
