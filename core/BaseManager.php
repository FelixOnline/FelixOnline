<?php
/**
 * Base manager
 */
class BaseManager
{
	protected $table; // database table
	protected $class; // object class name

	function __construct() {
		global $db;
		global $safesql;
		$this->db = $db;
		$this->safesql = $safesql;
	}

	/**
	 * Get object based on id
	 */
	public function get($id) {
		$object = new $this->class($id);
		return $object;
	}
}
