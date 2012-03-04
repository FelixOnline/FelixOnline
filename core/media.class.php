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
     * Returns array of MediaPhoto objects
     */
    public function getAlbums($limit = NULL) {
        $sql = "SELECT 
                    `id` 
                FROM `media_photo_album`
                WHERE visible = 1
                ORDER BY date DESC
                "; 
        if($limit) {
            $sql .= "LIMIT 0, ".$limit;
        }
        $albums = $this->db->get_results($sql);
        foreach($albums as $key => $object) {
            $output[] = new MediaPhoto($object->id);
        }
        return $output;
    }
}
?>
