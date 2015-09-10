<?php

	/**
	 * glue
	 *
	 * Provides an easy way to map URLs to classes. URLs can be literal
	 * strings or regular expressions.
	 *
	 * When the URLs are processed:
	 *	  * deliminators (/) are automatically escaped: (\/)
	 *	  * The beginning and end are anchored (^ $)
	 *	  * An optional end slash is added (/?)
	 *		* The i option is added for case-insensitive searches
	 *
	 * Example:
	 *
	 * $urls = array(
	 *	 '/' => 'index',
	 *	 '/page/(\d+) => 'page'
	 * );
	 *
	 * class page {
	 *	  function GET($matches) {
	 *		  echo "Your requested page " . $matches[1];
	 *	  }
	 * }
	 *
	 * glue::stick($urls);
	 *
	 */
	class glue {

		/**
		 * stick
		 *
		 * the main static function of the glue class.
		 *
		 * @param   array		$urls  		The regex-based url to class mapping
		 * @param   string		$base  		Base url if not at root 
		 * @throws  Exception			   Thrown if corresponding class is not found
		 * @throws  Exception			   Thrown if no match is found
		 * @throws  BadMethodCallException  Thrown if a corresponding GET,POST is not found
		 *
		 */
		static function stick ($urls, $base = NULL) {
			$method = strtoupper($_SERVER['REQUEST_METHOD']);
			$path = $_SERVER['REQUEST_URI'];

			if($base != NULL) {
				$path = substr($path, strpos($path, $base)+strlen($base));
			}

			$found = false;

			krsort($urls);

			foreach ($urls as $regex => $class) {
				$regex = str_replace('/', '\/', $regex);
				$regex = '^' . $regex . '\/?$';
				if (preg_match("/$regex/i", $path, $matches)) {
					$found = true;
					if (class_exists($class)) {
						$obj = new $class;
						if (method_exists($obj, $method)) {
							$obj->$method($matches);
						} else {
							throw new GlueMethodException("Could not find specified method in the class", $path, $class, $method);
						}
					} else {
						throw new GlueInternalException("Could not find specified class", $path, $class, $method);
					}
					break;
				}
			}
			if (!$found) {
				throw new GlueURLException("The URL accessed does not match any URL in the glue", $path);
			}
		}
	}
