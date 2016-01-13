<?php

use FelixOnline\Exceptions;

class ValidationController extends BaseController
{
	function GET($matches)
	{
		try {
			$code = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\EmailValidation', 'email_validation')
				->filter('code = "%s"', array($matches['code']))
				->filter('confirmed = 0')
				->one();
		} catch (Exceptions\InternalException $e) {
			throw new NotFoundException(
				$e->getMessage(),
				$matches,
				'ValidationController',
				FrontendException::EXCEPTION_NOTFOUND,
				$e
			);
		}

		$code->setConfirmed(1)->save();

		try {
			$comments = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Comment', 'comment')
				->filter('email = "%s"', array($code->getEmail()))
				->filter('active = 1')
				->filter('spam = 0')
				->values();

			foreach($comments as $comment) {
				if ($comment->getReply()) { // if comment is replying to an internal comment 
					$comment->emailReply();
				}

				// email authors of article
				$comment->emailAuthors();
			}
		} catch (\Exception $e) {

		}

		$this->theme->appendData(array(
			'email' => $code->getEmail()
		));

		$this->theme->render('validation');
	}
}
