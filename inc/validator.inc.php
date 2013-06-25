<?php
/*
 * Validation code
 * 
 * Run Validator::Check(array('field name' => value, ...), array('field name' => array('validator' => 'params', ...), ...);
 * Either all will be fine, or a ValidatorException will be thrown
 * 
 * Call getData on this to get:
 * array (
 * 	'field' => array (
 * 				'failed validator',
 * 				...
 * 				), ...
 * )
 * 
 * Notes on specific validators:
 *  - ic_email - You need to validate for email too (this only checks the suffix)
 *  - ic_username - If local, will always pass
 *  - maxlength - Needs to take a parameter for the maximum length
 *  - csrf - Set the token in the form first using the method in the utilities bit. Parameter should be form name
 * 
 * If a parameter is not needed (i.e. in more or less every case), a dummy value should be provided
 *
 * CSRF example:
 *	  $param = 'formname';
 *	  $token = Utility::generateCSRFToken($param);
 *	  // send these two values along with date in ajax request
 *	  Validator::Check( // this will throw an exception if not valid
 *		  array('csrf' => $token),
 *		  array('csrf' =>
 *			  array(
 *				  'val_1007' => $param
 *			  )
 *		  ) 
 *	  );
 */

class Validator {
	const validator_notnull = 'val_1001';
	const validator_email = 'val_1002';
	const validator_ic_email = 'val_1003';
	const validator_ic_username = 'val_1004';
	const validator_maxlength = 'val_1005';
	const validator_minlength = 'val_1006';
	const validator_csrf = 'val_1007';
	const validator_url = 'val_1008';
	
	public static function Check($source_data, $validators) {
		$bad_fields = array();
		$csrf_failed = false;
		foreach ($source_data as $field => $value) {
			// Check to see if any validators are defined for this field
			if(!array_key_exists($field, $validators)) {
				continue; // no validators
			}
			// There are validators, run each
			foreach ($validators[$field] as $validator => $parameter) {
				switch ($validator) {
					case self::validator_notnull:
						// Checks to see if a field has a value
						if($value == '') {
							if(!array_key_exists($field, $bad_fields)) {
								$bad_fields[$field] = array();
							}
							
							$bad_fields[$field][] = $validator;
						}
						break;
					case self::validator_email:
						if($value == '') {
							break; // Use null filter to trap this case if it can't b enull
						}
						// Check to see if email is an email
						if(!is_email($value)) {
							if(!array_key_exists($field, $bad_fields)) {
								$bad_fields[$field] = array();
							}
							
							$bad_fields[$field][] = $validator;
						}
						break;
					case self::validator_ic_email:
						if($value == '') {
							break; // Use null filter to trap this case if it can't b enull
						}
						// Check to see if an email ends in @imperial.ac.uk or @ic.ac.uk
						if(!preg_match('/(@ic.ac.uk$|@imperial.ac.uk$)/i', $value)) {
							if(!array_key_exists($field, $bad_fields)) {
								$bad_fields[$field] = array();
							}
							
							$bad_fields[$field][] = $validator;
						}
						break;
					case self::validator_ic_username:
						if($value == '') {
							break; // Use null filter to trap this case if it can't b enull
						}
						// Check to see if username is in college directory (if local, assumes success)
						if(LOCAL) {
							break; // Do not validate if local
						} else {
							$ds = ldap_connect("addressbook.ic.ac.uk");
							$r = ldap_bind($ds);
							$justthese = array("gecos");
							$sr = ldap_search($ds, "ou=People, ou=everyone, dc=ic, dc=ac, dc=uk", "uid=$value", $justthese);
							$info = ldap_get_entries($ds, $sr);
							if ($info["count"] > 0) {
								break;
							} else {
								if(!array_key_exists($field, $bad_fields)) {
									$bad_fields[$field] = array();
								}
								
								$bad_fields[$field][] = $validator;
							}
						}
						break;
					case self::validator_maxlength:
						// Check to see if a field is too big
						if(strlen($value) > $parameter) {
							if(!array_key_exists($field, $bad_fields)) {
								$bad_fields[$field] = array();
							}
							
							$bad_fields[$field][] = $validator;
						}
						break;
					case self::validator_minlength:
						// Check to see if a field is too small
						if(strlen($value) < $parameter) {
							if(!array_key_exists($field, $bad_fields)) {
								$bad_fields[$field] = array();
							}
							
							$bad_fields[$field][] = $validator;
						}
						break;
					case self::validator_csrf:
						// Check to see if the CSRF token matches that of the one in the session
						if(array_key_exists('felixonline_csrf_'.$parameter, $_COOKIE) && $value !== $_COOKIE['felixonline_csrf_'.$parameter]) {
							if(!array_key_exists($field, $bad_fields)) {
								$bad_fields[$field] = array();
							}
							
							$bad_fields[$field][] = $validator;
							$csrf_failed = true;
						} elseif(!array_key_exists('felixonline_csrf_'.$parameter, $_COOKIE)) {
							$bad_fields[$field][] = $validator;
							$csrf_failed = true;
						}
						
						// Reset CSRF token
						setcookie('felixonline_csrf_'.$parameter, '', time()-360000, '/', '.'.STANDARD_SERVER);
						
						break;
					case self::validator_url:
						if($value == '') {
							break; // Use null filter to trap this case if it can't b enull
						}
						if(filter_var($value, FILTER_VALIDATE_URL) === false) {
							if(!array_key_exists($field, $bad_fields)) {
								$bad_fields[$field] = array();
							}
							
							$bad_fields[$field][] = $validator;

						}
					default:
						break;
				}
			}

		}

		// Throw an exception if there are invalid fields
		if(count($bad_fields) > 0) {
			throw new ValidatorException(count($bad_fields), $bad_fields, $csrf_failed);
		}
		
		// Nothing bad happened
		return true;
	}
}
