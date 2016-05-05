<?php
/**
 * Approve email addresses for users and non spam, non rejected comments.
 */

if(php_sapi_name() !== 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

$values = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Comment', 'comment');
$values = $values->filter('spam = 0')->filter('active = 1')->values();

foreach($values as $record) {
	$toDo = false;

	if(!\FelixOnline\Core\EmailValidation::isEmailValidated($record->getEmail())) {
		echo $record->getEmail()."\n";
		$r = new \FelixOnline\Core\EmailValidation();
		$r->setEmail($record->getEmail())->setCode("")->setConfirmed(1)->save();
	}
}

$values = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\User', 'user', 'user');
$values = $values->values();

foreach($values as $record) {
	$toDo = false;

	if(!\FelixOnline\Core\EmailValidation::isEmailValidated($record->getEmail())) {
		echo $record->getEmail()."\n";
		$r = new \FelixOnline\Core\EmailValidation();
		$r->setEmail($record->getEmail())->setCode("")->setConfirmed(1)->save();
	}
}


echo "All done.\n";
exit(0);