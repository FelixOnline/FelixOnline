<?php
/*
 * Frontpage class
 * Represents the frontpage
 *
 * Fields:
 */
class Frontpage extends BaseModel
{
	protected $db;
	protected $safesql;
	protected $layout;
	protected $sections = array();

	/**
	 * Constructor
	 *
	 * @param integer $layout - layout id [default: 1]
	 */
	function __construct($layout = 1)
	{
		global $db, $safesql;
		$this->db = $db;
		$this->safesql = $safesql;
		$this->layout = $layout;
	}

	/**
	 * Get section
	 *
	 * @param string $section - section name
	 *
	 * @return array of articles
	 */
	public function getSection($section)
	{
		if (!array_key_exists($section, $this->sections)) {
			$sql = $this->safesql->query(
				"SELECT
					`1` AS one,
					`2` AS two,
					`3` AS three,
					`4` AS four,
					`5` AS five,
					`6` AS six,
					`7` AS seven,
					`8` AS eight
				FROM `frontpage`
				WHERE layout=%i
				AND section='%s'",
				array(
					$this->layout,
					$section,
				));

			$list = $this->db->get_row($sql);

			$articles = array();
			foreach ($list as $key => $a) {
				if ($a != 0 && !is_null($a)) {
					$articles[$key] = new \FelixOnline\Core\Article($a);
				}
			}

			$this->sections[$section] = $articles;
		}
		return $this->sections[$section];
	}

	/**
	 * Get editorial
	 *
	 * @return Article editorial
	 */
	public function getEditorial()
	{
		$articles = (new \FelixOnline\Core\ArticleManager())
			->filter("author = 'felix'") // TODO need to use article_author coloumn
			->filter('category = 2')
			->filter('text1 IS NOT NULL')
			->order('date', 'DESC')
			->limit(0, 1)
			->values();

		if (is_null($articles)) {
			throw new InternalException('Cannot find editorial');
		}

		return $articles[0];
	}

	/**
	 * Get featured categories
	 *
	 * @return array of featured categories
	 */
	public function getFeaturedCategories()
	{
		$sql = $this->safesql->query(
			"SELECT
				id,
				cat,
				label,
				top_slider_1 as top
			FROM `category`
			WHERE active = 1
			AND hidden = 0
			AND id > 0
			AND `order` > 0
			ORDER BY `order` ASC", array());
		$cats = $this->db->get_results($sql);
		return $cats;
	}
}

