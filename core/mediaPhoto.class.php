<?php
/*
 * Media Photo class
 *
 * Fields:
 *      id          - id of photo album 
 *      folder      - [depreciated]
 *      title       - title of photo album
 *      author      - author of photo album [string]
 *      date        - date of photo album
 *      description - 
 *      order       - 
 *      visible     -
 *      thumbnail   - id of thumbnail of photo album
 *      hits        -
 *      
 */
class MediaPhoto extends BaseModel {
    protected $type;

    function __construct($id = NULL) {
        global $db;
        $this->db = $db;
        $this->type = 'photo';
        if($id !== NULL) {
            $sql = "SELECT 
                    `id`,
                    `folder`,
                    `title`,
                    `author`,
                    UNIX_TIMESTAMP(`date`) as date,
                    `description`,
                    `order`,
                    `visible`,
                    `thumbnail`,
                    `hits`
                FROM `media_photo` 
                WHERE id=".$id;
            parent::__construct($this->db->get_row($sql), get_class($this), $id);
            return $this;
        } else {
            return $this;
        }
    }

    public function getType() {
        return $this->type;
    }

    /*
     * Public: Get photo album url
     */
    public function getURL() {
        $url = STANDARD_URL
            .'media/photo/'
            .$this->getId()
            .'/'
            .Utility::urliseText($this->getTitle())
            .'/';
        return $url;
    }
}
?>
