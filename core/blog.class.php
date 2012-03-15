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
            // initialise new page
        }
    }

}

?>

