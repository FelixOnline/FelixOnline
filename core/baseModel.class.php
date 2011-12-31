<?php
/*
 * Base model class
 *
 * Creates dynamic getter functions for model fields
 */
class BaseModel {
    protected $fields; // array that holds all the database fields

    function __construct($dbObject) {
        foreach($dbObject as $key => $value) {
            $this->fields[$key] = $value;
        }
        return $this->fields;
    }

    /* 
     * Create dynamic functions 
     */
    function __call($method,$arguments) {
        $meth = $this->from_camel_case(substr($method,3,strlen($method)-3));
        return array_key_exists($meth,$this->fields) ? $this->fields[$meth] : false;
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
