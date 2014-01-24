<?php

class UserController extends BaseController {
	function GET($matches) {
		$user = new User($matches['user']);
		if(!$matches['page']) {
			$pagenum = 1;
		} else {
			$pagenum = $matches['page'];
		}
		$articles = $user->getArticles($pagenum);

		if (is_null($articles)) {
			$articles = array();
		}

		$comments = $user->getComments();

		$this->theme->appendData(array(
			'user' => $user,
			'pagenum' => $pagenum,
			'articles' => $articles,
			'comments' => $comments
		));
		$this->theme->setHierarchy(array(
			$user->getUser() // user-{user}.php
		));
		$this->theme->render('user');
	}
}
