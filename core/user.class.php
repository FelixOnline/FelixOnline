<?php
/*
 * User class
 *
 * Fields:
 *	  user		-
 *	  name		-
 *	  visits	  -
 *	  ip		  -
 *	  timestamp   -
 *	  role		-
 *	  description -
 *	  email	   -
 *	  facebook	-
 *	  twitter	 -
 *	  websitename -
 *	  websiteurl  -
 *	  img		 -
 */
class User extends BaseModel {
	protected $db;
	private $articles;
	private $count;
	private $popArticles = array();
	private $comments = array();

	function __construct($uname = NULL) {
		/* initialise db connection and store it in object */
		global $db;
		global $safesql;
		$this->db = $db;
		$this->safesql = $safesql;

		if($uname !== NULL) {
			$sql = $this->safesql->query(
				"SELECT 
					`user`,
					`name`,
					`visits`,
					`ip`,
					UNIX_TIMESTAMP(`timestamp`) as timestamp,
					`role`,
					`info`,
					`description`,
					`email`,
					`facebook`,
					`twitter`,
					`websitename`,
					`websiteurl`,
					`img` 
				FROM `user` 
				WHERE user='%s'",
				array(
					$uname
				));
			parent::__construct($this->db->get_row($sql), 'User', $uname);
			return $this;
		} else {
		}
	}

	/*
	 * Public: Get url for user
	 *
	 * $page - page to link to
	 */
	public function getURL($pagenum = NULL) {
		$output = STANDARD_URL.'user/'.$this->getUser().'/'; 
		if($pagenum != NULL) {
			$output .= $pagenum.'/';
		}
		return $output;
	}

	/*
	 * Public: Get articles
	 * Get all articles from user
	 */
	public function getArticles($page = NULL) {
		$sql = "SELECT 
				id 
			FROM `article` 
			INNER JOIN `article_author` 
				ON (article.id=article_author.article) 
			WHERE article_author.author='%s'
			AND published < NOW()
			ORDER BY article.date DESC";
		if($page) {
			$sql .= " LIMIT %i, %i";
		}

		$sql = $this->safesql->query($sql, array(
			$this->getUser(),
			($page-1) * ARTICLES_PER_USER_PAGE,
			ARTICLES_PER_USER_PAGE,
		));
		$this->articles = $this->db->get_results($sql);	
		return $this->articles;
	}

	/*
	 * Public: Get popular articles
	 * Get users popular articles
	 */
	public function getPopularArticles() {
		if(!$this->popArticles) {
			$sql = $this->safesql->query(
				"SELECT 
					id 
				FROM `article` 
				INNER JOIN `article_author` 
					ON (article.id=article_author.article) 
				WHERE article_author.author='%s' 
				AND published < NOW()
				ORDER BY hits DESC LIMIT 0, %i",
				array(
					$this->getUser(),
					NUMBER_OF_POPULAR_ARTICLES_USER,
				));
			$articles = $this->db->get_results($sql);
			foreach($articles as $key => $obj) {
				$this->popArticles[] = new Article($obj->id);
			}
		}
		return $this->popArticles;
	}

	/*
	 * Public: Get comments
	 * Get all comments from user
	 */
	public function getComments() {
		if(!$this->comments) {
			$sql = $this->safesql->query(
				"SELECT 
					id
				FROM `comment` 
				WHERE user='%s' 
				ORDER BY timestamp DESC 
				LIMIT 0, %i",
				array(
					$this->getUser(),
					NUMBER_OF_POPULAR_COMMENTS_USER,
				));
			$comments = $this->db->get_results($sql);	
			if($comments) {
				foreach($comments as $key => $obj) {
					$this->comments[] = new Comment($obj->id);
				} 
			}
		}
		return $this->comments;
	}

	/*
	 * Public: Get user comment popularity
	 *
	 * Returns percentage
	 */
	public function getCommentPopularity() {
		$total = $this->getLikes() + $this->getDislikes();
		if($total) {
			$popularity = 100 * ($this->getLikes() 
							/ ($this->getLikes() + $this->getDislikes()));
			return round($popularity);
		} else {
			return false;
		}
	}

