<?php
/*
 * Blog Class
 *
 * Fields:
 *	  id:		 - id of page
 *	  name:	   - name of blog
 *	  slug:	   - url slug of page
 *	  controller: - name of controller used to handle blog
 *	  sticky:	 -
 */
class Blog extends BaseModel {
	protected $db;
	protected $posts;
	protected $safesql;

	function __construct($slug=NULL) {
		global $db;
		global $safesql;
		$this->db = $db;
		$this->safesql = $safesql;

		if($slug !== NULL) {
			$sql = $this->safesql->query("SELECT
											`id`,
											`name`,
											`slug`,
											`controller`,
											`sticky`
										FROM `blogs`
										WHERE slug='%s'", array($slug));
			parent::__construct($this->db->get_row($sql), 'Blog', $slug);
			return $this;
		} else {
			// initialise new blog
		}
	}

	/*
	 * Get posts
	 *
	 * $page - page number to get posts from [optional]
	 *
	 * returns array of BlogPost objects
	 */
	public function getPosts($page = NULL) {
		if(!$this->posts) {
			$sql = "
				SELECT
					id
				FROM `blog_post`
				WHERE blog = %i
				ORDER BY timestamp DESC";
			if($page) {
				$sql .= "LIMIT %i,%i;";
				$sql = $this->safesql->query($sql, array($this->getId(), (($page-1)*BLOG_POSTS_PER_PAGE), BLOG_POSTS_PER_PAGE));
			} else {
				$sql = $this->safesql->query($sql, array($this->getId()));
			}

			$posts = $this->db->get_results($sql);
			foreach($posts as $object) {
				$this->posts[] = new BlogPost($object->id);
			}
		}
		return $this->posts;
	}
}

