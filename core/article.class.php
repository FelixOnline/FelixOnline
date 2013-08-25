<?php
/*
 * Article class
 * Deals with both article retrieval and article submission
 *
 * Fields:
 *	  id			  - id of article 
 *	  title		   - title of article 
 *	  short_title	 - short title of article for boxes on front page [optional]
 *	  teaser		  - article teaser 
 *	  author		  - first author of article, superseded by article_author table [depreciated] 
 *	  category		- id of category article is in
 *	  date			- timestamp when article was added to site
 *	  approvedby	  - user who approved the article to be published
 *	  published	   - timestamp when article was published
 *	  hidden		  - if article is hidden from engine
 *	  text1		   - id of main article text
 *	  img1			- id of main article image
 *	  text2		   - id of second article text [depreciated]
 *	  img2			- id of second image text [depreciated]
 *	  img2lr		  - not quite sure [TODO]
 *	  hits			- number of views the article has had
 *	  short_desc	  - short description of article for boxes on front page [optional]
 */
class Article extends BaseModel {
	private $authors; // array of authors of article 
	private $approvedby; // user object of user who approved article
	private $category_cat; // category cat (short version)
	private $category_label; // category label
	private $content; // article content
	private $image; // image class
	private $image_title; // image title
	private $num_comments; // number of comments
	private $category; // category class
	private $search = array('@<>@',
		'@<script[^>]*?>.*?</script>@siU',  // javascript
		'@<style[^>]*?>.*?</style>@siU',	// style tags
		'@<embed[^>]*?>.*?</embed>@siU',	// embed
		'@<object[^>]*?>.*?</object>@siU',	// object
		'@<iframe[^>]*?>.*?</iframe>@siU',	// iframe	  
		'@<![\s\S]*?--[ \t\n\r]*>@',		// multi-line comments including CDATA
		'@</?[^>]*>*@' 		  // html tags
	);
	protected $db;
	
	/*
	 * Constructor for Article class
	 * If initialised with id then store relevant data in object
	 *
	 * $id - ID of article (optional)
	 *
	 * Returns article object
	 */
	function __construct($id=NULL) {
		global $db;
		$this->db = $db;
		//$this->db->cache_queries = true;
		if($id !== NULL) { // if creating an already existing article object
			$sql = "SELECT 
						`id`,
						`title`,
						`short_title`,
						`teaser`,
						`author`,
						`approvedby`,
						`category`,
						UNIX_TIMESTAMP(`date`) as date,
						UNIX_TIMESTAMP(`published`) as published,`hidden`,
						`text1`,
						`text2`,
						`img1`,
						`img2`,
						`img2lr`,
						`hits` 
					FROM `article` 
					WHERE id=".$id;
			parent::__construct($this->db->get_row($sql), 'Article', $id);
			//$this->db->cache_queries = false;
			return $this;
		} else {
			// initialise new article
		}
	}
	
	/*
	 * Public: Get array of authors of article
	 *
	 * Returns array
	 */
	public function getAuthors() { 
		if(!$this->authors) {
			$sql = "SELECT 
					article_author.author as author 
					FROM `article_author` 
					INNER JOIN `article` 
					ON (article_author.article=article.id) 
					WHERE article.id=".$this->getId();
			$authors = $this->db->get_results($sql);
			foreach($authors as $author) {
				$this->authors[] = new User($author->author);
			}
		}
		return $this->authors; 
	}

	/*
	 * Public: Get approved by user
	 *
	 * Returns User object
	 */
	public function getApprovedBy() {
		if(!$this->approvedby) {
			$this->approvedby = new User($this->fields['approvedby']);
		}
		return $this->approvedby;
	}

	/*
	 * Public: Get list of authors in english
	 *
	 * Returns html string of article authors
	 */
	public function getAuthorsEnglish() {
		$array = $this->getAuthors();
		// sanity check
		if (!$array || !count ($array))
			return '';
		// change array into linked usernames
		foreach ($array as $key => $user) {
			$full_array[$key] = '<a href="'.$user->getURL().'">'.$user->getName().'</a>';
		}
		// get last element
		$last = array_pop($full_array);
		// if it was the only element - return it
		if (!count ($full_array))
			return $last;
		return implode (', ', $full_array).' and '.$last;
	}

	/*
	 * Public: Get category class
	 */
	public function getCategory() {
		if(!$this->category) {
			$this->category = new Category($this->getCategoryCat());
		}
		return $this->category;
	}

