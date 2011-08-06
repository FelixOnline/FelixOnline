<?php

header("Content-Type: application/rss+xml; charset=utf-8");

require_once('inc/common.inc.php');

// constants in inc/const.inc.php

$rss_name = RSS_NAME;

if (isset($_GET['cat'])) {
	$cat = $_GET['cat'];
	$catid = get_category_id_by_cat($cat);
	$catname = get_category_label_by_cat($cat);
	$sql = "SELECT id,title,teaser,author,category,UNIX_TIMESTAMP(published) AS date FROM `article` WHERE (published IS NOT NULL AND published < NOW()) AND category = '$catid' ORDER BY published DESC LIMIT ".RSS_ARTICLES;
	$rss_name .= ' - '.$catname;
} else if (isset($_GET['id'])) {
	$user = $_GET['id'];
	$name = get_vname_by_uname_db($user);
	$sql = "SELECT id,title,teaser,author,UNIX_TIMESTAMP(published) AS date FROM `article` WHERE (published IS NOT NULL AND published < NOW()) AND author = '$user' ORDER BY published DESC LIMIT ".RSS_ARTICLES;
	$rss_name .= ' - '.$name;
} else {
	$sql = "SELECT id,title,teaser,author,UNIX_TIMESTAMP(published) AS date FROM `article` WHERE published IS NOT NULL AND published < NOW() ORDER BY published DESC LIMIT ".RSS_ARTICLES;
}

$newsfeed = new RSSFeed();
$newsfeed->SetChannel('http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],$rss_name,RSS_DESCRIPTION,'en-gb',RSS_COPYRIGHT,RSS_AUTHOR,RSS_SUBJECT);
$newsfeed->SetImage(RSS_IMG);

$rsc = mysql_query($sql);
while ($article = mysql_fetch_array($rsc)) {

	if (!($teaser = $article['teaser']))
		$teaser = get_article_teaser($article['id']);

	//SetItem($url, $title, $description,$pubDate)
	$url = STANDARD_URL.'/'.article_url($article['id']);
	$title = str_replace(array("&8217;","&"),array("'",'&amp;'),$article['title']).($user?'':' - '.get_vname_by_uname_db($article['author']));
	
	//$content = substr(($teaser = $article['teaser']),0,120).((strlen($teaser)>120)?'...':'');
	$content = trunc_text(strip_tags(clean_content2(get_article_text($article['id']))), 150).' ...'; 
	//$content = clean_content2(get_article_text($article['id']));
	$description = str_replace( array("&","&8217;"),array(" and ","'"),$content);
	
	$date = date("D, d M Y",$article['date']);
	
	$newsfeed->setItem($url,$title,$description,$date);

}

echo $newsfeed->output();
?>