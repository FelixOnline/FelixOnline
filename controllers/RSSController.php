<?php

use FelixOnline\Exceptions;
	
class RSSController extends BaseController {
	function GET($matches) {
		$articleManager = new \FelixOnline\Core\ArticleManager();

		// RSS feed for category
		if (array_key_exists('cat', $matches)) {
			try {
				$category = (new \FelixOnline\Core\CategoryManager())
					->filter('cat = "%s"', array($matches['cat']))
					->one();
			} catch (Exceptions\InternalException $e) {
				throw new Exceptions\NotFoundException(
					$e->getMessage(),
					$matches,
					'RSSController',
					null,
					$e
				);
			}

			$articleManager->filter('published IS NOT NULL')
				->filter('published < NOW()')
				->filter('category = %i', array($category->getId()))
				->limit(0, RSS_ARTICLES)
				->order('published', 'DESC');

		} else {
			$articleManager->filter('published IS NOT NULL')
				->filter('published < NOW()')
				->limit(0, RSS_ARTICLES)
				->order('published', 'DESC');
		}

		$articles = $articleManager->values();

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
				date("D, d M Y", $article->getPublished())
			);
		}

		header("Content-Type: application/rss+xml; charset=utf-8");
		echo $newsfeed->output();
	}
}
