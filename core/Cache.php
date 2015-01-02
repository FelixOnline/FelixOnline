<?php
/*
 * Caching class
 *
 * Usage:
 *	  $cache = new Cache('frontpage');
 *	  if($cache->start()) {
 *		  // stuff to cache
 *	  } $cache->stop();
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
		$this->expires = CACHE_LENGTH;
		$this->file = array();
	}

	/*
	 * Public: Start caching
	 */
	public function start() {
		if(CACHE && $this->exists() && !$this->expired()) { // if cache file exists and hasn't expired
			$this->valid = true;
			// load cache file
			$cache = $this->getCache();
			echo utf8_decode($cache['content']);
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
			echo "<!-- Cached ".date('jS F Y H:i', filemtime($this->getName()))." -->";
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
		if(time() - $this->expires > filemtime($this->getName())) {
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
	private function createCache($content, $type = 'json') {
		if(CACHE) {
			$fp = fopen($this->getName(), 'w+');
			$data = array(
				'url' => Utility::currentPageURL(),
				'generated' => time(),
				'content' => utf8_encode($content)
			);
			switch($type) {
				case 'serialize':
					$data = serialize($data);
					break;
				default:
					$data = Utility::jsonEncode($data);
					break;
			}
			fwrite($fp, $data);
			fclose($fp);
		} 
	}

	/*
	 * Private: Get array of contents of cache file
	 */
	private function getCache($type = 'json') {
		if(!$this->file) {
			$contents = file_get_contents($this->getName());
			switch($type) {
				case 'serialize':
					$this->file = unserialize($contents);
					break;
				default:
					$this->file = Utility::jsonDecode($contents);
					break;
			}
		}
		return $this->file;
	}

	/*
	 * Private: Get cache name with directory
	 */
	private function getName() {
		return $this->directory.$this->name;
	}

	/*
	 * Public: code
	 * Cache some code
	 *
	 * $class - object if code is method
	 * $function - name of function to cache [optional]
	 *
	 * n.b. if no function is specified then $class is taken as an anonymous function to cache
	 * 
	 * Examples:
	 *	  // function to cache: $foo->bar();
	 *	  $output = $cache->code(array($foo, 'bar'));
	 *
	 *	  // anonymous function
	 *	  $output = $cache->code(function() {
	 *		  return 'Hello!';
	 *	  });
	 *
	 * Returns result of function
	 */
	public function code($function) {
		if(CACHE && $this->exists() && !$this->expired()) { // if cache file exists and hasn't expired
			$this->valid = true;
			$cache = $this->getCache('serialize');
			return $cache['content'];
		} else {
			if(is_callable($function)) {
				$content = call_user_func($function);
				$this->createCache($content, 'serialize');
				return $content;
			}
		}
	}
}
