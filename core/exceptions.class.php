<?php
// Generic
class InternalException extends Exception {
	protected $user;
	
	public function __construct($message, $code = 100, Exception $previous = null) {
		global $currentuser;
		$this->user = $currentuser;

		parent::__construct($message, $code, $previous);
	}
	
	public function getUser() {
		return $this->user;
	}
}

// For if a template does not exist
class ViewNotFoundException extends Exception {
	protected $view;
	protected $user;
	
	public function __construct($message, $view, $code = 101, Exception $previous = null) {
		global $currentuser;
		$this->view = $view;
		$this->user = $currentuser;

		parent::__construct($message, $code, $previous);
	}
	
	public function getView() {
		return $this->view;
	}
	
	public function getUser() {
		return $this->user;
	}
}

// For if a model does not exist in the database
class ModelNotFoundException extends InternalException {
	public function __construct($message, $code = 102, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}

// If the image doesn't exist - don't use this if using timthumb (see below)
class ImageNotFoundException extends Exception {
	protected $page;
	protected $image_url;
	protected $image_height;
	protected $image_width;
	protected $timthumb;
	protected $user;
	
	public function __construct($message, $page, $image_url, $image_height, $image_width, $timthumb = false, $code = 103, Exception $previous = null) {
		global $currentuser;
		$this->page = $page;
		$this->image_url = $image_url;
		$this->image_height = $image_height;
		$this->image_width = $image_width;
		$this->timthumb = $timthumb;
		$this->user = $currentuser;

		parent::__construct($message, $code, $previous);
	}
	
	public function getPage() {
		return $this->page;
	}
	
	public function getImageUrl() {
		return $this->image_url;
	}
	
	public function getImageDimensions() {
		return array('width' => $this->image_width, 'height' => $this->image_height);
	}
	
	public function isUsingTimthumb() {
		return $this->timthumb;
	}
	
	public function getUser() {
		return $this->user;
	}
}

// Use this instead for timthumb, mainly in case this stuff changes in the future
class TimthumbImageNotFoundException extends ImageNotFoundException {
	public function __construct($message, $page, $image_url, $image_height, $image_width, $timthumb = false, $code = 103, Exception $previous = null) {
		parent::__construct($message, $page, $image_url, $image_height, $image_width, true, $code, $previous);
	}
}

// If there is an error in the model (i.e. wrong verb)
class ModelConfigurationException extends Exception {
	protected $user;
	protected $verb;
	protected $property;
	
	public function __construct($message, $verb, $property, $code = 104, Exception $previous = null) {
		global $currentuser;
		$this->user = $currentuser;
		$this->verb = $verb;
		$this->property = $property;

		parent::__construct($message, $code, $previous);
	}
	
	public function getVerb() {
		return $this->verb;
	}
	
	public function getProperty() {
		return $this->property;
	}
	
	public function getUser() {
		return $this->user;
	}
}

// Base for Glue exceptions
class GlueException extends Exception {
	protected $user;
	protected $url;
	protected $class;
	protected $method;
	
	public function __construct($message, $url, $class = '', $method = '', $code = 105, Exception $previous = null) {
		global $currentuser;
		$this->user = $currentuser;
		$this->url = $url;
		$this->class = $class;
		$this->method = $method;

		parent::__construct($message, $code, $previous);
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function getClass() {
		return $this->class;
	}
	
	public function getMethod() {
		return $this->method;
	}
}

// Glue can't match URL
class GlueURLException extends GlueException {
	public function __construct($message, $url, $code = 107, Exception $previous = null) {
		parent::__construct($message, $url, null, null, $code, $previous);
	}
}

// The class referenced in the glue doesn't exist
class GlueClassNotFoundException extends GlueException {
	public function __construct($message, $url, $class, $method, $code = 108, Exception $previous = null) {
		parent::__construct($message, $url, $class, $method, $code, $previous);
	}
}

// The method called by the glue doesnt exist
class GlueMethodNotFoundException extends GlueException {
	public function __construct($message, $url, $class, $method, $code = 109, Exception $previous = null) {
		parent::__construct($message, $url, $class, $method, $code, $previous);
	}
}

class ErrorHandlerException extends Exception {
	protected $user;
	protected $params;
	
	public function __construct($message, $params, $code = 110, Exception $previous = null) {
		global $currentuser;
		$this->params = $params;
		$this->user = $currentuser;
		
		parent::__construct($message, $code, $previous);
	}
	
	public function getErrno() {
		return $this->params['errno'];
	}
	
	public function getFile() {
		return $this->params['file'];
	}
	
	public function getLine() {
		return $this->params['line'];
	}
	
	public function getContext() {
		return $this->params['context'];
	}
}

function errorhandler($errno, $errstr, $errfile, $errline, $errcontext) {
	throw new ErrorHandlerException($errstr, array('errno' => $errno, 'file' => $errfile, 'line' => $errline, 'context' => $errcontext));
	
	return null;
}

set_error_handler('errorhandler');

?>