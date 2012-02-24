<?php
/*
 * Resources class
 * Handles all css and javascript resources
 */
class ResourceManager {
    private $css = array(); // array of css files
    private $js = array(); // array of js files
    private $cssPath; // path for css files
    private $jsPath; // path for JavaScript files

    function __construct($css, $js) {
        if($css) {
            $this->addCSS($css);
        }
        if($js) {
            $this->addJS($js);
        }
        $this->cssPath = 'css/';
        $this->jsPath = 'js/';
    }

    /*
     * Public: Add css files
     *
     * $css - array of css files to load
     *
     * Returns css array
     */
    public function addCSS($css) {
        if(is_array($css)) {
            foreach($css as $key => $value) {
                array_push($this->css, $value);
            }
            return $this->css;
        } else {
            throw new InternalException("CSS files to add is not an array");
        }
    }

    /*
     * Public: Add js files
     *
     * $js - array of js files to load
     *
     * Returns js array
     */
    public function addJS($js) {
        if(is_array($js)) {
            foreach($js as $key => $value) {
                array_push($this->js, $value);
            }
            return $this->js;
        } else {
            throw new InternalException("JS files to add is not an array");
        }
    }

    /*
     * Public: Replace css files
     */
    public function replaceCSS($css) {
        if(is_array($css)) {
            $this->css = $css;
            return $this->css;
        } else {
            throw new InternalException("CSS files to add is not an array");
        }
    }

    /*
     * Public: Replace js files
     */
    public function replaceJS($js) {
        if(is_array($js)) {
            $this->js = $js;
            return $this->js;
        } else {
            throw new InternalException("JS files to add is not an array");
        }
    }

    /*
     * Public: Get css files
     *
     * Returns array of css files paths
     */
    public function getCSS() {
        $data = array();
        foreach($this->css as $key => $value) {
            if($this->isExternal($value)) {
                $data[$key] = $value;
            } else {
                if($this->isLess($value)) {
                    $value = $this->processLess($value); 
                }
                $data[$key] = $this->getFilename($value, 'css');
            }
        }
        return $data;
    }

    /*
     * Public: Get js files
     *
     * Returns array of js files paths
     */
    public function getJS() {
        $data = array();
        foreach($this->js as $key => $value) {
            if($this->isExternal($value)) {
                $data[$key] = $value;
            } else {
                $data[$key] = $this->getFilename($value, 'js');
            }
        }
        return $data;
    }

    /*
     * Check if file is external
     */
    private function isExternal($file) {
        if(strpos($file, 'http://') !== false 
        || strpos($file, 'https://') !== false) { 
            return true;
        } else {
            return false;
        }
    }

    /*
     * Get path to file
     */
    private function getFilename($file, $type, $version = 'url') {
        if($version == 'url') { 
            $root = STANDARD_URL.'themes/'.THEME_NAME.'/';
        }
        else if($version == 'dir') {
            $root = THEME_DIRECTORY.'/';
        }
        switch($type) {
            case 'css':
                return $root.$this->cssPath.$file;
                break;
            case 'js':
                return $root.$this->jsPath.$file;
                break;
        }
    }

    /*
     * If file is a less file
     */
    private function isLess($file) {
        if(substr(strrchr($file,'.'),1) == 'less') {
            return true;    
        } else {
            return false;
        }
    }

    /*
     * Process less file
     *
     * Returns compiled css filename
     */
    private function processLess($lessfile) {
        require_once(BASE_DIRECTORY.'/inc/lessc.inc.php');
        $filename = strstr($lessfile, '.', true);
        $cssfile = $this->getFilename($filename.'.css', 'css', 'dir');
        // load the cache
        $cachefile = $this->getFilename($filename.".cache", 'css', 'dir');
        if (file_exists($cachefile)) {
            $cache = unserialize(file_get_contents($cachefile));
        } else {
            $cache = $this->getFilename($lessfile, 'css', 'dir');
        }
        $newcache = lessc::cexecute($cache);
        if (!is_array($cache) || $newcache['updated'] > $cache['updated']) {
            file_put_contents($cachefile, serialize($newcache));
            file_put_contents($cssfile, $newcache['compiled']);
        } 
        return $filename.'.css';
    }
}
?>
