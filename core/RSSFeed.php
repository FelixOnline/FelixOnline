<?php

class RSSFeed {

	var $channel_url;
	var $channel_title;
	var $channel_description;
	var $channel_lang;
	var $channel_copyright;
	var $channel_date;
	var $channel_creator;
	var $channel_subject;   

	var $image_url;

	var $items = array();
	var $nritems;


	function RSSFeed() {
		$this->nritems=0;
		$this->channel_url='';
		$this->channel_title='';
		$this->channel_description='';
		$this->channel_lang='';
		$this->channel_copyright='';
		$this->channel_date='';
		$this->channel_creator='';
		$this->channel_subject='';
		$this->image_url='';
	}   

	function SetChannel($url, $title, $description, $lang, $copyright, $creator) {
		$this->channel_url=$url;
		$this->channel_title=$title;
		$this->channel_description=$description;
		$this->channel_lang=$lang;
		$this->channel_copyright=$copyright;
		$this->channel_date=date("r");
		$this->channel_creator=$creator;
	}

	function SetImage($url) {
		$this->image_url=$url;  
	}

	function SetItem($url, $title, $description,$pubDate) {
		$this->items[$this->nritems]['url']=$url;
		$this->items[$this->nritems]['title']=$title;
		$this->items[$this->nritems]['pubDate']=$pubDate;
		$this->items[$this->nritems]['description']=$description;
		$this->nritems++;   
	}

	function Output() {
		$output =  '<?xml version="1.0" encoding="utf-8"?>'."\n";
		$output .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'."\n";
		$output .= '<channel>'."\n";
		$output .= '<title>'.$this->channel_title.'</title>'."\n";
		$output .= '<atom:link href="'.$this->channel_url.'" rel="self" type="application/rss+xml" />'."\n";
		$output .= '<link>'.$this->channel_url.'</link>'."\n";
		$output .= '<description>'.$this->channel_description.'</description>'."\n";
		$output .= '<language>'.$this->channel_lang.'</language>'."\n";
		$output .= '<copyright>'.$this->channel_copyright.'</copyright>'."\n";
		$output .= '<pubDate>'.$this->channel_date.'</pubDate>'."\n";
		$output .= '<managingEditor>'.$this->channel_creator.'</managingEditor>'."\n";

		$output .= '<image>'."\n";
		$output .= '<url>'.$this->image_url.'</url>'."\n";
		$output .= '<title>'.$this->channel_title.'</title>'."\n";
		$output .= '<link>'.$this->channel_url.'</link>'."\n";
		$output .= '</image>'."\n";

		for($k=0; $k<$this->nritems; $k++) {
			$output .= '<item>'."\n";
			$output .= '<title>'.$this->items[$k]['title'].'</title>'."\n";
			$output .= '<link>'.$this->items[$k]['url'].'</link>'."\n";
			$output .= '<description>'.$this->items[$k]['description'].'</description>'."\n";
			$output .= '<guid>'.$this->items[$k]['url'].'</guid>'."\n";
			$output .= '<pubDate>'.$this->items[$k]['pubDate'].'</pubDate>'."\n";
			$output .= '</item>'."\n";  
		};

		$output .= '</channel>'."\n";
		$output .= '</rss>'."\n";
		return $output;
	}
};

?>