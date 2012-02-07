<?php

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
class ModelNotFoundException extends Exception {
	protected $model;
	protected $user;
	
	public function __construct($message, $model, $code = 102, Exception $previous = null) {
		global $currentuser;
		$this->model = $model;
		$this->user = $currentuser;

		parent::__construct($message, $code, $previous);
	}
	
	public function getModel() {
		return $this->model;
	}
	
	public function getUser() {
		return $this->user;
	}
}

// If the image for timthumb doesnt exist
class ImageNotFoundException extends Exception {}

// If there is an error in the model (i.e. wrong verb)
class ModelConfigurationException extends Exception {
	protected $model;
	protected $user;
	protected $verb;
	protected $property;
	
	public function __construct($message, $model, $verb, $propety, $code = 104, Exception $previous = null) {
		global $currentuser;
		$this->model = $model;
		$this->user = $currentuser;
		$this->verb = $verb;
		$this->property = $property;

		parent::__construct($message, $code, $previous);
	}
	
	public function getModel() {
		return $this->model;
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

// URL doesnt match in the glue
class GlueException extends Exception {
	protected $user;
	protected $url;
	protected $class;
	protected $method;
	
	public function __construct($message, $url, $class, $method, $code = 105, Exception $previous = null) {
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

// The class referenced in the glue doesn't exist
class GlueClassNotFoundException extends Exception {
	protected $user;
	protected $url;
	protected $class;
	protected $method;
	
	public function __construct($message, $url, $class, $method, $code = 106, Exception $previous = null) {
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

// The method called by the glue doesnt exist
class GlueMethodNotFoundException extends Exception {
	protected $user;
	protected $url;
	protected $class;
	protected $method;
	
	public function __construct($message, $url, $class, $method, $code = 107, Exception $previous = null) {
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

// Other stuff
class InternalException extends Exception {
	protected $user;
	
	public function __construct($message, $code = 108, Exception $previous = null) {
		global $currentuser;
		$this->user = $currentuser;

		parent::__construct($message, $code, $previous);
	}
	
	public function getUser() {
		return $this->user;
	}
}

?>