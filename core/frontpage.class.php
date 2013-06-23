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
    protected $layout;
    protected $sections = array();

    /**
     * Constructor
     *
     * @param integer $layout - layout id [default: 1]
     */
    function __construct($layout = 1)
    {
        global $db;
        $this->db = $db;
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
            $sql = "SELECT
                    `1` AS one,
                    `2` AS two,
                    `3` AS three,
                    `4` AS four,
                    `5` AS five,
                    `6` AS six,
                    `7` AS seven,
                    `8` AS eight
                FROM `frontpage`
                WHERE layout=".$this->layout."
                AND section='".$section."'";

            $list = $this->db->get_row($sql);

            $articles = array();
            foreach ($list as $key => $a) {
                if ($a != 0 && !is_null($a)) {
                    $articles[$key] = new Article($a);
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
        $sql = "SELECT id FROM `article`
            WHERE author='felix'
            AND category='2'
            AND text1 IS NOT NULL
            ORDER BY date DESC
            LIMIT 1";
        $id = $this->db->get_var($sql);
        return new Article($id);
    }

    /**
     * Get featured categories
     *
     * @return array of featured categories
     */
    public function getFeaturedCategories()
    {
        $sql = "
            SELECT
                id,
                cat,
                label,
                top_slider_1 as top
            FROM `category`
            WHERE active = 1
            AND hidden = 0
            AND id > 0
            AND `order` > 0
            ORDER BY `order` ASC";
        $cats = $this->db->get_results($sql);
        return $cats;
    }
}

