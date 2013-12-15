<?php
	
class RSSController extends BaseController {
	function GET($matches) {
		$articleManager = new ArticleManager();

		// RSS feed for category
		if (array_key_exists('cat', $matches)) {
			$category = new Category($matches['cat']);

			$articles = $articleManager->filter(
				array(
					'published IS NOT NULL',
					'published < NOW()',
					'category = ' . $category->getId(),
				),
				RSS_ARTICLES,
				array('published', 'DESC')
			);

		} else {
			$articles = $articleManager->filter(
				array(
					'published IS NOT NULL',
					'published < NOW()',
				),
				RSS_ARTICLES,
				array('published', 'DESC')
			);
		}

		$newsfeed = new RSSFeed();
		$newsfeed->SetChannel(
			'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],
			RSS_NAME,
			RSS_DESCRIPTION,
			'en-gb',
			RSS_COPYRIGHT,
			RSS_AUTHOR,
			RSS_SUBJECT
		);
		$newsfeed->SetImage(RSS_IMG);

		foreach($articles as $article) {
			$newsfeed->setItem(
				$article->getURL(),
				str_replace(array("&", "&8217;"), array("and", "'"), $article->getTitle()),
				str_replace(array("&", "&8217;"), array(" and ", "'"), $article->getShortDesc(600)) . '...',
				date("D, d M Y", $article->getDate())
			);
		}

		header("Content-Type: application/rss+xml; charset=utf-8");
		echo $newsfeed->output();
	}
}
