<?php
/*
 * Page Class
 *
 * Fields:
 *	  id:		 - id of page
 *	  slug:	   - url slug of page
 *	  title:	  - title of page
 *	  content:	- content of page
 */
class Page extends BaseModel {
	protected $db;
	protected $safesql;

	function __construct($slug=NULL) {
		global $db, $safesql;
		$this->db = $db;
		$this->safesql = $safesql;
		if($slug !== NULL) {
			$sql = $this->safesql->query(
				"SELECT
					`id`,
					`slug`,
					`title`,
					`content`
				FROM `pages`
				WHERE slug='%s'",
				array(
					$slug,	
				));
			parent::__construct($this->db->get_row($sql), 'Page', $slug);
			return $this;
		} else {
			// initialise new page
		}
	}

	/*
	 * Private: Take string and eval any php
	 * Find any instances of php tags in string and replaces it with the evaluated php
	 */
	private function evalPHP($string) {
		ob_start();
		eval("?>$string<?php ");
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/*
	 * Public: Get page content
	 */
	public function getContent() {
		return $this->evalPHP($this->fields['content']);
	}

	/**
	 * Public: Get page slug
	 *
	 * @return string page slug
	 */
	public function getSlug() {
		return $this->fields['slug'];				
	}
}
