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
    private $parent; // parent page
    private $data = array(); // data to be added to rendered page
    private $hierarchy = array(); // template hierarchy
    private $sidebar = array(); // array of sidebar modules

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
        if(!$this->parent) {
            $this->parent = $page;
        }
        $this->page = $page;
        $this->includePage($this->cascade());
    }

    /*
     * Private: Include page in enclosed function
     */
    private function includePage($themePage) {
        $themeData = $this->data;
        call_user_func(function() use($themeData, $themePage) {
            extract($themeData);
            include(THEME_DIRECTORY.'/'.$themePage.'.php');
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
     * Public: Check if current view is $query
     */
    public function isPage($query) {
        if($query == $this->parent) return true;
        else return false; 
    }

    /*
     * Public: Set sidebar modules
     * Overrides any previous sidebar modules
     */
    public function setSidebar($modules) {
        $this->sidebar = $modules;
        return $this->sidebar;
    }

    /*
     * Public: Render sidebar with set modules
     */
    public function renderSidebar() {
        if(!$this->sidebar || empty($this->sidebar)) {
            throw new InternalException('No sidebar modules set');
            return false;
        }
        foreach($this->sidebar as $key => $module) {
            $this->render('sidebar/'.$module);
        }
    }

    /*
     * Public: Get sidebar
     */
    public function getSidebar() {
        return $this->sidebar;
    }

    /*
     * Public: Add module to end of sidebar
     * Add a new module to the end of sidebar
     */
    public function addSidebarEnd($module) {
        array_push($this->sidebar, $module);
        return $this->sidebar;
    }

    /*
     * Public: Add module to beginning of sidebar
     * Add a new module to the beginning of sidebar
     */
    public function addSidebarBeginning($module) {
        array_unshift($this->sidebar, $module);
        return $this->sidebar;
    }

}
?>
