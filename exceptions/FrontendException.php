<?php

use FelixOnline\Exceptions;

// Fundamental frontend exceptions
class FrontendException extends Exceptions\UniversalException {
	const EXCEPTION_FRONTEND = 150;
	const EXCEPTION_GLUE = 151;
	const EXCEPTION_GLUE_URL = 152;
	const EXCEPTION_GLUE_METHOD = 153;
	const EXCEPTION_VALIDATOR = 155;
	const EXCEPTION_NOTFOUND = 156;

	protected $url;

	public function __construct(
		$message,
		$url = '',
		$code = Exceptions\UniversalException::EXCEPTION_UNIVERSAL,
		\Exception $previous = null
	) {
		$this->url = $url;

		parent::__construct($message, $code, $previous);
	}
	
	public function getUrl() {
		return $this->url;
	}
}