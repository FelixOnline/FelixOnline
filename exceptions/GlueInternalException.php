<?php

// Glue exceptions - our fault
class GlueInternalException extends GlueURLException {
	protected $class;
	protected $method;
	
	public function __construct($message, $url, $class, $method, $code = parent::EXCEPTION_GLUE, Exception $previous = null) {
		$this->class = $class;
		$this->method = $method;
		parent::__construct($message, $url, $code, $previous);
	}
	
	public function getClass() {
		return $this->class;
	}
	
	public function getMethod() {
		return $this->method;
	}
}