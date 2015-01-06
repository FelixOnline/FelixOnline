<?php

class ValidatorException extends FrontendException {
	protected $invaliddata;
	protected $csrf_failed;
	
	public function __construct($message, $invaliddata, $csrf_failed = false, $code = parent::EXCEPTION_VALIDATOR, Exception $previous = null) {
		$this->invaliddata = $invaliddata;
		$this->csrf_failed = $csrf_failed;
		
		$app = \FelixOnline\Core\App::getInstance();

		parent::__construct($message, $app['env']['PATH_INFO'], $code, $previous);
	}
	
	public function getData() {
		return $this->invaliddata;
	}
	
	public function hasCsrfFailed() {
		return $this->csrf_failed;
	}
}