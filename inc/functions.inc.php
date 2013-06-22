<?php
/*
 * Functions
 */

/*
 * Hide email behind javascript
 *
 * $email - email address to hide
 */
function hideEmail($email) {
	$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
	$key = str_shuffle($character_set); $cipher_text = '';
	$id = 'e'.rand(1,999999999);
	for ($i=0;$i<strlen($email);$i+=1)
		$cipher_text.= $key[strpos($character_set,$email[$i])];
	$script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
	$script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
	$script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
	$script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
	$script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
	return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
}

/*
 * Create a Roman numeral from a number
 *
 * $num - number to be converted
 *
 * Returns string
 */
function romanNumerals($num) {
	$n = intval($num);
	$res = '';

	/*** roman_numerals array  ***/
	$roman_numerals = array(
		'M'  => 1000,
		'CM' => 900,
		'D'  => 500,
		'CD' => 400,
		'C'  => 100,
		'XC' => 90,
		'L'  => 50,
		'XL' => 40,
		'X'  => 10,
		'IX' => 9,
		'V'  => 5,
		'IV' => 4,
		'I'  => 1
	);

	foreach ($roman_numerals as $roman => $number) {
		/*** divide to get  matches ***/
		$matches = intval($n / $number);

		/*** assign the roman char * $matches ***/
		$res .= str_repeat($roman, $matches);

		/*** substract from the number ***/
		$n = $n % $number;
	}

	/*** return the res ***/
	return $res;
}

/*
 * Get relative time from timestamp
 *
 * $date - unix timestamp
 *
 * Returns string
 */
function getRelativeTime($date) {
	$diff = time() - $date;
	if ($diff<60)
		return $diff . " second" . plural($diff) . " ago";
	$diff = round($diff/60);
	if ($diff<60)
		return $diff . " minute" . plural($diff) . " ago";
	$diff = round($diff/60);
	if ($diff<24)
		return $diff . " hour" . plural($diff) . " ago";
	$diff = round($diff/24);
	if ($diff<7)
		return $diff . " day" . plural($diff) . " ago";
	$diff = round($diff/7);
	if ($diff<4)
		return $diff . " week" . plural($diff) . " ago";
	return "on " . date("F j, Y", $date);
}

/*
 * If number is plural then return 's'
 *
 * $num - number to check
 *
 * Return string
 */
function plural($num) {
	if ($num != 1)
		return "s";
}

/*
 * Local development functions
 */
if(!function_exists('ldap_get_mail')) {
	function ldap_get_mail($username) {
		return $username.'@imperial.ac.uk';
	}
}
