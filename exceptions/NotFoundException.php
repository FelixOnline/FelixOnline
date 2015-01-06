<?php

class NotFoundException extends FrontendException {
	protected $matches;
	protected $controller;

	public function __construct(
		$message,
		$matches,
		$controller,
		$code = parent::EXCEPTION_NOTFOUND,
		\Exception $previous = null
	) {
		$this->matches = $matches;
		$this->controller = $controller;

		$app = \FelixOnline\Core\App::getInstance();
;
		parent::__construct($message, $app['env']['PATH_INFO'], $code, $previous);
	}

	public function getMatches() {
		return $this->matches;
	}

	public function getController() {
		return $this->controller;
	}
}
