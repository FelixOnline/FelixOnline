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
    /*
     * Render specific template
     *
     * $page - page to render
     * $data - data to include with render [array]
     */
    public function render($page, $data = NULL) {
        if($data) $this->appendData($data);
        $this->page = $page;
        /*
         * Check for specific pages in category 
         * e.g. if gallery page exists then use that etc
         */
        switch($page) {
            case 'frontpage':
                $this->includePage($page);
                break;
            case 'category':
                $this->includePage($page);
                break;
            case 'article':
                $this->includePage($page);
                break;
            default:
                $this->includePage($page);
                break;
        }
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
     * Check if current view is $query
     */
    public function isPage($query) {
        if($query == $this->page) return true;
        else return false; 
    }
}
?>
