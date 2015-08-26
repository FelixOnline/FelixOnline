<?php

use FelixOnline\Exceptions;

class ValidationController extends BaseController
{
	function GET($matches)
	{
		global $currentuser;
		
		try {
			$code = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\EmailValidation', 'email_validation')
				->filter('code = "%s"', array($matches['code']))
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

		$this->theme->appendData(array(
			'email' => $code->getEmail()
		));

		$this->theme->render('validation');
	}
}
