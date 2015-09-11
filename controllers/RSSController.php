<?php

use FelixOnline\Exceptions;
	
class RSSController extends BaseController {
	function GET($matches) {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		$articleManager = new \FelixOnline\Core\ArticleManager();

		// RSS feed for category
		if (array_key_exists('cat', $matches)) {
			try {
				$category = (new \FelixOnline\Core\CategoryManager())
					->filter('cat = "%s"', array($matches['cat']))
					->filter('active = 1');

				if(!$currentuser->isLoggedIn()) {
					$category->filter('secret = 0');
				}

				$category = $category->one();
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

			$name = $category->getLabel().' - '.\FelixOnline\Core\Settings::get('rss_name');

			$articleManager->filter('published IS NOT NULL')
				->filter('published < NOW()')
				->filter('category = %i', array($category->getId()))
				->limit(0, \FelixOnline\Core\Settings::get('rss_articles'))
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

			$name = $user->getName().' - '.\FelixOnline\Core\Settings::get('rss_name');

			$articleManager = (new \FelixOnline\Core\ArticleManager())
				->filter('published < NOW()')
				->order('date', 'DESC');

			$authorManager = (new \FelixOnline\Core\ArticleAuthorManager())
				->filter("author = '%s'", array($user->getUser()));
			$articleManager->join($authorManager);

			$categoryManager = (new \FelixOnline\Core\CategoryManager())
				->filter("active = 1");

			if(!$currentuser->isLoggedIn()) {
				$categoryManager->filter('secret = 0');
			}

			$articleManager->join($categoryManager, null, 'category');

			$articleManager->limit(0, \FelixOnline\Core\Settings::get('rss_articles'));

		} else {
			$articleManager->filter('published IS NOT NULL')
				->filter('published < NOW()')
				->limit(0, \FelixOnline\Core\Settings::get('rss_articles'))
				->order('published', 'DESC');

			$categoryManager = (new \FelixOnline\Core\CategoryManager())
				->filter("active = 1");

			if(!$currentuser->isLoggedIn()) {
				$categoryManager->filter('secret = 0');
			}

			$articleManager->join($categoryManager, null, 'category');

			$author = "felix@imperial.ac.uk (Felix)";

			$name = \FelixOnline\Core\Settings::get('rss_name');
		}

		$articles = $articleManager->values();

		$newsfeed = new RSSFeed();
		$newsfeed->SetChannel(
			'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],
			$name,
			\FelixOnline\Core\Settings::get('rss_description'),
			'en-gb',
			\FelixOnline\Core\Settings::get('rss_copyright'),
			$author
		);
		$newsfeed->SetImage(\FelixOnline\Core\Settings::get('image_url').'/800/600/'.\FelixOnline\Core\Settings::get('rss_img'));

		if(count($articles) > 0) {
			foreach($articles as $article) {
				$converter = new \Sioen\Converter();

				$text = $converter->toHTML($article->getContent());
				$text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $text); // Some <p>^B</p> tags can get through some times. Should not happen with the current migration script

				// More text tidying
				$text = strip_tags($text);

				$newsfeed->setItem(
					$article->getURL(),
					str_replace(array(' & ', '&'), array(' and ', 'and'), html_entity_decode($article->getTitle())),
					str_replace(array(' & ', '&'), array(' and ', 'and'), html_entity_decode(substr($text, 0, 600))) . '...',
					date("r", $article->getPublished())
				);
			}
		}

		header("Content-Type: application/rss+xml; charset=utf-8");
		echo $newsfeed->output();
	}
}
