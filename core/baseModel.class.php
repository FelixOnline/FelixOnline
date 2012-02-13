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

    function __construct($dbObject, $class, $item=null) {
        /* initialise db connection and store it in object */
        global $db;
        $this->db = $db;
		
		$this->class = $class;
		$this->item = $item;
		
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
     * TODO match set functions
     */
    function __call($method,$arguments) {
        $meth = $this->from_camel_case(substr($method,3,strlen($method)-3));
        $verb = substr($method, 0, 3);
        switch($verb) {
            case 'get':
                if(array_key_exists($meth, $this->fields)) {
                    return $this->fields[$meth];
                } else {
                    throw new ModelConfigurationException('The requested field does not exist', $verb, $meth, $class, $item);
                }
                break;
            case 'set':
                if(array_key_exists($meth, $this->fields)) {
                    $this->fields[$meth] = $arguments[0];
                    return $this->fields[$meth];
                } else {
                    throw new ModelConfigurationException('The requested field does not exist', $verb, $meth, $class, $item);
                }
                break;
            default:
                throw new ModelConfigurationException('The requested verb is not valid', $verb, $meth, $class, $item);
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

?>
