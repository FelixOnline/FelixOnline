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
	 *
	 * $id - array or single id
	 */
	public function get($id) {
		if (is_array($id)) {
			$objects = [];
			foreach($id as $i) {
				$objects[] = new $this->class($i);
			}
			return $objects;
		} else {
			$object = new $this->class($id);
			return $object;
		}
	}

	/**
	 * Get all objects
	 */
	public function all($limit = NULL, $order = array("id", "ASC")) {
		$params = array(
			$this->table,
			$order[0],
			$order[1],
		);

		$sql = "SELECT
					`id`
				FROM %s
				ORDER BY %s %s";

		if (!is_null($limit)) {
			$sql .= " LIMIT 0, %i";
			$params[] = $limit;
		}

		$sql = $this->safesql->query($sql, $params);

		$results = $this->db->get_results($sql);
		$objects = [];

		foreach($results as $result) {
			$objects[] = new $this->class($result->id);
		}

		return $objects;
	}

	/**
	 * Filter objects
	 *
	 * Example:
	 *
	 *	$articleManager->filter(array(
	 *		'published IS NOT NULL',
	 *		'published < NOW()'
	 *	), 20, array('published', 'ASC'));
	 */
	public function filter($filters, $limit = NULL, $order = array("id", "ASC")) {
		$filter = implode(" AND ", $filters);
		$params = array(
			$this->table,
			$filter,
			$order[0],
			$order[1],
		);

		$sql = "SELECT
					`id`
				FROM %s
				WHERE %s
				ORDER BY %s %s";

		if (!is_null($limit)) {
			$sql .= " LIMIT 0, %i";
			$params[] = $limit;
		}

		$sql = $this->safesql->query($sql, $params);

		$results = $this->db->get_results($sql);
		$objects = [];

		foreach($results as $result) {
			$objects[] = new $this->class($result->id);
		}

		return $objects;
	}
}
