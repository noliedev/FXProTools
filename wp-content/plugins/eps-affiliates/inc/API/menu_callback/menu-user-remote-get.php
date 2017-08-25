<?php

	function afl_admin_user_remote_access () {
		echo afl_eps_page_header();
		afl_content_wrapper_begin();
			afl_admin_user_remote_access_callback();
			afl_admin_get_remote_users();
		afl_content_wrapper_end();
	}

	function afl_admin_user_remote_access_callback () {

		if ( isset($_POST['submit'])) {
			unset($_POST['submit']);
			if ( afl_admin_user_remote_access_callback_validation($_POST) ){
				afl_admin_user_remote_access_callback_submit($_POST);
			}
		}

		$form = array();
		$form['#action'] = $_SERVER['REQUEST_URI'];
	 	$form['#method'] = 'post';
	 	$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	 	$form['remote_user_get_url'] = array(
	 		'#title' 	=> 'User List Remote url',
	 		'#type'  	=> 'text',
	 		'#name'		=> 'user-list-url',
	 		'#default_value' => isset($post['remote_user_get_url']) ? $post['remote_user_get_url'] : (!empty(afl_variable_get('remote_user_get_url')) ? afl_variable_get('remote_user_get_url') : '') ,
	 		'#required'	=> TRUE
	 	);

		$form['submit'] = array(
	 		'#title' => 'Submit',
	 		'#type' => 'submit',
	 		'#value' => 'Submit',
	 		'#attributes' => array(
	 			'class' => array(
	 				'btn','btn-primary','pull-left'
	 			)
	 		),
	 	);

	 	echo afl_render_form($form);
	}

	function afl_admin_user_remote_access_callback_validation ( $form_state = array() ) {
		$rules = array();
		foreach ($form_state as $key => $value) {
			$rules[] = array(
		 		'value'	=> $value,
		 		'name' 	=> str_replace('_', ' ', $key),
		 		'field' => str_replace('_', '-', $key),
		 		'rules' => array(
		 			'rule_required',
		 		)
		 	);
		}

		$resp  = set_form_validation_rule($rules);

		if (!$resp) {
			return false;
		}
		else 
			return true;	 
	}

	function afl_admin_user_remote_access_callback_submit ( $form_state = array() ) {
		foreach ($form_state as $key => $value) {
			afl_variable_set($key, $value);
		}
	}

/*
 * --------------------------------------------------------------------------------------
 * Get the users from the remote user list url
 * --------------------------------------------------------------------------------------
*/
 	function afl_admin_get_remote_users () {
	 	$list_remote_url  = afl_variable_get('remote_user_get_url', '');

	 	if ( !empty($list_remote_url)) {
	 		$form1 = array();
			$form1['#action'] = $_SERVER['REQUEST_URI'];
		 	$form1['#method'] = 'post';
		 	$form1['#prefix'] ='<div class="form-group row">';
		 	$form1['#suffix'] ='</div>';

	 		$form1['access'] = array(
		 		'#name' => 'access',
		 		'#type' => 'submit',
		 		'#value' => 'Invoke API call',
		 		'#attributes' => array(
		 			'class' => array(
		 				'btn','btn-primary','access-api'
		 			)
		 		),
		 	);
		 	$form1['markup'] = array(
		 		'#type' => 'markup',
		 		'#markup' => '  <div id="progress"></div><div id="message"></div>'
		 	);
		 	echo afl_render_form($form1);
	 	}

	 //if try access the url 
	 	if ( isset($_POST['access'])) {
	 		_remote_users_embedd_to_system();
	 	}
	}	
/*
 * --------------------------------------------------------------------------------------
 * Get remote user list and addds to the system
 * --------------------------------------------------------------------------------------
*/
 function _remote_users_embedd_to_system () {
 	$url = afl_variable_get('remote_user_get_url', '');
	// $res = file_get_contents($url);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  $user = json_decode($response);

	$count = count((array)$user);
	curl_close($ch);

 }