	/*
	 * Public: Get cat of article category
	 */
	public function getCategoryCat() {
		if(!$this->category_cat || !$this->category_label) {
			$sql = "SELECT 
						`cat`,
						label 
					FROM `category` 
					WHERE id = ".$this->fields['category'];
			$cat = $this->db->get_row($sql);
			$this->category_cat = $cat->cat;
			$this->category_label = $cat->label;
		}
		return $this->category_cat;
	}

	/*
	 * Public: Get label of article category
	 */
	public function getCategoryLabel() {
		if(!$this->category_label || !$this->category_cat) {
			$sql = "SELECT cat,`label` FROM `category` WHERE id = ".$this->getCategory();
			$cat = $this->db->get_row($sql);
			$this->category_label = $cat->label;
			$this->category_cat = $cat->cat;
		}
		return $this->category_label;
	}

	/*
	 * Public: Get category url
	 */
	public function getCategoryURL() {
		return STANDARD_URL.$this->getCategoryCat().'/';
	}

	/*
	 * Public: Get article content
	 */
	public function getContent() {
		if(!$this->content) {
			$sql = "SELECT `content` FROM `text_story` WHERE id = ".$this->getText1();
			$this->content = $this->db->get_var($sql);
		}
		return $this->cleanText($this->content);
	}

	/*
	 * Private: Clean text
	 */
	private function cleanText($text) {
		$result = strip_tags($text, '<p><a><div><b><i><br><blockquote><object><param><embed><li><ul><ol><strong><img><h1><h2><h3><h4><h5><h6><em><iframe><strike>'); // Gets rid of html tags except <p><a><div>
		$result = preg_replace('#<div[^>]*(?:/>|>(?:\s|&nbsp;)*</div>)#im', '', $result); // Removes empty html div tags
		$result = preg_replace('#<span*(?:/>|>(?:\s|&nbsp;)[^>]*</span>)#im', '', $result); // Removes empty html div tags
		$result = preg_replace('#<p[^>]*(?:/>|>(?:\s|&nbsp;)*</p>)#im', '', $result); // Removes empty html p tags
		//$result = preg_replace("/<(\/)*div[^>]*>/", "<\\1p>", $result); // Changes div tags into <p> tags
		return $result;
	}

	/*
	 * Public: Get article teaser
	 * TODO
	 *
	 * Returns string
	 */
	public function getTeaserFull() {
		if ($this->getTeaser()) {
			return str_replace('<br/>','',strip_tags($this->getTeaser()));
			//return str_replace('<br/>','',preg_replace($this->search,'',$this->teaser));
		} else {
			$text = $this->getText(1);
			return trim(substr(strip_tags($text),0,strrpos(substr(strip_tags($text),0,TEASER_LENGTH),' '))).'...';
		}
	}

	/*
	 * Public: Get article preview with word limit
	 * Shortens article content to word limit
	 *
	 * $limit - word limit [defaults to 50]
	 */
	public function getPreview($limit = 50) {
		$string = strip_tags($this->getContent());
		$words = explode(" ",$string);
		if(count($words) > $limit) {
		  $append = ' ... <br/><a href="'.$this->getURL().'" title="Read more" id="readmorelink">Read more</a>';
		}
		return implode(" ",array_splice($words,0,$limit)) . $append;
	}

	/*
	 * Public: Get short description
	 * If a short description is specified in the database then use that. 
	 * Otherwise limit article content to a certain character length
	 *
	 * $limit - character limit for description [defaults to 80]
	 */
	public function getShortDesc($limit = 80) {
		if($this->fields['short_desc']) {
			return substr($this->fields['short_desc'], 0, $limit);
		} else {
			return substr(strip_tags($this->getContent()), 0, $limit);
		}
	}

	/*
	 * Public: Get number of comments on article
	 *
	 * Returns int
	 */
	public function getNumComments() {
		if(!$this->num_comments && $this->num_comments !== 0) {
			$sql = "SELECT SUM(count) AS count 
				FROM (
					SELECT article,COUNT(*) AS count 
					FROM `comment` 
					WHERE article=".$this->getId()." 
					AND `active`=1 
					GROUP BY article 
					UNION ALL 
					SELECT article,COUNT(*) AS count 
					FROM `comment_ext` 
					WHERE article=".$this->getId()." 
					AND `active`=1 
					AND `pending`=0 
					GROUP BY article
				) AS t GROUP BY article";
			$this->num_comments = $this->db->get_var($sql);
			if(!$this->num_comments) $this->num_comments = 0;
		}
		return $this->num_comments;
	}

