<?php
/**
 * Give all article authors an Author role
 */

if(php_sapi_name() === 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\ArticleAuthor', 'article_author');

$values = $manager->values(true);

if(!$values) {
	echo "Nothing to do.\n";
	return;
}

$roleM = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Role', 'roles');
$roleM->filter('name = "author"');
$roleV = $roleM->one();

$roleId = $roleV->getId();

echo "I am assuming the author role is ".$roleId.".\n";

$done = array();

foreach($values as $record) {
	$toDo = false;

	if(array_search($record->getAuthor()->getUser(), $done) !== false) {
		continue;
	}

	$roles = $record->getAuthor()->getExplicitRoles();

	if(!$roles) {
		$toDo = true;
	} else {
		$hasRole = false;
		foreach($roles as $role) {
			if($role->getName() == 'author') {
				echo "Skipping ".$record->getAuthor()->getUser()." - has role\n";
				$hasRole = true;

				continue;
			}
		}

		if(!$hasRole) {
			$toDo = true;
		}
	}

	if($toDo) {
		$done[] = $record->getAuthor()->getUser();

		$roleMap = new \FelixOnline\Core\UserRole();
		$roleMap->setUser($record->getAuthor());
		$roleMap->setRole($roleV);
		$roleMap->save();
	}
}

echo "All done.\n";
