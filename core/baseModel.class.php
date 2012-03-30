<?php
/*
 * Base model class
 *
 * Creates dynamic getter functions for model fields
 */
class BaseModel {
    protected $fields = array(); // array that holds all the database fields
    protected $class;
	protected $item;
    protected $db;
    private $imported;
    private $importedFunctions;

    function __construct($dbObject, $class, $item=null) {
        /* initialise db connection and store it in object */
        global $db;
        $this->db = $db;
		
		$this->class = $class;
		$this->item = $item;

        // import functions
        $this->imported = array();
        $this->importedFunctions = array();

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
                    if(array_key_exists($meth, $this->fields)) {
                        $this->fields[$meth] = $arguments[0];
                        return $this->fields[$meth];
                    }
                    throw new ModelConfigurationException('The requested field does not exist', $verb, $meth, $class, $item);
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
     * Public: Save all fields to database TODO
     */
    public function save() {
        $arrayLength = count($this->fields);
        $sql = "INSERT INTO `";
        $sql .= strtolower(get_class($this));
        $sql .= "` (";
        $i = 1; // counter
        foreach($this->fields as $key => $value) {
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
                    $sql .= "'".$value."'";
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
            $sql .= $key."='".$value."'";
            if($i != $arrayLength) {
                $sql .= ", ";
            }
            $i++;
        }
        return $this->db->query($sql);
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