	/*
	 * Public: Get comments
	 *
	 * Returns db object
	 */
	public function getComments() {
		$sql = "SELECT id,timestamp 
				FROM (
					SELECT 
						comment.id,
						UNIX_TIMESTAMP(comment.timestamp) AS timestamp 
					FROM `comment` 
					WHERE article=".$this->getId()." 
					AND active=1". // select all internal comments 
				" UNION SELECT 
						comment_ext.id,
						UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp 
					FROM `comment_ext` 
					WHERE article=".$this->getId()." 
					AND pending=0 AND spam=0". // select external comments that are not spam
				" UNION SELECT 
						comment_ext.id,
						UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp 
						FROM `comment_ext`
					WHERE article=".$this->getId()." 
					AND IP = '".$_SERVER['REMOTE_ADDR']."' 
					AND active=1 
					AND pending=1 
					AND spam=0". // select external comments that are pending and are from current ip
			//" UNION SELECT comment_ext.id,UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp FROM `comment_ext` WHERE article=$article AND IP != '".$_SERVER['REMOTE_ADDR']."' AND active=1 AND pending=0". // select external comments that have been approved and not from current ip
			//" UNION SELECT comment_ext.id,UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp FROM `comment_ext` WHERE article=$article AND IP = '".$_SERVER['REMOTE_ADDR']."' AND active=1 AND pending=0". // select external comments that have been approve and are from current ip
				") AS t 
				ORDER BY timestamp ASC 
				LIMIT 500";
		$comments = array();
		$rsc = $this->db->get_results($sql);
		if($rsc) {
			foreach($rsc as $key => $obj) {
				$comments[] = new Comment($obj->id);
			}
		}
		return $comments;
	}

	/*
	 * Public: Get image class
	 */
	public function getImage() {
		if($this->getImg1()) { 
			if($this->getImg1() == 183 || $this->getImg1() == 742) {
				return false;
			} else {
				if(!$this->image) {
					$this->image = new Image($this->getImg1());
				}
				return $this->image;
			}
		} else {
			return false;
		}
	}

	/*
	 * Public: Get full article url
	 *
	 * Returns string
	 */
	public function getURL() {
		return STANDARD_URL.$this->constructURL();
	}

	/*
	 * Private: Construct url for article from title and category label
	 *
	 * Returns string
	 */
	private function constructURL() {
		$cat = $this->getCategoryCat();
		$dashed = Utility::urliseText($this->getTitle());
		$output = $cat.'/'.$this->getId().'/'.$dashed.'/'; // output: CAT/ID/TITLE/
		return $output;
	}

	/*
	 * Public: Log visit and increment hit count on article
	 * Check if user has visited page before (based on ip or user for a set length of time)
	 */
	public function logVisit() {
		if(!$this->recentlyVisited()) {
			$this->logVisitor();
			$this->hitArticle();
		} else {
			$this->logVisitor(1);
		}
	}

	/*
	 * Private: Increment hit count on article
	 */
	private function hitArticle() {
		$sql = "UPDATE `article` SET hits=hits+1 WHERE id=".$this->getId();
		return $this->db->query($sql);
	}

	/*
	 * Private: Add log of visitor into article_vist table
	 */
	private function logVisitor($repeat = 0) {
		global $currentuser;
		$user = NULL;
		if($currentuser->isLoggedIn()) $user = $currentuser->getUser();
		$sql = "INSERT INTO 
					article_visit 
				(
					article,
					user,
					IP,
					browser,
					referrer, 
					repeat_visit
				) VALUES (
					'".$this->getId()."',
					'".$user."',
					'".$this->db->escape($_SERVER['REMOTE_ADDR'])."',
					'".$this->db->escape($_SERVER['HTTP_USER_AGENT'])."',
					'".$this->db->escape($_SERVER['HTTP_REFERER'])."',
					'".$repeat."'
				)";
		return $this->db->query($sql);
	}

	/*
	 * Private: Check if user has recently visited article
	 *
	 * Returns boolean
	 */
	private function recentlyVisited() {
		global $currentuser;
		if($currentuser->isLoggedIn()) {
			$sql = "SELECT
						COUNT(id)
					FROM
						`article_visit`
					WHERE user = '".$currentuser->getUser()."'
					AND article = '".$this->getId()."'
					AND UNIX_TIMESTAMP(timestamp) < now() - interval 4 week";
			return $this->db->get_var($sql);
		} else {
			$sql = "SELECT
						COUNT(id)
					FROM
						`article_visit`
					WHERE IP = '".$_SERVER['REMOTE_ADDR']."'
					AND browser = '".$_SERVER['HTTP_USER_AGENT']."'
					AND article = '".$this->getId()."'
					AND UNIX_TIMESTAMP(timestamp) < now() - interval 4 week";
			return $this->db->get_var($sql);
		}
	}

	public function print_this() {
		print_r($this);
	}
}

