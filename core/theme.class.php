<?php
/*
 * Theme class
 * Handles rendering views etc
 */
class Theme {
    private $name; // theme name
    private $directory; // theme directory
    private $url;
    private $page; // current page

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
     * TODO check if specific template files exist
     */
    public function render($page, $matches) {
        global $currentuser;
        $this->page = $page;
        switch($page) {
            case 'frontpage':
                include($this->directory.'/'.$page.'.php');
                break;
            case 'category':
                include($this->directory.'/'.$page.'.php');
                break;
            case 'article':
                break;
        }
    }

    /*
     * Check if current view is $query
     */
    public function isPage($query) {
        if($query == $this->page) return true;
        else return false; 
    }
}
?>
