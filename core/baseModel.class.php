<?php
/*
 * Base model class
 *
 * Creates dynamic getter functions for model fields
 */
class BaseModel {
	protected $fields = array(); // array that holds all the database fields
	protected $dbtable; // name of database table
	protected $class;
	protected $item;
	protected $db;
	private $imported = array();
	private $importedFunctions = array();
	protected $filters = array();

	function __construct($dbObject, $class, $item=null) {
		/* initialise db connection and store it in object */
		global $db;
		$this->db = $db;
		
		$this->class = $class;
		$this->item = $item;

		if(class_exists(get_class($this).'Helper')) {
			$this->import(get_class($this).'Helper');
		}
		
		if($dbObject) {
			foreach($dbObject as $key => $value) {
				$this->fields[$key] = $value;
			}
		} else {
			throw new ModelNotFoundException('No model in database', $class, $item);
		}
		return $this->fields;
	}

	/* 
	 * Create dynamic functions 
	 */
	function __call($method,$arguments) {
		// check if there is an imported function that matches request
		if(array_key_exists($method, $this->importedFunctions)) {
			// invoke the function  
			return call_user_func_array(array($this->importedFunctions[$method], $method), $arguments);
		} else {
			$meth = $this->from_camel_case(substr($method,3,strlen($method)-3));
			$verb = substr($method, 0, 3);
			switch($verb) {
				case 'get':
					if(array_key_exists($meth, $this->fields)) {
						return $this->fields[$meth];
					}
					throw new ModelConfigurationException('The requested field does not exist', $verb, $meth, $class, $item);
					break;
				case 'set':
					$this->fields[$meth] = $arguments[0];
					return $this->fields[$meth];
					break;
				default:
					throw new ModelConfigurationException('The requested verb is not valid', $verb, $meth, $class, $item);
					break; 
			}
		}
	}

	/*
	 * Import an object and allow its functions to be used in this class
	 */
	public function import($object) {  
		// the new object to import
		$newImport = new $object($this);
		// the name of the new object (class name)
		$importName = get_class($newImport);
		// the new functions to import
		$importFunctions = get_class_methods($newImport);
  
		// add the object to the registry
		array_push($this->imported, array($import_name, $newImport));  
  
		// add the methods to the registry
		foreach($importFunctions as $key => $functionName) {
			$this->importedFunctions[$functionName] = &$newImport;
		}
	}   

	/*
	 * Public: Set dbtable
	 */
	public function setDbtable($table) {
		$this->dbtable = $table;
		return $this->dbtable;
	}

	/*
	 * Public: Save all fields to database TODO
	 *
	 * Example:
	 *	  $obj = new Obj();
	 *	  $obj->setTable('comment');
	 *	  $obj->setUser('k.onions');
	 *	  $obj->setContent('hello');
	 *	  $obj->save();
	 */
	public function save() {
		$arrayLength = count($this->fields);
		if(!$arrayLength) {
			throw new InternalException('No fields in object', $this->class, '');
		}
		$sql = "INSERT INTO `";

		if(!$this->dbtable) {
			throw new InternalException('No table specified', $this->class, '');
		}
		$sql .= $this->dbtable;

		$sql .= "` (";
		$i = 1; // counter
		foreach($this->fields as $key => $value) {
			if(array_key_exists($key, $this->filters)) {
				$key = $this->filters[$key];
			}
			if($i == $arrayLength) {
				$sql .= $key;
			} else {
				$sql .= $key.', ';
			}
			$i++;
		} 
		$sql .= ") VALUES (";
		$i = 1;
		foreach($this->fields as $key => $value) {
			if($value) {
				if(is_numeric($value)) {
					$sql .= $value;
				} else {
					$sql .= "'".$this->db->escape($value)."'";
				}
			} else {
				$sql .= "''";
			}
			if($i != $arrayLength) {
				$sql .= ", ";
			}
			$i++;
		} 
		$sql .= ") ";
		$sql .= "ON DUPLICATE KEY UPDATE ";
		$i = 1;
		foreach($this->fields as $key => $value) {
			if(array_key_exists($key, $this->filters)) {
				$key = $this->filters[$key];
			}
			$sql .= $key."='".$this->db->escape($value)."'";
			if($i != $arrayLength) {
				$sql .= ", ";
			}
			$i++;
		}
		return $this->db->query($sql);
	}

	/*
	 * Public: Set field filters
	 *
	 * $filters - array
	 *
	 * Returns filters
	 */
	public function setFieldFilters($filters) {
		$this->filters = $filters;
		return $this->filters;
	}

	/*
	 * Public: Get all fields
	 */
	public function getFields() {
		return $this->fields;
	}
  
	/* 
	 * Convert camel case to underscore
	 * http://www.paulferrett.com/2009/php-camel-case-functions/ 
	 */
	function from_camel_case($str) {
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}
}

