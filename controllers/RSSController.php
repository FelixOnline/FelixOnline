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

			$author = $category->getEmail().' (Felix '.$category->getLabel().')';

			$name = $category->getLabel().' - '.RSS_NAME;

			$articleManager->filter('published IS NOT NULL')
				->filter('published < NOW()')
				->filter('category = %i', array($category->getId()))
				->limit(0, RSS_ARTICLES)
				->order('published', 'DESC');

		} elseif (array_key_exists('user', $matches)) {
			try {
				$user = new \FelixOnline\Core\User($matches['user']);
			} catch (Exceptions\InternalException $e) {
				throw new Exceptions\NotFoundException(
					$e->getMessage(),
					$matches,
					'RSSController',
					null,
					$e
				);
			}

			if($user->getShowEmail()) {
				$author = $user->getEmail().' ('.$user->getName().')';
			} else {
				$author = '('.$user->getName().')';
			}

			$name = $user->getName().' - '.RSS_NAME;

			$articleManager = (new \FelixOnline\Core\ArticleManager())
				->filter('published < NOW()')
				->order('date', 'DESC');

			$authorManager = (new \FelixOnline\Core\ArticleAuthorManager())
				->filter("author = '%s'", array($user->getUser()));
			$articleManager->join($authorManager);

			$articleManager->limit(0, RSS_ARTICLES);

		} else {
			$articleManager->filter('published IS NOT NULL')
				->filter('published < NOW()')
				->limit(0, RSS_ARTICLES)
				->order('published', 'DESC');

			$author = "felix@imperial.ac.uk (Felix)";

			$name = RSS_NAME;
		}

		$articles = $articleManager->values();

		$newsfeed = new RSSFeed();
		$newsfeed->SetChannel(
			'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],
			$name,
			RSS_DESCRIPTION,
			'en-gb',
			RSS_COPYRIGHT,
			$author
		);
		$newsfeed->SetImage(RSS_IMG);

		if(count($articles) > 0) {
			foreach($articles as $article) {
				$newsfeed->setItem(
					$article->getURL(),
					str_replace(array(' & ', '&'), array(' and ', 'and'), html_entity_decode($article->getTitle())),
					str_replace(array(' & ', '&'), array(' and ', 'and'), html_entity_decode($article->getShortDesc(600))) . '...',
					date("r", $article->getPublished())
				);
			}
		}

		header("Content-Type: application/rss+xml; charset=utf-8");
		echo $newsfeed->output();
	}
}
