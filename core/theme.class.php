<?php
/*
 * Theme class
 * Handles rendering views etc
 */
class Theme {
    private $name; // theme name
    private $directory; // theme directory
    private $url;

    function __construct($name) {
        $this->name = $name;
        $this->directory = BASE_DIRECTORY.'/themes/'.$this->name;
        $this->url = STANDARD_URL.'themes/'.$this->name;
        require_once($this->directory.'/index.php');
    }

    public function getName() { return $this->name; }
    public function getDirectory() { return $this->name; }
    public function getURL() { return $this->url; }

    /*
     * Render specific template
     */
    public function render($view) {
        include($this->directory.'/'.$view.'.php');
    }
}
?>
