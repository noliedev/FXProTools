<?php 
function afl_generate_users () {
	echo afl_eps_page_header();
	
	echo afl_content_wrapper_begin();
		afl_generate_users_form();
	echo afl_content_wrapper_end();
}

function afl_generate_users_form () {
	$website = "http://example.com";

	afl_generate_users_form_callback();
	if (!empty($_POST['submit'])) {
		$error_count 		= 0;
		$success_count 	= 0;

		$start_with = $_POST['user_name_start'];
		$count = $_POST['user_count'];

		$sponsor = $_POST['sponsor'];
		preg_match('#\((.*?)\)#', $sponsor, $matches);
		$sponsor_uid = $matches[1];

		for ($i = 1; $i <= $count ; $i++) { 
			$name = $start_with.'-'.$i;
			if (!username_exists( $name )) {
				$userdata = array(
	        'user_login'    	=>  $name ,
	        'user_email'    	=>   $name.'@eps.com',
	        'user_pass'     	=>   $name,
	        'first_name'    	=>   $name,
	        'last_name'     	=>   $name,
        );
        $user = wp_create_user( $name, $name, $name.'@eps.com' );

        if ($user) {
        	$reg_object = new Eps_affiliates_registration;
	        $reg_object->afl_join_member(
	        	array(
	        		'uid'=>$user,
	        		'sponsor_uid' => $sponsor_uid
	        		)
	        );

        	$success_count+=1;
        } else {
        	$error_count+=1;
        }
			} else {
        $error_count+=1;
			}
		}
		if ($success_count) {
			echo wp_set_message('Generated users count : '.$success_count, 'success');
		}
		if ($error_count) {
			echo wp_set_message('Errro occured count : '.$error_count, 'error');
		}
	}
}

function afl_generate_users_form_callback( ){
	$form = array();
	$form['#method'] = 'post';
	$form['#action'] = $_SERVER['REQUEST_URI'];
	$form['user_name_start'] = array(
		'#type' =>'text',
		'#title' =>'starting-with',
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['sponsor'] = array(
		'#type' =>'auto_complete',
		'#title' =>'sponsor',
		'#auto_complete_path' => 'users_auto_complete',
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['user_count'] = array(
		'#type' =>'text',
		'#title' =>'No.of users',
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['submit'] = array(
		'#type' =>'submit',
		'#value' =>'Generate'
	);
	echo afl_render_form($form);
}
function afl_generate_users_form_validation ($name) {

}