	/*
	 * Public: Get likes
	 * Get number of likes on comments by user
	 */
	public function getLikes() {
		if(!$this->likes) {
			$sql = $this->safesql->query(
				"SELECT 
					SUM(likes) 
				FROM `comment` 
				WHERE user='%s'
				AND `active`=1",
				array(
					$this->getUser(),
				));
			$this->likes = $this->db->get_var($sql);
		}
		return $this->likes;
	}

	/*
	 * Public: Get dislikes
	 * Get number of dislikes on comments by user
	 */
	public function getDislikes() {
		if(!$this->dislikes) {
			$sql = $this->safesql->query(
				"SELECT 
					SUM(dislikes) 
				FROM `comment` 
				WHERE user='%s'
				AND `active`=1",
				array(
					$this->getUser(),
				));
			$this->dislikes = $this->db->get_var($sql);
		}
		return $this->dislikes;
	}

	/*
	 * Public: Get number of pages in a category
	 *
	 * Returns int 
	 */
	public function getNumPages() {
		if(!$this->count) {
			$sql = $this->safesql->query(
				"SELECT 
					COUNT(id) as count 
				FROM `article` 
				INNER JOIN `article_author` 
					ON (article.id=article_author.article) 
				WHERE article_author.author='%s'
				AND published < NOW()
				ORDER BY article.date DESC",
				array(
					$this->getUser()
				));
			$this->count = $this->db->get_var($sql);
		}

		$pages = ceil(($this->count - ARTICLES_PER_USER_PAGE) / (ARTICLES_PER_USER_PAGE)) + 1;
		return $pages;
	}

	/*
	 * Public: Get first name of user
	 *
	 * Returns string
	 */
	public function getFirstName() {
		$name = explode(' ', $this->getName());
		return $name[0];
	}

	/*
	 * Public: Get last name of user
	 *
	 * Returns string
	 */
	public function getLastName() {
		$name = explode(' ', $this->getName());
		return $name[1];
	}

	/*
	 * Public: Get email
	 * If user has defined an email the use that. Else use ldap email
	 * $force - [boolean] if true then always return an email address (if not defined in database then from ldap)
	 *
	 * Returns string
	 */
	public function getEmail($force = false) {
		if($force == true) { // if forcing email
			if(!$this->fields['email']) {
				return ldap_get_mail($this->getUser());
			}
			return $this->fields['email'];
		}
		return $this->fields['email'];
	}

	/*
	 * Public: Get user info
	 * Decode json array of info
	 *
	 * Returns array
	 */
	public function getInfo() {
		return json_decode($this->fields['info']);
	}

	public function getFirstLogin() {
		$sql = $this->safesql->query(
			"SELECT 
				UNIX_TIMESTAMP(timestamp) as timestamp 
			FROM `login` 
			WHERE user='%s' 
			ORDER BY timestamp ASC 
			LIMIT 1",
			array(
				$this->getUser()
			));
		$login = $this->db->get_var($sql);
		if($login) {
			return $login;
		} else {
			return 1262304000; // 1st of January 2010
		}
	}

	public function getLastLogin() {
		$sql = $this->safesql->query(
			"SELECT 
				UNIX_TIMESTAMP(timestamp) as timestamp 
			FROM `login` 
			WHERE user='%s' 
			ORDER BY timestamp DESC 
			LIMIT 1",
			array(
				$this->getUser()
			));
	$login = $this->db->get_var($sql);
		if($login) {
			return $login;
		} else {
			return 1262304000; // 1st of January 2010
		}
	}

	/*
	 * Public: Has personal info
	 * Check to see whether user has personal info
	 */
	public function hasPersonalInfo() {
		if($this->getDescription()
			|| $this->getFacebook()
			|| $this->getTwitter()
			|| $this->getEmail()
			|| $this->getWebsiteurl()
		) {
			return true;
		}
		return false;
	}

	/**
	 * Public: Get image
	 */
	public function getImage() {
		if (!$this->image) {
			if ($this->getImg()) {
				$this->image = new Image($this->getImg());
			}
		}
		return $this->image;
	}
}
