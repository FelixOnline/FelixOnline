<?php

use FelixOnline\Core;

class Utility extends Core\Utility {
	static function getResponsibilities(Core\User $user) {
		try {
			$categories = $user->getCategories();
		} catch(\Exception $e) {
			throw $e;
			$categories = array();
		}

		if(count($categories) == 0) {
			return 'Contributor';
		}

		// change array into linked categories
		foreach ($categories as $key => $cat) {
			$full_array[$key] = $cat->getLabel();
		}
		// get last element
		$last = array_pop($full_array);
		// if it was the only element - return it
		if (!count ($full_array))
			return $last.' Editor';
		$output = implode (', ', $full_array);
		if($bold) {
			$output .= ' and ';	
		} else {
			$output .= ' and ';
		} 
		$output .= $last;

		$output .= ' Editors';

		return $output;
	}
}