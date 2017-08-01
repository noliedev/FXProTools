<?php 
/*
 * ------------------------------------------------------
 * After the completion of checkout
 * ------------------------------------------------------
*/
	function eps_commerce_checkout_complete( $order_id ){
		afl_purchase($order_id);	
	}




function eps_commerce_purchase_complete($args = array()){
	 	//need to save the details to purchases
	 	$response = array();

	 	$response['status'] 	= 1;
	 	$response['response'] = 'success';

	 	//check user id exists or not
	 	if (empty($args['uid']) ){
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'user id cannot be null';
	 	}

	 	//check order_id exists
	 	if (empty($args['order_id'])) {
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'order id cannot be null';
	 	}

	 	//check amount paid exists or not
	 	if (empty($args['amount_paid'])) {
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'amount cannot be null';
	 	}

	 		//check afl_point exists or not
	 	if (empty($args['afl_point'])) {
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'Affiliate point cannot be null';
	 	}

	 	//check user id field is an integer
	 	if (!empty($args['uid']) && !is_numeric($args['uid'])){
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'user id needs to be an integer number';
	 	}

	 	//check order_id is integer
	 	if (!empty($args['order_id']) && !is_numeric($args['order_id'])){
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'Order id needs to be an integer number';
	 	}

	 	//check order_id is integer
	 	if (!empty($args['amount_paid']) && !is_numeric($args['amount_paid'])){
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'Amount needs to be an integer number';
	 	}

 		//check afl_point is integer
	 	if (!empty($args['afl_point']) && !is_numeric($args['afl_point'])){
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'Amount needs to be an integer number';
	 	}

	 	//details enter to the purchase table
	 	$ins = afl_purchase($args);


	 	if (!$ins) {
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'Un-expected error occured. Unable to insert to the purchase details.';
	 	}
	 		return $response;
	 }
/*
 * ------------------------------------------------------
 * Calculate the affiliates rank
 * ------------------------------------------------------
*/
 function eps_affiliates_calculate_affiliate_rank ($uid = '') {
 	global $wpdb;
 	$table_prefix = $wpdb->prefix;
 	if (!empty($uid)) {
 		$max_rank = afl_variable_get('number_of_ranks');
 		$i = $max_rank;
 		for ( $i = $max_rank; $i > 0; $i--) {
 		
	 		/*
	 		 * ---------------------------------------------------------
	 		 * check the condition meets
	 		 * ---------------------------------------------------------
	 		*/
	 			//check pv
	 			if (!_check_required_pv_meets( $uid, $i ) ){
	 				continue;
	 			}
	 			//check gv
	 			if (!_check_required_gv_meets( $uid, $i ) ){
	 				continue;
	 			}
	 			//check no of distributors
	 			if (!_check_required_distributors_meets($uid)) {
	 				continue;
	 			}

	 		/*
	 		 * ---------------------------------------------------------
	 		 * After the condition success, run the below codes
	 		 * ---------------------------------------------------------
	 		*/
	 			/*------- Update the genealogy rank --------------------*/
	 			
	 			$update_id = $wpdb->update( 
											$table_prefix.'afl_user_genealogy', 
											array( 
												'member_rank' => $i 
											),
											array('uid' => $uid)				
										);

	 			$date_splits 	= afl_date_splits(afl_date());

	 			if ( $update_id ) {
				/*
		 		 * ---------------------------------------------------------
		 		 * Rank table update /  Insert
		 		 * 
		 		 * Check the uid already exist then update
		 		 * else insert
		 		 * ---------------------------------------------------------
		 		*/
	 				$rank_table = eps_table_name('afl_ranks');
			  	$query 			= 'SELECT * FROM '.$rank_table.' WHERE uid = %d';
	 				$row 				= $wpdb->get_row( 
	                    		$wpdb->prepare($query,$uid) 
		                 		);
	 				if ( empty($row) ){
	 					
	 					$rank_data 		= array();
	 					
	 					$rank_data['uid'] 				= $uid;
	 					$rank_data['member_rank'] = $i;
	 					$rank_data['updated'] 		= afl_date();
	 					$rank_data['rank_day'] 		= $date_splits['d'];
	 					$rank_data['rank_month'] 	= $date_splits['m'];
	 					$rank_data['rank_year'] 	= $date_splits['y'];
	 					$rank_data['rank_week'] 	= $date_splits['w'];
	 					$rank_data['rank_date'] 	= afl_date_combined($date_splits);

	 					$wpdb->insert($rank_table, $rank_data);

	 				} else {
	 					$update_id = $wpdb->update( 
											$rank_table, 
											array( 
												'member_rank' => $i,
												'updated' 		=> afl_date() 
											),
											array('uid' => $uid)				
										);	
	 				}
				/*
		 		 * ---------------------------------------------------------
		 		 * Rank history table Insert
		 		 * ---------------------------------------------------------
		 		*/
		 			$rank_history_table  = eps_table_name('afl_rank_history');
	 				$rank_history_data 		= array();
	 					
					$rank_history_data['uid'] 				= $uid;
					$rank_history_data['member_rank'] = $i;
					$rank_history_data['updated'] 		= afl_date();
					$rank_history_data['rank_day'] 		= $date_splits['d'];
					$rank_history_data['rank_month'] 	= $date_splits['m'];
					$rank_history_data['rank_year'] 	= $date_splits['y'];
					$rank_history_data['rank_week'] 	= $date_splits['w'];
					$rank_history_data['rank_date'] 	= afl_date_combined($date_splits);

					$wpdb->insert($rank_history_table, $rank_history_data);
	 				break;
	 			}
 		}
 	}
 }
