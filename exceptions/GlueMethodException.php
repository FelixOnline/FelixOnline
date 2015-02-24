<?php

// Glue exceptions - our fault (specifically, method is wrong)
class GlueMethodException extends GlueInternalException {
	protected $class;
	protected $method;
	
	public function __construct($message, $url, $class, $method, $code = parent::EXCEPTION_GLUE_METHOD, Exception $previous = null) {
		$this->class = $class;
		$this->method = $method;
		parent::__construct($message, $url, $code, $previous);
	}
}