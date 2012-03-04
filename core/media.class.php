<?php
/*
 * Media class
 * Provides functions to get other media types
 */

class Media {
    protected $db;
    function __construct() {
        global $db;
        $this->db = $db; 
    }

    /*
     * Public: Get photo albums
     */
    public function getPhotos($limit = NULL) {
        $sql = "SELECT `id` 
                FROM `media_photo`
                WHERE visible = 1
                "; 
        if($limit) {
            $sql .= "LIMIT 0, ".$limit;
        }
        return $this->db->get_results($sql);
    }
}
?>
