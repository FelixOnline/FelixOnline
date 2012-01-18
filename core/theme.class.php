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
    private $data = array(); // data to be added to rendered page
    private $hierarchy = array(); // template hierarchy

    function __construct($name) {
        global $currentuser, $db, $timing;
        $this->name = $name;
        $this->directory = BASE_DIRECTORY.'/themes/'.$this->name;
        $this->url = STANDARD_URL.'themes/'.$this->name;
        $this->appendData(array(
            'currentuser' => $currentuser, 
            'db' => $db, 
            'timing' => $timing,
            'theme' => $this
        ));
        require_once($this->directory.'/index.php');
    }

    public function getName() { return $this->name; }
    public function getDirectory() { return $this->name; }
    public function getURL() { return $this->url; }

    public function appendData($array) {
        $this->data = array_merge($this->data, $array);
    }

    public function setHierarchy($hierarchy) {
        return $this->hierarchy = $hierarchy;
    }

    /*
     * Render specific template
     *
     * $page - page to render
     * $data - data to include with render [array]
     */
    public function render($page, $data = NULL) {
        if($data) $this->appendData($data);
        $this->page = $page;
        $this->includePage($this->cascade());
    }

    /*
     * Private: Include page in enclosed function
     */
    private function includePage($page) {
        $data = $this->data;
        call_user_func(function() use($data, $page) {
            extract($data);
            include(THEME_DIRECTORY.'/'.$page.'.php');
        });
    }

    /*
     * Private: Cascade through template hierarchy
     *
     * Returns matched page
     */
    private function cascade() {
        if($this->hierarchy) { // if there is a hierarchy defined
            foreach($this->hierarchy as $key => $value) { // loop through each hierarchy
                $file = $this->page.'-'.$this->data[$this->page]->{'get'.$this->toCamelCase($value)}();
                if($this->fileExists($file)) { // if that file exists then return it
                    $this->hierarchy = array(); // reset hierarchy for further renders TODO
                    return $file;
                }        
            }
            $this->hierarchy = array(); // reset hierarchy for further renders TODO
        }
        return $this->page; // if no page found then return base page
    }

    /*
     * Private: Check whether file exists
     */
    private function fileExists($file) {
        return file_exists(THEME_DIRECTORY.'/'.$file.'.php');
    }

    /*
     * Translates a string with dashes into camel case (e.g. first-name -> FirstName)
     * $str - String in dash format
     * $ucfirst - If true, capitalise the first char in $str
     * Returns string - $str translated into camel caps
     */
    private function toCamelCase($str, $ucfirst = true) {
        $parts = explode('-', $str);
        $parts = $parts ? array_map('ucfirst', $parts) : array($str);
        $parts[0] = $ucfirst ? ucfirst($parts[0]) : lcfirst($parts[0]);
        return implode('', $parts);
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
