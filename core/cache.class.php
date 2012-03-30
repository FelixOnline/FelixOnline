<?php
/*
 * Caching class
 *
 * Usage:
 *      $cache = new Cache('frontpage');
 *      if($cache->start()) {
 *          // stuff to cache
 *      } $cache->stop();
 */
class Cache {
    private $name; // name of cache
    private $directory;
    private $file; // contents of cache file
    private $expires; // number of seconds till cache expires
    private $valid; // if cache is valid

    function __construct($name, $directory = NULL) {
        $this->name = $name;
        if($directory) {
            $this->directory = $directory; 
        } else {
            $this->directory = CACHE_DIRECTORY;
        }
		if(!is_writable($this->directory)) {
			throw new InternalException('Cache directory '.$this->directory.' is not writable');
		}
        $this->expires = 20 * 60; // default 20mins
        $this->file = array();
    }

    /*
     * Public: Start caching
     */
    public function start() {
        if($this->exists() && !$this->expired()) { // if cache file exists and hasn't expired
            $this->valid = true;
            // load cache file
            $cache = $this->getCache();
            echo $cache['content'];
            return false;
        } else {
            ob_start();
            return true;
        }
    }

    /*
     * Public: Stop caching
     */
    public function stop() {
        if(!$this->valid) { // if not valid
            // generate cache
            $content = ob_get_contents(); 
            $this->createCache($content);
            ob_end_flush();
        } else {
            echo "<!-- Cached ".date('jS F Y H:i', filemtime($this->getName()))."-->";
        }
    }

    /*
     * Public static: Clear cache
     */
    static public function clear($name = NULL, $regenerate = true) {
        if($name) { // delete cache file
            $cache = new Cache($name);
            if(!$cache->exists()) {
                return false;
            }
            $contents = $cache->getCache();
            unlink(CACHE_DIRECTORY.$name);
            // regenerate cache
            if($regenerate) {
                Utility::getURL($contents['url']);
            }
        } else { // clear entire cache
            foreach (glob(CACHE_DIRECTORY.'*') as $filename) {
                unlink($filename);
            }
        }
    }

    /*
     * Public: Set expiry time
     */
    public function setExpiry($time) {
        $this->expires = $time;
        return $this->expires;
    }

    /*
     * Private: Check if cache has expired
     */
    private function expired() {
        if(!$this->exists()) {
            return false;
        }
        if($this->expires === 0) {
            return false; // infinity case
        }
        $cache = $this->getCache();
        if(time() - $this->expires > $cache['generated']) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Private: Check cache file exists
     */
    public function exists() {
        return file_exists($this->getName());
    }

    /*
     * Private: Create cache
     */
    private function createCache($content) {
        $fp = fopen($this->getName(), 'w+');
        $data = json_encode(array(
            'url' => Utility::currentPageURL(),
            'generated' => time(),
            'content' => $content
        ));
        fwrite($fp, $data);
        fclose($fp);
    }

    /*
     * Private: Get array of contents of cache file
     */
    private function getCache() {
        if(!$this->file) {
            $contents = file_get_contents($this->getName());
            $this->file = json_decode($contents, true);
        }
        return $this->file;
    }

    /*
     * Private: Get cache name with directory
     */
    private function getName() {
        return $this->directory.$this->name;
    }
}
?>
