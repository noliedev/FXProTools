<?php 
/*
 * ----------------------------------------------------------------
 * Validation for the fields
 * ----------------------------------------------------------------
*/
	class Form_validation_rules {

		//required field
		public function rule_required($name = '',$value = ''){
			$response = array();
			if (empty($value)) {
				$response['status'] 	= 0;
				$response['message'] 	= 'Field '.$name.' field required';
			} else {
				$response['status'] 	= 1;
			}
			return $response;
		}
		//numeric validation
		public function rule_is_numeric($name = '',$value = '') {
			if (!empty($value)) {
				if (!is_numeric($value)) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Field '.$name.' must contain a numeric number';
				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
		}
		//user name legth
		public function rule_name_length ($name = '',$value = '') {
			if (!empty($value)) {
<<<<<<< HEAD
				if ( 1 > strlen( $value ) ) {
=======
				if ( 4 > strlen( $value ) ) {
>>>>>>> a3eb117dca110ed02010bf0895e5c78cdae5e735
					$response['status'] 	= 0;
					$response['message'] 	= $name.' too short. At least 4 characters is required';
				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
			
		}
		//user name legth
		public function rule_user_name_valid ($name = '',$value = '') {
			if (!empty($value)) {
				if ( !validate_username( $value )) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Sorry, the username you entered is not valid';
				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
		}
		//user name exist
		public function rule_user_name_exists ($name = '',$value = '') {
			if (!empty($value)) {
				if ( !username_exists( $value )) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Sorry, that '.$name.' not exists!';
				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
			
		}
		//user name already exists
		public function rule_user_already_name_exists ($name = '',$value = '') {
			if (!empty($value)) {
				if ( username_exists( $value )) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Sorry, that username already exists!';
				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
			
		}
		//is email valid
		public function rule_valid_email ($name = '',$value = '') {
			if (!empty($value)) {
				if ( !is_email( $value )) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Email is not valid';
				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
			
		}
		//is email exists
		public function rule_email_exists ($name = '',$value = '') {
			if (!empty($value)) {
				if ( email_exists( $value )) {
					$response['status'] 	= 0;
					$response['message'] 	= 'Email Already in use';
				} else {
					$response['status'] 	= 1;
				}
				return $response;
			}
			
		}
	}