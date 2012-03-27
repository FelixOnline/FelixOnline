<?php
define('EXCEPTION_UNIVERSAL', 100);
define('EXCEPTION_INTERNAL', 101);
define('EXCEPTION_NOTFOUND', 102);
define('EXCEPTION_IMAGE_NOTFOUND', 103);
define('EXCEPTION_VIEW_NOTFOUND', 104);
define('EXCEPTION_MODEL_NOTFOUND', 105);
define('EXCEPTION_TIMTHUMB_NOTFOUND', 106);
define('EXCEPTION_MODEL', 107);
define('EXCEPTION_GLUE', 108);
define('EXCEPTION_GLUE_URL', 109);
define('EXCEPTION_GLUE_CLASS', 110);
define('EXCEPTION_GLUE_METHOD', 111);
define('EXCEPTION_ERRORHANDLER', 112);
define('EXCEPTION_VALIDATOR', 113);
define('EXCEPTION_LOGIN', 114);

define('LOGIN_EXCEPTION_CREDENTIALS', 50);
define('LOGIN_EXCEPTION_SESSION', 51);
define('LOGIN_EXCEPTION_OTHER', 52);

// Base of all exceptions
class UniversalException extends Exception {
	protected $user;
	
	public function __construct($message, $code = EXCEPTION_UNIVERSAL, Exception $previous = null) {
		global $currentuser;
		$this->user = $currentuser;

		parent::__construct($message, $code, $previous);
	}
	
	public function getUser() {
		return $this->user;
	}
}

// Generic - our fault
class InternalException extends UniversalException {
	public function __construct($message, $code = EXCEPTION_INTERNAL, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}

// Generic - their fault
class NotFoundException extends UniversalException {
	public function __construct($message, $code = EXCEPTION_NOTFOUND, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}

// If the image doesn't exist - don't use this if using timthumb (see below)
class ImageNotFoundException extends UniversalException {
	protected $page;
	protected $image_url;
	protected $image_height;
	protected $image_width;
	protected $timthumb;
	
	public function __construct($message, $page, $image_url, $image_height, $image_width, $timthumb = false, $code = EXCEPTION_IMAGE_NOTFOUND, Exception $previous = null) {
		$this->page = $page;
		$this->image_url = $image_url;
		$this->image_height = $image_height;
		$this->image_width = $image_width;
		$this->timthumb = $timthumb;

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
}

// For if a template does not exist
class ViewNotFoundException extends InternalException {
	protected $view;
	
	public function __construct($message, $view, $code = EXCEPTION_VIEW_NOTFOUND, Exception $previous = null) {
		$this->view = $view;

		parent::__construct($message, $code, $previous);
	}
	
	public function getView() {
		return $this->view;
	}
}

// For if a model does not exist in the database
class ModelNotFoundException extends NotFoundException {
	protected $class;
	protected $item;
	
	public function __construct($message, $class, $item = null, $code = EXCEPTION_MODEL_NOTFOUND, Exception $previous = null) {
		$this->class = $class;
		$this->item = $item;
		
		parent::__construct($message, $code, $previous);
	}
	
	public function getClass() {
		return $this->class;
	}
	
	public function getItem() {
		return $this->item;
	}
}

// Use this instead for timthumb, mainly in case this stuff changes in the future
class TimthumbImageNotFoundException extends ImageNotFoundException {
	public function __construct($message, $page, $image_url, $image_height, $image_width, $timthumb = false, $code = EXCEPTION_TIMTHUMB_NOTFOUND, Exception $previous = null) {
		parent::__construct($message, $page, $image_url, $image_height, $image_width, true, $code, $previous);
	}
}

// If there is an error in the model (i.e. wrong verb)
class ModelConfigurationException extends InternalException {
	protected $verb;
	protected $property;
	protected $class;
	protected $item;
	
	public function __construct($message, $verb, $property, $class, $item, $code = EXCEPTION_MODEL, Exception $previous = null) {
		$this->verb = $verb;
		$this->property = $property;
		$this->class = $class;
		$this->item = $item;

		parent::__construct($message, $class, $item, $code, $previous);
	}
	
	public function getVerb() {
		return $this->verb;
	}
	
	public function getProperty() {
		return $this->property;
	}
	
	public function getClass() {
		return $this->class;
	}
	
	public function getItem() {
		return $this->item;
	}
}

// Glue exceptions - our fault
class GlueInternalException extends InternalException {
	protected $url;
	protected $class;
	protected $method;
	
	public function __construct($message, $url, $class = '', $method = '', $code = EXCEPTION_GLUE, Exception $previous = null) {
		$this->url = $url;
		$this->class = $class;
		$this->method = $method;

		parent::__construct($message, $code, $previous);
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
class GlueURLException extends NotFoundException {
	protected $url;
	
	public function __construct($message, $url, $code = EXCEPTION_GLUE_URL, Exception $previous = null) {
		$this->url = $url;

		parent::__construct($message, $code, $previous);
	}
	
	public function getUrl() {
		return $this->url;
	}
}

// The class referenced in the glue doesn't exist
class GlueClassNotFoundException extends GlueInternalException {
	public function __construct($message, $url, $class, $method, $code = EXCEPTION_GLUE_CLASS, Exception $previous = null) {
		parent::__construct($message, $url, $class, $method, $code, $previous);
	}
}

// The method called by the glue doesnt exist
class GlueMethodNotFoundException extends GlueInternalException {
	public function __construct($message, $url, $class, $method, $code = EXCEPTION_GLUE_METHOD, Exception $previous = null) {
		parent::__construct($message, $url, $class, $method, $code, $previous);
	}
}

class ErrorHandlerException extends InternalException {
	protected $params;
	
	public function __construct($message, $params, $code = EXCEPTION_ERRORHANDLER, Exception $previous = null) {
		$this->params = $params;
		
		parent::__construct($message, $code, $previous);
	}
	
	public function getErrno() {
		return $this->params['errno'];
	}
	
	public function getErrorFile() {
		return $this->params['file'];
	}
	
	public function getErrorLine() {
		return $this->params['line'];
	}
	
	public function getContext() {
		return $this->params['context'];
	}
}

class ValidatorException extends UniversalException {
	protected $invaliddata;
	protected $csrf_failed;
	
	public function __construct($message, $invaliddata, $csrf_failed = false, $code = EXCEPTION_VALIDATOR, Exception $previous = null) {
		$this->invaliddata = $invaliddata;
		$this->csrf_failed = $csrf_failed;
		
		parent::__construct($message, $code, $previous);
	}
	
	public function getData() {
		return $this->invaliddata;
	}
	
	public function hasCsrfFailed() {
		return $this->csrf_failed;
	}
}

class LoginException extends UniversalException {
	protected $username;
	protected $type;
	
	public function __construct($message, $username, $type = LOGIN_EXCEPTION_OTHER, $code = EXCEPTION_VALIDATOR, Exception $previous = null) {
		$this->username = $username;
		$this->type = $type;
		
		parent::__construct($message, $code, $previous);
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getType() {
		return $this->type;
	}
}

function errorhandler($errno, $errstr, $errfile, $errline, $errcontext) {
	throw new ErrorHandlerException($errstr, array('errno' => $errno, 'file' => $errfile, 'line' => $errline, 'context' => $errcontext));
	
	return null;
}

set_error_handler('errorhandler', E_ALL & ~E_NOTICE);

?>