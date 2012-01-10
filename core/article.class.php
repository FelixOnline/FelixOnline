<?php
/*
 * Article class
 * Deals with both article retrieval and article submission
 *
 * Fields:
 *      id              - id of article 
 *      title           - title of article 
 *      short_title     - short title of article for boxes on front page [optional]
 *      teaser          - article teaser 
 *      author          - first author of article, superseded by article_author table [depreciated] 
 *      category        - id of category article is in
 *      date            - timestamp when article was added to site
 *      approvedby      - user who approved the article to be published
 *      published       - timestamp when article was published
 *      hidden          - if article is hidden from engine
 *      text1           - id of main article text
 *      img1            - id of main article image
 *      text2           - id of second article text [depreciated]
 *      img2            - id of second image text [depreciated]
 *      img2lr          - not quite sure [TODO]
 *      hits            - number of views the article has had
 *      short_desc      - short description of article for boxes on front page [optional]
 */
class Article extends BaseModel {
	private $authors; // array of authors of article 
    private $category_cat; // category cat (short version)
    private $category_label; // category label
    private $content; // article content
    private $image; // image class
    private $image_title; // image title
    private $num_comments; // number of comments
	private $search = array('@<>@',
        '@<script[^>]*?>.*?</script>@siU',  // javascript
        '@<style[^>]*?>.*?</style>@siU',    // style tags
        '@<embed[^>]*?>.*?</embed>@siU',    // embed
        '@<object[^>]*?>.*?</object>@siU',    // object
        '@<iframe[^>]*?>.*?</iframe>@siU',    // iframe      
        '@<![\s\S]*?--[ \t\n\r]*>@',        // multi-line comments including CDATA
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
            $sql = "SELECT `id`,`title`,`short_title`,`teaser`,`author`,`approvedby`,`category`,UNIX_TIMESTAMP(`date`) as date,UNIX_TIMESTAMP(`published`) as published,`hidden`,`text1`,`text2`,`img1`,`img2`,`img2lr`,`hits` FROM `article` WHERE id=".$id;
            parent::__construct($this->db->get_row($sql));
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
        $sql = "SELECT article_author.author as author FROM `article_author` INNER JOIN `article` ON (article_author.article=article.id) WHERE article.id=".$this->getId();
        $authors = $this->db->get_results($sql);
        foreach($authors as $author) {
            $this->authors[] = $author->author;
        }
        return $this->authors; 
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
        foreach ($array as $key => $value) {
            $full_array[$key] = '<a href="user/'.$value.'/">'.get_vname_by_uname_db($value).'</a>';
        }
        // get last element
        $last = array_pop ($full_array);
        // if it was the only element - return it
        if (!count ($full_array))
            return $last;
        return implode (', ', $full_array).' and '.$last;
    }

    /*
     * Public: Get cat of article category
     */
    public function getCategoryCat() {
        if(!$this->category_cat) {
            $sql = "SELECT `cat` FROM `category` WHERE id = ".$this->getCategory();
            $this->category_cat = $this->db->get_var($sql);
        }
        return $this->category_cat;
    }

    /*
     * Public: Get label of article category
     */
    public function getCategoryLabel() {
        if(!$this->category_label) {
            $sql = "SELECT `label` FROM `category` WHERE id = ".$this->getCategory();
            $this->category_label = $this->db->get_var($sql);
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
        return $this->content;
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
     *
     * $limit - word limit
     */
    public function getPreview($limit) {
        $string = strip_tags($this->getContent());
        $words = explode(" ",$string);
        if(count($words) > $limit) {
          $append = ' ... <br/><a href="'.$this->getURL().'" title="Read more" id="readmorelink">Read more</a>';
        }
        return implode(" ",array_splice($words,0,$limit)) . $append;
    }

    /*
     * Public: Get short description
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
        if(!$this->num_comments) {
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
        }
        return $this->num_comments;
    }

    /*
     * Public: Get image class
     */
    public function getImage() {
        if(!$this->image) {
            $this->image = new Image($this->getImg1());
        }
        return $this->image;
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
        $title = strtolower($this->getTitle()); // Make title lowercase
        $title= preg_replace('/[^\w\d_ -]/si', '', $title); // Remove special characters
        $dashed = str_replace( " ", "-", $title); // Replace spaces with hypens
        $output = $cat.'/'.$this->getId().'/'.$dashed.'/'; // output: CAT/ID/TITLE/
        return $output;
    }

    /*
     * Public: Log article visit and increment hit count on article
     */
    public function logArticleVisit() {
        $this->hitArticle();
        $this->logVisitor(); 
    }

    /*
     * Private: Increment hit count on article
     */
    private function hitArticle() {
        $sql = "UPDATE `article` SET hits=hits+1 WHERE id=".$this->id;
        return $this->db->query($sql);
    }

    /*
     * Private: Add log of visitor into article_vist table
     */
    private function logVisitor() {
        $sql = "INSERT INTO article_visit (article,user,IP,referrer) VALUES ('$this->id',";
        $sql .= ($u = is_logged_in()) ? "'$u'" : "NULL";
        $sql .= ",'".$_SERVER['REMOTE_ADDR']."'";
        $sql .= ",'".$_SERVER['HTTP_REFERER']."'";
        $sql .= ")";
        return $this->db->query($sql);
    }

	public function print_this() {
		print_r($this);
	}
}

?>
