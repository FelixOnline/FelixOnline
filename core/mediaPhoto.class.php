<?php
/*
 * Media Photo Album class
 *
 * Fields:
 *	  id		  - id of photo album 
 *	  folder	  - [depreciated]
 *	  title	   - title of photo album
 *	  author	  - author of photo album [string]
 *	  date		- date of photo album
 *	  description - 
 *	  order	   - 
 *	  visible	 -
 *	  thumbnail   - id of thumbnail of photo album
 *	  hits		-
 *	  
 */
class MediaPhoto extends BaseModel {
	protected $type;
	private $thumbnail; // album thumbnail
	private $images; // array of images in album
	private $mostViewed; // array of most viewed albums

	function __construct($id = NULL) {
		global $db, $safesql;
		$this->db = $db;
		$this->safesql = $safesql;
		$this->type = 'photo';
		if($id !== NULL) {
			$sql = $this->safesql->query(
				"SELECT 
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
				FROM `media_photo_album` 
				WHERE id=%i",
				array(
					$id,
				));
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

	/*
	 * Public: Get thumbail
	 *
	 * Returns mediaPhoto object of thumbnail
	 */
	public function getThumbnail() {
		if(!$this->thumbnail) {
			$this->thumbnail = new MediaPhotoImage($this->fields['thumbnail']);
		}
		return $this->thumbnail;
	}

	/*
	 * Public: Get images
	 *
	 * Returns array of mediaPhotoImage objects
	 */
	public function getImages() {
		if(!$this->images) {
			$sql = $this->safesql->query(
				"SELECT 
					`id`
				FROM
					`media_photo_image`
				WHERE
					album_id = %i 
				ORDER BY id",
				array(
					$this->getId(),
				));
			$photos = $this->db->get_results($sql);
			foreach($photos as $object) {
				$this->images[] = new MediaPhotoImage($object->id);
			}
		}
		return $this->images;
	}

	/*
	 * Public: Get most viewed photo albums
	 *
	 * Returns array of mediaPhoto objects
	 */
	public function getMostViewed() {
		if(!$this->mostViewed) {
			$sql = $this->safesql->query(
				"SELECT
					`id`
				FROM
					`media_photo_album`
				WHERE
					visible = '1'
				ORDER BY hits DESC
				LIMIT 0, 3", array());
			$albums = $this->db->get_results($sql);
			foreach($albums as $object) {
				$this->mostViewed[] = new MediaPhoto($object->id);
			}
		} 
		return $this->mostViewed;
	}

	/*
	 * Public: Hit photo album
	 */
	public function hit() {
		$sql = $this->safesql->query(
			"UPDATE 
				`media_photo_album` 
			SET hits=hits+1 
			WHERE id=%i",
			array(
				$this->getId(),
			));
		return $this->db->query($sql);
	}
}