/*
 * ----------------------------------------------------
 * check required pv
 * ----------------------------------------------------
*/
 function _check_required_pv_meets ($uid = '', $rank = '') {
 		$user_pv = _get_user_pv($uid);
 		//check conidition meets
 		$required_pv = afl_variable_get('rank_'.$rank.'_pv',0);
 		if ($required_pv >= $user_pv  ){
 			return true;
 		} else {
 			return false;
 		}
 }
/*
 * ----------------------------------------------------
 * check required pv
 * ----------------------------------------------------
*/
 function _check_required_gv_meets ($uid = '', $rank = '') {
 		$user_gv = _get_user_gv($uid);
 		//check conidition meets
 		$required_gv = afl_variable_get('rank_'.$rank.'_gv',0);
 		if ($required_gv >= $user_gv  ){
 			return true;
 		} else {
 			return false;
 		}
 }
 /*
 * ----------------------------------------------------
 * check required distributors
 * ----------------------------------------------------
*/
 function _check_required_distributors_meets ($uid = '', $rank = '') {
 		$user_distrib = _get_user_distributor_count($uid);
 		//check conidition meets
 		$required_distrib = afl_variable_get('rank_'.$rank.'no_of_distributors',0);
 		if ($required_distrib >= $user_distrib  ){
 			return true;
 		} else {
 			return false;
 		}
 }
/*
 * ---------------------------------------------------
 * get user pv
 * ---------------------------------------------------
*/
 function _get_user_pv ($uid = '') {
 	global $wpdb;
 	if (empty($uid)) 
 		$uid = afl_current_uid();

 	$table_prefix = $wpdb->prefix ? $wpdb->prefix : 'wp_';
 	$table 				= $table_prefix.'afl_purchases';

 	$query = array();
 	$query['#select'] = $table;
 	$query['#where'] 	= array(
 		'`'.$table.'`.`uid` = '.$uid
 	);
 	$query['#expression'] 	= array(
 		'SUM(`afl_points`) as sum'
 	);
 	$result = db_select($query, 'get_row');
 	$result = (array)$result;
 	if (isset($result['sum'])) {
 		return afl_get_commerce_amount($result['sum']);
 	} else {
 		return 0;
 	}
 }
/*
 * ---------------------------------------------------
 * get user pv
 * ---------------------------------------------------
*/
 function _get_user_gv ($uid = '') {
 	global $wpdb;
 	if (empty($uid)) 
 		$uid = afl_current_uid();

 	$table_prefix = $wpdb->prefix ? $wpdb->prefix : 'wp_';
 	$table 				= $table_prefix.'afl_purchases';
 	
 	//direct downlines
 	$downlines    = afl_get_user_downlines_uid($uid); 
 	$downlines_uid = array();
 	foreach ($downlines as $key =>$down_uid) {
 		$downlines_uid[] = $down_uid->uid;
 	}

 	$query = array();
 	$query['#select'] = $table;
 	$query['#where_in'] 	= array(
 		'uid' => $downlines_uid
 	);

 	$query['#expression'] 	= array(
 		'SUM(`afl_points`) as sum'
 	);
 	$result = db_select($query, 'get_row');
	$result = (array)$result;
	 	if (isset($result['sum'])) {
	 		return afl_get_commerce_amount($result['sum']);
	 	} else {
	 		return 0;
	 	}
 }
/*
 * -------------------------------------------------
 * No.of distributor count
 * -------------------------------------------------
*/
 function _get_user_distributor_count ($uid) {
 	$downlines    = afl_get_direct_user_downlines($uid, array(), TRUE); 
 	if ($downlines) {
 		return $downlines;
 	} else 
 		return 0;
 }
