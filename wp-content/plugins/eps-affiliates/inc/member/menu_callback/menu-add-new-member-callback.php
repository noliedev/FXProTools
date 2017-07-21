<?php 
/*
 * ---------------------------------------------
 * Add new member
 * ---------------------------------------------
*/
 function afl_add_new_member () {
	 
 	$post = array();
 	if ( isset($_POST['submit'] ) ) {
        $rules = create_validation_rules($_POST);
			 	$resp  = set_form_validation_rule($rules);
			 	if (!empty($resp)) {
			 		echo $resp;
			 		$post = $_POST;
			 	} else {
			 		// // sanitize user form input
	        global $username, $password, $email, $sponsor, $first_name, $sur_name;
	        $username   =   sanitize_user( $_POST['user_name'] );
	        $password   =   esc_attr( $_POST['password'] );
	        $email      =   sanitize_email( $_POST['email'] );
	        $first_name =   sanitize_text_field( $_POST['first_name'] );
	        $sur_name  	=   sanitize_text_field( $_POST['sur_name'] );
	        $sponsor   	=   sanitize_text_field( $_POST['sponsor'] );
	 
	        // // call @function complete_registration to create the user
	        // // only when no WP_error is found

	        $user_uid = complete_registration(
		        $username,
		        $password,
		        $email,
		        $first_name,
		        $sur_name,
		        $sponsor
	        );
	        $post_data = array();
	        if ($user_uid) {
	        	$post_data['uid'] = $user_uid;
	        	//extract sponsor uid
	        	preg_match_all('/\d+/', $sponsor, $matches);
    				$post_data['sponsor_uid'] = $matches[0];
	        }
	        //user get the uid,if a uid get then insert to genealogy
	        $reg_object = new Eps_affiliates_registration;
	        $reg_object->afl_join_member($post_data);
				}
    }
 	afl_add_new_member_form($post);
 }
/*
 * ---------------------------------------------
 * Add new member Form
 * ---------------------------------------------
*/
 function afl_add_new_member_form ($post) {
 	// pr(get_users());
 	afl_content_wrapper_begin();

 	$form = array();
 	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	$form['first_name'] = array(
 		'#title' => 'First Name',
 		'#type' => 'text',
 		'#name' => 'first name',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#default_value' => isset($post['first_name']) ? $post['first_name'] : '',
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
	$form['sur_name'] = array(
 		'#title' => 'Sur Name',
 		'#type' => 'text',
 		'#name' => 'Sur name',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#default_value' => isset($post['sur_name']) ? $post['sur_name'] : '',
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
 	$form['user_name'] = array(
 		'#title' => 'User Name',
 		'#type' => 'text',
 		'#name' => 'User name',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#default_value' => isset($post['user_name']) ? $post['user_name'] : '',
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
 	$form['email'] = array(
 		'#title' => 'Email address',
 		'#type' => 'text',
 		'#name' => 'Email address',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#default_value' => isset($post['email']) ? $post['email'] : '',
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
 	$form['password'] = array(
 		'#title' => 'Password',
 		'#type' => 'password',
 		'#name' => 'Password',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
 	$form['confirm_password'] = array(
 		'#title' => 'Confirm Password',
 		'#type' => 'password',
 		'#name' => 'Confirm Password',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
 	$form['sponsor'] = array(
 		'#title' => 'Sponsor',
 		'#type' => 'auto_complete',
 		'#name' => 'sponsor',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		//for autocomplete call action action hook
 		'#auto_complete_path' => 'users_auto_complete',
 		'#default_value' => isset($post['sponsor']) ? $post['sponsor'] : '',
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
 	$form['mobile'] = array(
 		'#title' => 'Mobile number',
 		'#type' => 'text',
 		'#name' => 'Mobile number',
 		'#attributes' => array(
 			'class' => array(

 			)
 		),
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
 
 	$form['submit'] = array(
 		'#title' => 'Submit',
 		'#type' => 'submit',
 		'#value' => 'Submit',
 		'#attributes' => array(
 			'class' => array(
 				'btn','btn-primary'
 			)
 		),
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
 	echo afl_render_form($form);
 	afl_content_wrapper_end();
 }

function complete_registration($username, $password, $email, $first_name, $sur_name, $sponsor) {
	
    global $reg_errors;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    	=>   $username,
        'user_email'    	=>   $email,
        'user_pass'     	=>   $password,
        'first_name'    	=>   $first_name,
        'sur_name'     		=>   $sur_name,
        'sponsor_id'  		=>   $sponsor,
        'user_registered'	=>   afl_date(),
        );
        // pr($userdata);
        $user = wp_insert_user( $userdata );
        return $user;
    }
}
/*
 * ---------------------------------------------
 * Create validation rules array
 * ---------------------------------------------
*/
	function create_validation_rules ($POST) {
		$rules = array();
			 	$rules[] = array(
			 		'value'=>$POST['user_name'],
			 		'name' =>'user name',
			 		'rules' => array(
			 			'rule_required',
			 			'rule_name_length',
			 			'rule_user_name_valid',
			 			'rule_user_already_name_exists',
			 		)
			 	);
			 	$rules[] = array(
			 		'value'=>$POST['password'],
			 		'name' =>'Password',
			 		'rules' => array(
			 			'rule_required',
			 			'rule_name_length',
			 		)
			 	);
			 	$rules[] = array(
			 		'value'=> $POST['email'],
			 		'name' =>'Email',
			 		'rules' => array(
			 			'rule_required',
			 			'rule_valid_email',
			 			'rule_email_exists',
			 		)
			 	);
			 	//extract the name from the sponsor name
			 	$split = explode('(', $_POST['sponsor']);
			 	$sponsor_name = $split[0];
			 	$rules[] = array(
			 		'value'=> $sponsor_name,
			 		'name' =>'Sponsor',
			 		'rules' => array(
			 			'rule_user_name_exists',
			 			'rule_required',
			 		)
			 	);
		return $rules;
	}

// function registration_validation( $username, $password, $c_password, $email, $first_name, $sur_name, $sponsor)  {
// 	global $reg_errors;
// 	$reg_errors = new WP_Error;
// 	//set required fields
// 	if ( empty( $username ) || empty( $password ) || empty( $email ) || empty( $c_password ) ) {
// 	    $reg_errors->add('field', 'Required form field is missing');
// 	}
// 	//check user name length
// 	if ( 4 > strlen( $username ) ) {
// 	    $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
// 	}
// 	//check user name already exist or not
// 	if ( username_exists( $username ) )
// 	    $reg_errors->add('user_name', 'Sorry, that username already exists!');if ( ! validate_username( $username ) ) {
// 	    $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
// 	}
// 	//check the length of password
// 	if ( 5 > strlen( $password ) ) {
//         $reg_errors->add( 'password', 'Password length must be greater than 5' );
//     }
//   //validating email
//   if ( !is_email( $email ) ) {
// 	  $reg_errors->add( 'email_invalid', 'Email is not valid' );
// 	}
// 	//email already exist or not
// 	if ( email_exists( $email ) ) {
// 	    $reg_errors->add( 'email', 'Email Already in use' );
// 	}
// 	//passwords mismatch
// 	if ( $password != $c_password ) {
// 	    $reg_errors->add( 'confirm password', 'Entered passwords are mis match' );
// 	}

// 	if ( is_wp_error( $reg_errors ) ) {
// 		$wp_error = '';
//     foreach ( $reg_errors->get_error_messages() as $error ) {
//     	$wp_error .= '<p>* '.$error.'</p>';
//     }
//    	echo wp_set_message($wp_error,'danger');
// 	}
// }
