<?php

/*
 * ------------------------------------------------------------
 * invoke get user and add to our system api call
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_api_embedd_remote_user', 'api_embedd_remote_user_callback');
 add_action('wp_ajax_nopriv_api_embedd_remote_user', 'api_embedd_remote_user_callback');

	function api_embedd_remote_user_callback () {

		// Start the session.
		session_start();
		// The example total processes.
		$url 	= afl_variable_get('remote_user_get_url', '');
		$url 	= 'http://localhost/test.php';
		// $res = file_get_contents($url);
	  $ch 	= curl_init($url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  $response = curl_exec($ch);
	  $user 		= json_decode($response);

		$count 		= count((array)$user);
		curl_close($ch);
		
		//Get the list count of api users
		$total = 5;



		// The array for storing the progress.
		$arr_content = array();
		// Loop through process
		for($i=1; $i<=$total; $i++){
		  // Calculate the percentatage.
		  $percent = intval($i/$total * 100);
		  
	  	// Put the progress percentage and message to array.
		  $arr_content['percent'] = $percent;
		  $arr_content['message'] = $i . " row(s) processed.";
		  

		// Write the progress into file and serialize the PHP array into JSON format.
		  // The file name is the session id.
		  file_put_contents(EPSAFFILIATE_PLUGIN_DIR."/inc/API/tmp/". session_id() . ".txt", json_encode($arr_content));
		  

		// Sleep one second so we can see the delay
		  sleep(1);
		}
		die();
	}
/*
 * ------------------------------------------------------------
 * invoke get user and add to our system api call
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_api_embedd_remote_user_refresh', 'api_embedd_remote_user_refresh_callback');
 add_action('wp_ajax_nopriv_api_embedd_remote_user_refresh', 'api_embedd_remote_user_refresh_callback');

 function api_embedd_remote_user_refresh_callback () {
 	// Start the session.
		session_start();
 	// The file has JSON type.
	header('Content-Type: application/json');
	// Prepare the file name from the query string.
	// Don't use session_start here. Otherwise this file will be only executed after the process.php execution is done.
	$file = str_replace(".", "", session_id());
	$file = EPSAFFILIATE_PLUGIN_DIR."inc/API/tmp/" . session_id() . ".txt";
	
	// Make sure the file is exist.
	if (file_exists($file)) {
	  // Get the content and echo it.
	  $text = file_get_contents($file);
	  echo $text;
	  

	// Convert to JSON to read the status.
	  $obj = json_decode($text);
	  // If the process is finished, delete the file.
	  if ($obj->percent == 100) {
	    unlink($file);
	  }
	}
	else {
	  echo json_encode(array("percent" => null, "message" => null,"file"=>$file));
	}
	die();
 }

/*
 * ------------------------------------------------------------
 * Get the users API
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_api_embedd_remote_user_access', 'api_embedd_remote_user_access_callback');
	add_action('wp_ajax_nopriv_api_embedd_remote_user_access', 'api_embedd_remote_user_access_callback');
	function api_embedd_remote_user_access_callback () {
	 		$url 	= afl_variable_get('remote_user_get_url', '');
			// $res = file_get_contents($url);
		  $ch 	= curl_init($url);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  $response = curl_exec($ch);
			$count 		= count((array)json_decode($response));
			curl_close($ch);
			if ($response) {
				echo json_encode(array(
					'users' => $response,
					'count'	=> $count
				));
			}
			die();
	} 
/*
 * ------------------------------------------------------------
 * Add user to the system
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_api_embedd_remote_user_to_system', 'api_embedd_remote_user_to_system_callback');
	add_action('wp_ajax_nopriv_api_embedd_remote_user_to_system', 'api_embedd_remote_user_to_system_callback');
	function api_embedd_remote_user_to_system_callback () {
	 	// file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/log1.txt', json_encode($_POST['data']).PHP_EOL,FILE_APPEND);
		$response = 0;

	 	//create new user to the database if not existed
	 	 $_POST = $_POST['data'];
	 	 if ( isset( $_POST['email'] ) ) {
			if ( !email_exists( $_POST['email'] )) {
				if ( isset($_POST['name']) ) {
					if ( !username_exists( $_POST['name'] ) ) {
					/*
	       	 * ------------------------------------------------------------------- 
	       	 * check remote sponsor mlmid user exist
	       	 * Creat user if exists
	       	 * ------------------------------------------------------------------- 
	       	*/
						$sponsor = _check_remote_sponsor_mlmid_exist($_POST['sponsor_mlmid']);
						if ( $sponsor ) {
							$userdata = array(
			        'user_login'    	=>   $_POST['name'],
			        'user_email'    	=>   $_POST['email'],
			        'user_pass'     	=>   '123456',
			        'first_name'    	=>   $_POST['name'],
			       );
			       $user = wp_insert_user( $userdata );
			       
			    /*
	       	 * ------------------------------------------------------------------- 
	       	 * Place the user under sponsor
	       	 * ------------------------------------------------------------------- 
	       	*/
						 if ( $user ) {
			       	do_action('eps_affiliates_place_user_under_sponsor',$user, $sponsor);
		       	/*
		       	 * ------------------------------------------------------------------- 
		       	 * set this user remote_user_mlmid && remote_sponsor_mlmid field
		       	 * ------------------------------------------------------------------- 
		       	*/
							global $wpdb;
			       	$wpdb->update(
									_table_name('afl_user_genealogy'),
									array(
										'remote_user_mlmid' 	 => $_POST['userMlmId'],
										'remote_sponsor_mlmid' => $_POST['sponsor_mlmid']
									),
									array('uid' => $user)
								);
			       	$response = 1;
						 } else {
						 	file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/notinserted.txt', json_encode($_POST).PHP_EOL,FILE_APPEND);
						 }
						} else {
							// file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/sponsor_not_exist.txt', json_encode($_POST).PHP_EOL,FILE_APPEND);

							//these users moves to holding tank
							$userdata = array(
				        'user_login'    	=>   $_POST['name'],
				        'user_email'    	=>   $_POST['email'],
				        'user_pass'     	=>   '123456',
				        'first_name'    	=>   $_POST['name']
				      );
				      $user = wp_insert_user( $userdata );

							do_action('eps_affiliates_place_user_in_holding_tank',$user,afl_root_user());
							//update the holding users remote user mlmid and remote sponsor mlm id

							global $wpdb;
			       	$wpdb->update(
									_table_name('afl_user_holding_tank'),
									array(
										'remote_user_mlmid' 	 => $_POST['userMlmId'],
										'remote_sponsor_mlmid' => $_POST['sponsor_mlmid']
									),
									array('uid' => $user)
								);
						}
					} else {
						file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/user_name_exists.txt', json_encode($_POST).PHP_EOL,FILE_APPEND);
					}
				}
			} else {
				file_put_contents(EPSAFFILIATE_PLUGIN_DIR.'inc/API/tmp/email_exists.txt', json_encode($_POST).PHP_EOL,FILE_APPEND);
			}
	 	 }
	 	
	 	// if ( ! class_exists( 'WP_Userembedd_Process', false ) ) {
	  // 	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/API/eps-remote-users-background-process.php';
	  // }

	  // $process_all = new WP_Userembedd_Process();
	  // $process_all->push_to_queue( $_POST['data'] );
	  // $process_all->save()->dispatch();
	  // echo "success"; //return something
	 	echo $response;
	  wp_die();
 	}

