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
    }

    /*
     * Public: Start caching
     */
    public function start() {
        if($this->exists() && !$this->expired()) { // if cache file exists and hasn't expired
            $this->valid = true;
            // load cache file
            include($this->getCache());
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
            echo "<!-- Cached ".date('jS F Y H:i', filemtime($this->getCache()))."-->";
        }
    }

    /*
     * Public static: Clear cache
     */
    static public function clear($name = NULL) {
        if($name) { // delete cache file
            unlink(CACHE_DIRECTORY.$name);
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
        if(time() - $this->expires > filemtime($this->getCache())) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Private: Check cache file exists
     */
    private function exists() {
        return file_exists($this->getCache());
    }

    /*
     * Private: Create cache
     */
    private function createCache($content) {
        $fp = fopen($this->getCache(), 'w+');
        fwrite($fp, $content);
        fclose($fp);
    }

    /*
     * Private: Get cache file
     */
    private function getCache() {
        return $this->directory.$this->name;
    }
}
?>
