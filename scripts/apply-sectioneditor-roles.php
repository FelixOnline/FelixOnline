<?php
/**
 * Give all section editors an Editor role
 */

if(php_sapi_name() !== 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\CategoryAuthor', 'category_author');

$values = $manager->values(true);

if(!$values) {
	echo "Nothing to do.\n";
	exit(1);
	return;
}

$roleM = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Role', 'roles');
$roleM->filter('name = "sectionEditor"');
$roleV = $roleM->one();

$roleId = $roleV->getId();

echo "I am assuming the sectionEditor role is ".$roleId.".\n";

$done = array();

foreach($values as $record) {
	$toDo = false;

	if(array_search($record->getUser()->getUser(), $done) !== false) {
		continue;
	}

	$roles = $record->getUser()->getExplicitRoles();

	if(!$roles) {
		$toDo = true;
	} else {
		$hasRole = false;
		foreach($roles as $role) {
			if($role->getName() == 'sectionEditor') {
				echo "Skipping ".$record->getUser()->getUser()." - has role\n";
				$hasRole = true;

				continue;
			}
		}

		if(!$hasRole) {
			$toDo = true;
		}
	}

	if($toDo) {
		echo "Adding ".$record->getUser()->getUser()."\n";

		$roleMap = new \FelixOnline\Core\UserRole();
		$roleMap->setUser($record->getUser());
		$roleMap->setRole($roleV);
		$roleMap->save();
	}

	$done[] = $record->getUser()->getUser();
}

// Now delete old roles
$roleM = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\UserRole', 'user_roles');
$roleM->filter('role = '.$roleId);
$values = $roleM->values(true);

foreach($values as $value) {
	if(array_search($value->getUser()->getUser(), $done) === FALSE) {
		echo $value->getUser()->getUser()." is no longer a section editor - removing role\n";
		$value->delete();
	}
}

echo "All done.\n";
exit(0);