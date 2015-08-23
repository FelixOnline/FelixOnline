<?php

class GlueURLException extends NotFoundException {
	public function __construct($message, $url, $code = parent::EXCEPTION_GLUE_URL, Exception $previous = null) {
		parent::__construct($message, $url, $code, $previous);
	}
}