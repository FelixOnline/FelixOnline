<?php

/*
 * Article class
 * Deals with both article retrieval and article submission
 */
class Article {
	private $id; // id of article
	private $title; // title of article
	private $short_title;
	private $teaser;
	private $author; // username of author
	private $approvedby;
	private $category; // category.id
    private $category_cat; // category cat (short version)
    private $category_label; // category label
	private $date; // article unix timestamp
	private $publishdate; // unix timestamp
    private $hidden; // if article is hidden or not
	private $text1; // text_story.id
	private $text2; // text_story.id (optional)
    private $image; // image id
    private $image_title; // image title
	private $img1; // image.id
	private $img2; // image.id (optional)
	private $img2lr; // img2 l/r [depreciated? TODO]
	private $hits; // never write to this
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
    private $db; // database object
	
    /*
     * Constructor for Article class
     * If initialised with id then store relevant data in object
     *
     * $id - ID of article (optional)
     *
     * Returns article object
     */
	function __construct($id=NULL) {
        /* initialise db connection and store it in object */
        global $db;
        $this->db = $db;
        $this->db->cache_queries = true;
        if($id !== NULL) { // if creating an already existing article object
            $this->id = $id;
            $sql = "SELECT `title`,`short_title`,`teaser`,`author`,`approvedby`,`category`,UNIX_TIMESTAMP(`date`) as date,UNIX_TIMESTAMP(`published`) as publishdate,`hidden`,`text1`,`text2`,`img1`,`img2`,`img2lr`,`hits` FROM `article` WHERE id=".$this->id;
            $article = $this->db->get_row($sql);
            foreach($article as $key => $value) { 
                $this->{$key} = $value; // store each value into object
            }
            return $this;
        } else {
            // initialise new article
        }
	}
	
    /*
     * Getter functions 
     */	
    public function getID()             { return $this->id; }
	public function getTitle()          { return $this->title; }
	public function getAuthor()         { return $this->author; }
	public function getApprovedby()     { return $this->approvedby; }
	public function getHits()           { return $this->hits; }
	public function getCategory()       { return $this->category; }

    /*
     * Public: Get label of article category
     */
    public function getCategoryCat() {
        if(!$this->category_cat) {
            $sql = "SELECT `cat` FROM `category` WHERE id = ".$this->category;
            $this->category_cat = $this->db->get_var($sql);
        }
        return $this->category_cat;
    }

    /*
     * Public: Get label of article category
     */
    public function getCategoryLabel() {
        if(!$this->category_label) {
            $sql = "SELECT `label` FROM `category` WHERE id = ".$this->category;
            $this->category_label = $this->db->get_var($sql);
        }
        return $this->category_label;
    }

    /*
     * Public: Get article submission date
     *
     * Returns UNIX_TIMESTAMP
     */
    public function getDate() { 
        return $this->date; 
    }
    
    /*
     * Public: Get publish date of article
     *
     * Returns UNIX_TIMESTAMP
     */    
	public function getPublishdate() {
		return $this->publishdate;
	}


	public function getImgID($img_id=1) {
		$var = img.$img_id;
		return $this->$var;
	}
	
	public function getImg2lr() {
		return $this->img2lr;
	}
	
	public function getPreview() {
		$content = preg_replace($this->search,'',$this->get_text(1));
		if (strlen($content) <= PREVIEW_LENGTH)
			return $content;
		else
			return substr($content,0,strrpos(substr($content,0,PREVIEW_LENGTH),' ')).'...';
	}
	
	public function getShortTitle() {
		return $this->short_title;
	}
	
    /*
     * Public: Get article teaser
     *
     * Returns string
     */
	public function getTeaser() {
		if ($this->teaser) {
            return str_replace('<br/>','',strip_tags($this->teaser));
			//return str_replace('<br/>','',preg_replace($this->search,'',$this->teaser));
        } else {
			$text = $this->getText(1);
			return trim(substr(strip_tags($text),0,strrpos(substr(strip_tags($text),0,TEASER_LENGTH),' '))).'...';
		}
	}

	public function getText($text_id=1) {
		global $cid;
		$var = text.$text_id;
		$sql = "SELECT content FROM `article` INNER JOIN `text_story` ON (article.text$text_id=text_story.id) WHERE article.id=$this->id";
		$rsc = mysql_query($sql,$cid);
		list($content) = mysql_fetch_array($rsc);
		return $content;
	}
	
	public function getTextID($text_id=1) {
		$var = text.$text_id;
		return $this->$var;
	}
	

    /*
     * Public: Get number of comments on article
     *
     * Returns int
     */
    public function getNumComments() {
        if(!$this->num_comments) {
            $sql = "SELECT SUM(count) AS count FROM (SELECT article,COUNT(*) AS count FROM `comment` WHERE article=".$this->id." AND `active`=1 GROUP BY article UNION ALL SELECT article,COUNT(*) AS count FROM `comment_ext` WHERE article=$id AND `active`=1 AND `pending`=0 GROUP BY article) AS t GROUP BY article";
            $this->num_comments = $this->db->get_var($sql);
        }
        return $this->num_comments;
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
        $title = strtolower($this->title); // Make title lowercase
        $title= preg_replace('/[^\w\d_ -]/si', '', $title); // Remove special characters
        $dashed = str_replace( " ", "-", $title); // Replace spaces with hypens
        $output = $cat.'/'.$this->id.'/'.$dashed.'/'; // output: CAT/ID/TITLE/
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
