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
     *
     * Returns array of id's of photo albums
     */
    public function getPhotos($limit = NULL) {
        $sql = "SELECT 
                    `id` 
                FROM `media_photo`
                WHERE visible = 1
                "; 
        if($limit) {
            $sql .= "LIMIT 0, ".$limit;
        }
        $albums = $this->db->get_results($sql);
        foreach($albums as $key => $object) {
            $output[] = $object->id;
        }
        return $output;
    }
}
?>
