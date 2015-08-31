<?php
/*
 * Link Controller
 * for URL redirections
 */
use \FelixOnline\Exceptions;
use \FelixOnline\Core\Advert;

class AdvertController extends BaseController {
	function GET($matches) {
		try {
			$advert = new Advert($matches['advert']);

			if(!$advert->getActive()) {
				throw new Exceptions\InternalException('This advert is not currently active');
			}
		} catch (Exceptions\InternalException $e) {
			throw new NotFoundException(
				$e->getMessage(),
				$matches,
				'AdvertController',
				null,
				$e
			);
		}

		$advert->clickAdvert();

		// Send redirection header
		header('Location: '.$advert->getUrl());

		// Do not do anything else
		exit;
	}
}
