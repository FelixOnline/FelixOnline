<?php
/*
 * Misc Functions DEPRECATED
 */

/*
 * Local development functions
 */
if(!function_exists('ldap_get_mail')) {
	function ldap_get_mail($username) {
		return $username.'@imperial.ac.uk';
	}
}