/*
 * ------------------------------------------------------------
 * upload the user details to queue for processing
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_api_upload_users_to_queue', 'api_upload_users_to_queue_callback');
 add_action('wp_ajax_nopriv_api_upload_users_to_queue', 'api_upload_users_to_queue_callback');
 function api_upload_users_to_queue_callback () {

 	if (!empty($_POST['data'])) {
	 	$data = $_POST['data'];
	 	$uniqu_id   = $_POST['id'];
	 	$exists = _check_queue_already_add(array('uid'=> $uniqu_id,'name'=>'"remote_users_embedd"'));
	 	if (!$exists) {
	 		$queue_data = array();
		 	$queue_data['name'] 			= 'remote_users_embedd';
		 	$queue_data['uid'] 				= $uniqu_id;
		 	$queue_data['title'] 			= 'remote_users_embedd';
		 	$queue_data['data']  			= maybe_serialize($data);
		 	$queue_data['expire'] 		= afl_date();
		 	$queue_data['status'] 		= -1;
		 	$queue_data['created'] 		= afl_date();
		 	$queue_data['processed'] 	= afl_date();
		 	$queue_data['runs'] 			= 0;
		 	
		 	global $wpdb;
		 	$wpdb->insert(
		 		_table_name('afl_processing_queue'),
		 		$queue_data
		 	);
	 	}
	 	
 	}
 }

 function _check_queue_already_add ($data = array()	) {
 	$query = array();
 	$query['#select'] = _table_name('afl_processing_queue');
 	$query['#where'] = array(

 	);

 	foreach ($data as $key => $value) {
 		$query['#where'][] = ''.$key.'='.$value.'';
 	}
 	$res = db_select($query, 'get_row');
 	if (!empty($res)) {
 		return true;
 	} else {
 		return false;
 	}
 }

 