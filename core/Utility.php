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

	static function tidyText($text) {
		$converter = new \Sioen\Converter();

		$text = $converter->toHTML($text);
		$text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $text); // Some <p>^B</p> tags can get through some times. Should not happen with the current migration script

		// More text tidying
		$text = strip_tags($text, '<p><a><div><b><i><br><blockquote><object><param><embed><li><ul><ol><strong><img><h1><h2><h3><h4><h5><h6><em><iframe><strike>'); // Gets rid of html tags except <p><a><div>
		$text = preg_replace('/(<br(| |\/|( \/))>)/i', '', $text); // strip br tag
		$text = preg_replace('#<div[^>]*(?:/>|>(?:\s|&nbsp;)*</div>)#im', '', $text); // Removes empty html div tags
		$text = preg_replace('#<span*(?:/>|>(?:\s|&nbsp;)[^>]*</span>)#im', '', $text); // Removes empty html span tags
		$text = preg_replace('#<p[^>]*(?:/>|>(?:\s|&nbsp;)*</p>)#im', '', $text); // Removes empty html p tags
		$text = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $text); // Remove style attributes

		return $text;
	}
}