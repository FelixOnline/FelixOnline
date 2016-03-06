<?php

use FelixOnline\Exceptions;

class TopicController extends BaseController
{
	public static function fetch($slug, $pagenum = 1) {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		$topic = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Topic', 'topic', 'slug')
				->filter('slug = "%s"', array($slug))
				->filter('disabled = 0')
				->one();

		if($pagenum == 1) {
			$counter = \FelixOnline\Core\Settings::get('articles_per_cat_page');
			$startat = 0;
		} else {
			$counter = \FelixOnline\Core\Settings::get('articles_per_second_cat_page');
			$startat = \FelixOnline\Core\Settings::get('articles_per_cat_page') + ($pagenum - 2) * \FelixOnline\Core\Settings::get('articles_per_second_cat_page');
		}

		$topicJoiner = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Topic', 'article_topic', 'topic')
			->filter('topic = "%s"', array($topic->getSlug()));

		$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Article', 'article', 'id')
			->join($topicJoiner, 'LEFT', 'id', 'article');

		if(!$currentuser->isLoggedIn()) {
			$catJoiner = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Category', 'category', 'id')
				->filter('secret = 0');

			$manager = $manager->join($catJoiner, 'LEFT', 'category', 'id');
		}

		$manager = $manager->filter('published < NOW()')
			->order(array('published', 'id'), 'DESC')
			->limit($startat, $counter);

		$count = $manager->count();
		$pages = ceil(($count - \FelixOnline\Core\Settings::get('articles_per_cat_page')) / (\FelixOnline\Core\Settings::get('articles_per_second_cat_page'))) + 1;

		$articles = $manager->values();
			
		if (is_null($articles)) {
			$articles = [];
		}

		return array('topic' => $topic,
			'pagenum' => $pagenum,
			'articles' => $articles,
			'pages' => $pages);
	}

	function GET($matches)
	{
		if(!isset($matches['slug'])) {
			$topics = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Topic', 'topic', 'slug');
			$topics = $topics->filter('hidden = 0')->all();

			$this->theme->render('topic_overview', array('topics' => $topics));

			return;
		}

		try {
			if(!isset($matches['page'])) {
				$pagenum = 1;
			} else {
				$pagenum = $matches['page'];
			}

			$data = self::fetch($matches['slug'], $pagenum);
		} catch (Exceptions\InternalException $e) {
			throw new NotFoundException(
				$e->getMessage(),
				$matches,
				'TopicController',
				FrontendException::EXCEPTION_NOTFOUND,
				$e
			);
		}

		$this->theme->appendData(array(
			'topic' => $data['topic'],
			'pagenum' => $data['pagenum'],
			'articles' => $data['articles'],
			'pages' => $data['pages'],
		));

		$this->theme->setHierarchy(array(
			$data['topic']->getSlug() // topic-{slug}.php
		));

		if($pagenum == 1) {
			$this->theme->render('topic_page1');
		} else {
			$this->theme->render('topic');
		}
	}
}
