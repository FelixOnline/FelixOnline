<?php

class Article {

	private $id;
	private $title;
	private $short_title;
	private $teaser;
	private $author;
	private $approvedby;
	private $category_id; // category.id
	private $category_label; // category
	private $date; // article unix timestamp
	private $publishdate; // unix timestamp
	private $text1; // text_story.id
	private $text2; // text_story.id (optional)
	private $img1; // image.id
	private $img2; // image.id (optional)
	private $img2lr; // img2 l/r
	private $hits; // never write!
	private $search = array('@<>@',
		'@<script[^>]*?>.*?</script>@siU',  // javascript
		'@<style[^>]*?>.*?</style>@siU',    // style tags
		'@<embed[^>]*?>.*?</embed>@siU',    // embed
		'@<object[^>]*?>.*?</object>@siU',    // object
		'@<iframe[^>]*?>.*?</iframe>@siU',    // iframe      
		'@<![\s\S]*?--[ \t\n\r]*>@',        // multi-line comments including CDATA
		'@</?[^>]*>*@' 		  // html tags
	);
	
	function Article($id=NULL) {
		global $cid,$dbok;
		if (!$cid || !$dbok)
			die("Database error: ".mysql_error($cid));
		$this->id=$id;
		$sql = "SELECT `title`,`short_title`,`teaser`,`author`,`approvedby`,`category`,`cat`.`label`,UNIX_TIMESTAMP(`date`),UNIX_TIMESTAMP(`published`),`text1`,`text2`,`img1`,`img2`,`img2lr`,`hits` FROM `article` AS a INNER JOIN `category` AS cat ON (a.category=cat.id) WHERE a.id=$id";
		if ($id!==NULL && ($c=mysql_num_rows($rsc=mysql_query($sql,$cid))))
			list($this->title,$this->short_title,$this->teaser,$this->author,$this->approvedby,$this->category_id,$this->category_label,$this->date,$this->publishdate,$this->text1,$this->text2,$this->img1,$this->img2,$this->img2lr,$this->hits)
				= mysql_fetch_array($rsc);
		else {
			// initialise new article
		}
	}
	
	function print_this() {
		print_r($this);
	}
	
	function get_approvedby() {
		return $this->approvedby;
	}
	
	function get_author() {
		return $this->author;
	}
	
	function get_category_id() {
		return $this->category_id;
	}
	
	function get_category_label() {
		return $this->category_label;
	}
	
	function get_date() {
		return $this->date;
	}
	
	function get_hits() {
		return $this->hits;
	}
	
	function get_img_id($img_id=1) {
		$var = img.$img_id;
		return $this->$var;
	}
	
	function get_img2lr() {
		return $this->img2lr;
	}
	
	function get_preview() {
		$content = preg_replace($this->search,'',$this->get_text(1));
		if (strlen($content) <= PREVIEW_LENGTH)
			return $content;
		else
			return substr($content,0,strrpos(substr($content,0,PREVIEW_LENGTH),' ')).'...';
	}
	
	function get_publishdate() {
		return $this->publishdate;
	}
	
	function get_short_title() {
		return $this->short_title;
	}
	
	function get_teaser() {
		if ($this->teaser)
			return str_replace('<br/>','',preg_replace($this->search,'',$this->teaser));
		else {
			$text = $this->get_text(1);
			return trim(substr(strip_tags($text),0,strrpos(substr(strip_tags($text),0,TEASER_LENGTH),' '))).'...';
		}
	}
	
	function get_text($text_id=1) {
		global $cid;
		$var = text.$text_id;
		$sql = "SELECT content FROM `article` INNER JOIN `text_story` ON (article.text$text_id=text_story.id) WHERE article.id=$this->id";
		$rsc = mysql_query($sql,$cid);
		list($content) = mysql_fetch_array($rsc);
		return $content;
	}
	
	function get_text_id($text_id=1) {
		$var = text.$text_id;
		return $this->$var;
	}
	
	function get_title() {
		return $this->title;
	}

}

?>