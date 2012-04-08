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

    /*
     * Public: Get videos
     *
     * Returns array of MediaVideo objects
     */
    public function getVideos($limit = NULL) {
        $sql = "SELECT 
                    `id` 
                FROM `media_video`
                WHERE hidden = 0
                ORDER BY date DESC
                "; 
        if($limit) {
            $sql .= "LIMIT 0, ".$limit;
        }
        $albums = $this->db->get_results($sql);
        foreach($albums as $key => $object) {
            $output[] = new MediaVideo($object->id);
        }
        return $output;
    }

    /*
     * Public: Get radio shows
     * Get IC Radio shows
     *
     * Returns array of radio shows
     */
    public function getRadioShows() {
        $doc = new DOMDocument();
        $doc->load('https://icradio-firestar.media.su.ic.ac.uk/external/felix.php');
        $arrFeeds = array();
        foreach ($doc->getElementsByTagName('show') as $node) {
            $itemRSS = array ( 
                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                'dj' => $node->getElementsByTagName('dj')->item(0)->nodeValue,
                'genre' => $node->getElementsByTagName('genre')->item(0)->nodeValue,
                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
            );
            array_push($arrFeeds, $itemRSS);
        }
        return $arrFeeds;
    }
}
?>
