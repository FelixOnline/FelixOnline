<?php
/*
 * Page Class
 *
 * Fields:
 *      id:         - id of page
 *      slug:       - url slug of page
 *      title:      - title of page
 *      content:    - content of page
 */
class Page extends BaseModel {
    protected $db;

    function __construct($slug=NULL) {
        global $db;
        $this->db = $db;
        if($slug !== NULL) {
            $sql = "SELECT
                        `id`,
                        `slug`,
                        `title`,
                        `content`
                    FROM `pages`
                    WHERE slug='".$slug."'";
            parent::__construct($this->db->get_row($sql));
            return $this;
        } else {
            // initialise new page
        }
    }
}

?>
