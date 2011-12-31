<?php
/*
 * Theme class
 * Handles rendering views etc
 */
class Theme {
    private $name; // theme name
    private $directory; // theme directory

    function __construct($name) {
        $this->name = $name;
        $this->directory = BASE_DIRECTORY.'/themes/'.$this->name;
        require_once($this->directory.'/index.php');
    }

    public function getName() { return $this->name; }
    public function getDirectory() { return $this->name; }

    /*
     * Render specific template
     */
    public function render($view) {
        include($this->directory.'/'.$view.'.php');
    }
}
?>
