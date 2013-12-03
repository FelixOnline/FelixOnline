<?php
/*
 * Utility Class
 *
 * Collection of static functions
 */
class Utility {
	/*
	 * Public Static: Get current page url
	 *
	 * Returns string
	 */
	public static function currentPageURL() {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	/*
	 * Public Static: Trim text
	 *
	 * $string - String to trim
	 * $limit - Character limit for string
	 *
	 * Returns string
	 */
	public static function trimText($string, $limit, $strip = true) {
		if($strip) {
				$string = strip_tags($string); // strip tags
		}
		if(strlen($string) <= $limit) {
			return $string;
		} else {
			return substr($string, 0, $limit).' ... ';
		}
	}

	/*
	 * Public Static: Get list of users in english with links
	 *
	 * $array - array of user objects to output
	 * $bold - if true bold each user
	 *
	 * Returns html string of users
	 */
	public static function outputUserList($array, $bold = false) {
		// sanity check
		if (!$array || !count ($array))
			return '';
		// change array into linked usernames
		foreach ($array as $key => $user) {
			if(!is_object($user)) {
				throw new InternalException($user.' user is not an object');
			}
			$full_array[$key] = '<a href="'.$user->getURL().'">'.$user->getName().'</a>';
		}
		// get last element
		$last = array_pop($full_array);
		// if it was the only element - return it
		if (!count ($full_array))
			return $last;
		$output = implode (', ', $full_array);
		if($bold) {
			$output .= '</b> and <b>';	
		} else {
			$output .= ' and ';
		} 
		$output .= $last;
		return $output;
	}

	/*
	 * Public Static: Hide email behind javascript to prevent robot crawls
	 *
	 * $email - email address to hide
	 * $name - name for email address [optional]
	 *
	 * Returns html code to output
	 */
	public static function hideEmail($email, $name = NULL) {
		if(!$name) $name = $email;
		$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
		$key = str_shuffle($character_set); $cipher_text = '';
		$id = 'e'.rand(1,999999999);
		for ($i=0;$i<strlen($email);$i+=1)
			$cipher_text.= $key[strpos($character_set,$email[$i])];
		$script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";var n="'.$name.'";';
		$script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
		$script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+n+"</a>"';
		$script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
		$script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
		return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
	}
	
	/*
	 * Public Static: Creates CSRF protection token
	 * 
	 * $form_name - name of form (token is unique for form)
	 * $max_length - time for which token is valid (in seconds) [optional, default is 1 hour]
	 */
	public static function generateCSRFToken($form_name, $max_length = 3600) {
		$rand = mt_rand(9, 99999999);
		$time = time();
		$hash = $time * $rand;
		$hash = $hash.$form_name.$max_length;
		$hash = sha1($hash);
		
		setcookie('felixonline_csrf_'.$form_name, $hash, time() + $max_length, '/', '.'.STANDARD_SERVER);
		
		return $hash;
	}

	/*
	 * Public Static: Urlise text
	 * Make url friendly text from string
	 *
	 * $string
	 *
	 * Returns string
	 */
	public static function urliseText($string) {
		$title = strtolower($string); // Make title lowercase
		$title= preg_replace('/[^\w\d_ -]/si', '', $title); // Remove special characters
		$dashed = str_replace( " ", "-", $title); // Replace spaces with hypens
		return $dashed;
	}

	/*
	 * Public static: Get URL
	 * Using curl request url but don't get body
	 *
	 * $url - url to ping
	 */
	public static function getURL($url) {
		$timeout = 5;

		$ch = curl_init($url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	/*
	 * Public static: Redirect users
	 * Redirect a user by sending a new header location
	 *
	 * $goto - url to redirect to
	 * $params - array of parameters to add to url [optional]
	 * $hash - anchor tag to jump to [optional]
	 */
	public static function redirect($goto, $params = NULL, $hash = NULL) {
		if($params) {
			$i = 0;
			if(!$goto) $goto = STANDARD_URL;
			foreach($params as $key => $value) {
				if(strpos($goto,'?')) {
					$goto .= '&'.$key.'='.$value;
				} else if ($i == 0) {
					$goto .= '?'.$key.'='.$value;
				}
				$i++;
			}
		}
		if($hash) {
			$goto .= '#'.$hash;
		}
		header('Location: '.$goto);
	}

	/*
	 * Public: Add http
	 * Adds http to url if doesn't exist
	 */
	public static function addhttp($url) {
		$url = trim($url);
		if($url != '' && $url != 'http://') {
			if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
				$url = "http://" . $url;
			}
		} else {
			$url = '';
		}
		return $url;
	}

	/**
	 * Decode JSON string and throw error if fails
	 *
	 * @param string $string - JSON string to decode
	 *
	 * @static
	 *
	 * @return mixed - associative array
	 * @throws \Exception if json decode fails with message about why
	 */
	public static function jsonDecode($string)
	{
		$json = json_decode($string, true);

		// if json_decode failed
		if ($json === null) {
			self::jsonLastError();
		}

		return $json;
	}

	/**
	 * Encode as JSON and throw error if fails
	 *
	 * @param mixed $data - data to encode
	 *
	 * @static
	 *
	 * @return string - json string
	 * @throws \Exception if json decode fails with message about why
	 */
	public static function jsonEncode($data)
	{
		$json = json_encode($data);

		// if json_encode failed
		if ($json === false) {
			self::jsonLastError();
		}

		return $json;
	}

	/**
	 * Throw json last error
	 */
	public static function jsonLastError()
	{
		switch (json_last_error()) {
		case JSON_ERROR_DEPTH:
			throw new InternalException('Maximum stack depth exceeded');
			break;
		case JSON_ERROR_STATE_MISMATCH:
			throw new InternalException('Underflow or the modes mismatch');
			break;
		case JSON_ERROR_CTRL_CHAR:
			throw new InternalException('Unexpected control character found');
			break;
		case JSON_ERROR_SYNTAX:
			throw new InternalException('Syntax error, malformed JSON');
			break;
		case JSON_ERROR_UTF8:
			throw new InternalException('Malformed UTF-8 characters, possibly incorrectly encoded');
			break;
		default:
			throw new InternalException('Unknown error');
			break;
		}
	}
}
