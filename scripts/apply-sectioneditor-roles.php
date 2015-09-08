<?php
/**
 * Give all section editors an Editor role
 */

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\CategoryAuthor', 'category_author');

$values = $manager->values(true);

if(!$values) {
	echo "Nothing to do.\n";
	return;
}

$roleM = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Role', 'roles');
$roleM->filter('name = "sectionEditor"');
$roleV = $roleM->one();

$roleId = $roleV->getId();

echo "I am assuming the sectionEditor role is ".$roleId.".\n";

// Delete old roles
$uRoleM = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\UserRole', 'user_roles');
$uRoleM->filter('role = "'.$roleId.'"');
$uRoles = $uRoleM->values();

if($uRoles) {
	foreach($uRoles as $uRole) {
		$uRole->delete(); // Remove old sectionEditor role
	}
}

$done = array();

foreach($values as $record) {
	if(array_search($record->getUser()->getUser(), $done) !== false) {
		continue;
	}

	$done[] = $record->getUser()->getUser();

	$roleMap = new \FelixOnline\Core\UserRole();
	$roleMap->setUser($record->getUser());
	$roleMap->setRole($roleV);
	$roleMap->save();
}

echo "All done.\n";
