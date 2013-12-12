<?php
/*
 * Image class
 *
 * Fields:
 *	  id			  -
 *	  title		   -
 *	  uri			 -
 *	  user			-
 *	  description	 -
 *	  timestamp	   -
 *	  v_offset		-
 *	  h_offset
 *	  caption
 *	  attribution
 *	  attr_link
 *	  width
 *	  height
 */
class Image extends BaseModel {
	/*
	 * Constructor for Image class
	 * If initialised with id then store relevant data in object
	 *
	 * $id - ID of image (optional)
	 *
	 * Returns image object
	 */
	function __construct($id=NULL) {
		/* initialise db connection and store it in object */
		global $db, $safesql;
		$this->db = $db;
		$this->safesql = $safesql;
		if($id !== NULL) { // if creating an already existing article object
			$sql = $this->safesql->query(
				"SELECT
					`id`,
					`title`,
					`uri`,
					`user`,
					`description`,
					UNIX_TIMESTAMP(`timestamp`) as timestamp,
					`v_offset`,
					`h_offset`,
					`caption`,
					`attribution`,
					`attr_link`,
					`width`,
					`height`
				FROM `image`
				WHERE id=%i",
				array(
					$id,
				));
			parent::__construct($this->db->get_row($sql), 'Image', $id);
			return $this;
		} else {
			// initialise new image
		}
	}

	/*
	 * Public: Get image source url
	 */
	public function getURL($width = '', $height = '') {
		if($this->getUri()) {
			$uri = $this->getName();
			if($height) {
				return IMAGE_URL.$width.'/'.$height.'/'.$uri;
			} else if($width) {
				return IMAGE_URL.$width.'/'.$uri;
			} else {
				return IMAGE_URL.'upload/'.$uri;
			}
		} else {
			return IMAGE_URL.DEFAULT_IMG_URI;
		}
	}

	/*
	 * Public: Check if image is tall or not
	 *
	 * $width - width of place image needs to fit to
	 * $limit - pixel limit to check image against
	 */
	public function isTall($width = 460, $limit = 400) {
		$scale = $this->getWidth()/$width;
		
		if($scale == 0) {
			return false;
		}
		
		$check = $this->getHeight()/$scale;
		if ($check > $limit) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 * Public: Get image name
	 * Get image filename
	 */
	public function getName() {
		return str_replace('img/upload/', '', $this->getUri());
	}
}
