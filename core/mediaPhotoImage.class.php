<?php
/*
 * Media Photo Image
 *
 * Fields:
 *      id -
 *      album_id -
 *      name -
 *      date -
 *      title -
 *      caption -
 *      camera -
 *      iso -
 *      fstop -
 *      orientation -
 *      tags -
 *      geo_coords
 *
 */
class MediaPhotoImage extends BaseModel {

    function __construct($id = NULL) {
        global $db;
        $this->db = $db;
        if($id !== NULL) {
            $sql = "
                SELECT
                    `id`,
                    `album_id`,
                    `name`,
                    `date`,
                    `title`,
                    `caption`,
                    `camera`,
                    `iso`,
                    `fstop`,
                    `orientation`,
                    `tags`,
                    `geo_coords`
                FROM  
                    `media_photo_image`
                WHERE
                    id = ".$id;
            parent::__construct($this->db->get_row($sql), get_class($this), $id);
            return $this;
        } else {
            return $this;
        }
    }

    /*
     * Public: Get URL
     *
     * Returns string of url
     */
    public function getURL($width = '', $height = '') {
        $url =  GALLERY_IMAGE_URL;
        if($height) {
            $url .= $width.'/'.$height.'/'.$this->getName();
        } else if($width) {
            $url .= $width.'/'.$this->getName();
        } else {
            $url .= 'gallery_images/images/'.$this->getName();
        }
        return $url;
    }
}
?>
