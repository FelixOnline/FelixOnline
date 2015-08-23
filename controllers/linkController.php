<?php
/*
 * Link Controller
 * for URL redirections
 */
use \FelixOnline\Exceptions;
use \FelixOnline\Core\Link;

class LinkController extends BaseController {
	function GET($matches) {
		try {
			$link = new Link($matches['link']);

			if(!$link->getActive()) {
				throw new Exceptions\InternalException('This link is not currently active');
			}
		} catch (Exceptions\InternalException $e) {
			throw new NotFoundException(
				$e->getMessage(),
				$matches,
				'LinkController',
				null,
				$e
			);
		}

		// Send redirection header
		header('Location: '.$link->getUrl());

		// Do not do anything else
		exit;
	}
}
