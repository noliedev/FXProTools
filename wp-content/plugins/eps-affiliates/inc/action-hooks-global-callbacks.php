<?php
/*
 * ---------------------------------------------------------
 * Commerce purchase complete
 * ---------------------------------------------------------
*/
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
	 		$response['error'][] 	= 'Affiliate point needs to be an integer number';
	 	}

	 	//details enter to the purchase table
	 	$ins = afl_purchase($args);

	 	//calculate rank
	 	do_action('eps_affiliates_calculate_affiliate_rank', $args['uid']);

		//calculte the rank of uplines matrix
		$refers_uids = afl_get_referrer_upline_uids($args['uid']);
		foreach ($refers_uids as $uid) {
			do_action('eps_affiliates_calculate_affiliate_rank', $uid);
		}

		//calculte the rank of uplines Unilevel
		$refers_uids = afl_unilevel_get_upline_uids($args['uid']);
		foreach ($refers_uids as $uid) {
			do_action('eps_affiliates_calculate_affiliate_rank', $uid);
		}

		$category = 'Product Purchase';
		if ( !empty($args['category'])) {
			$category = $args['category'];
		}
		//insert details into transactions table
		$afl_date_splits = afl_date_splits(afl_date());
	  $transaction = array();
    $transaction['uid'] 								= $args['uid'];
    $transaction['associated_user_id'] 	= $args['uid'];
    $transaction['payout_id'] 					= 0;
    $transaction['level']								= 0;
    $transaction['currency_code'] 			= afl_currency();
    $transaction['order_id'] 						= 1;
    $transaction['int_payout'] 					= 0;
    $transaction['hidden_transaction'] 	= 0;
    $transaction['credit_status'] 			= 0;
    $transaction['amount_paid'] 				= afl_commerce_amount($args['afl_point']);
    $transaction['category'] 						= $category;
    $transaction['notes'] 							= $category;
    $transaction['transaction_day'] 		= $afl_date_splits['d'];
    $transaction['transaction_month'] 	= $afl_date_splits['m'];
    $transaction['transaction_year'] 		= $afl_date_splits['y'];
    
    $transaction['transaction_week'] 		= $afl_date_splits['w'];
    $transaction['transaction_date'] 		= afl_date_combined($afl_date_splits);
    $transaction['created'] 						= afl_date();
	  //to mbr transaction
		// afl_member_transaction($transaction, TRUE);

	 	if (!$ins) {
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'Un-expected error occured. Unable to insert to the purchase details.';
	 	}
	 		return $response;
	}

/*
 * ---------------------------------------------------------
 * Joining package purchase complete
 * ---------------------------------------------------------
*/
	function eps_commerce_joining_package_purchase_complete($args = array()){
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
	 		//check associate user id exists or not
	 	if (empty($args['associated_uid']) ){
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'Associate user id cannot be null';
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
	 	// if (empty($args['afl_point'])) {
	 	// 	$response['status'] 	= 0;
	 	// 	$response['response']	=	'Failure';
	 	// 	$response['error'][] 	= 'Affiliate point cannot be null';
	 	// }

	 	//check user id field is an integer
	 	if (!empty($args['uid']) && !is_numeric($args['uid'])){
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'user id needs to be an integer number';
	 	}
	 	//check associate user id field is an integer
	 	if (!empty($args['associated_uid']) && !is_numeric($args['associated_uid'])){
	 		$response['status'] 	= 0;
	 		$response['response']	=	'Failure';
	 		$response['error'][] 	= 'associate user id needs to be an integer number';
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

 		// //check afl_point is integer
	 	// if (!empty($args['afl_point']) && !is_numeric($args['afl_point'])){
	 	// 	$response['status'] 	= 0;
	 	// 	$response['response']	=	'Failure';
	 	// 	$response['error'][] 	= 'Affiliate point needs to be an integer number';
	 	// }

	 	//details enter to the purchase table
	 	//$ins = afl_purchase($args);

	 	//calculate rank
	 	// do_action('eps_affiliates_calculate_affiliate_rank', $args['uid']);

		//calculte the rank of uplines
		$refers_uids = afl_get_referrer_upline_uids($args['uid']);
		foreach ($refers_uids as $uid) {
			do_action('eps_affiliates_calculate_affiliate_rank', $uid);
		}

		//insert details into transactions table
		$afl_date_splits = afl_date_splits(afl_date());
	  $transaction = array();
    $transaction['uid'] 								= $args['uid'];
    $transaction['associated_user_id'] 	= $args['associated_uid'];
    $transaction['currency_code'] 			= afl_currency();
    $transaction['order_id'] 						= 1;
    $transaction['int_payout'] 					= 0;
    $transaction['hidden_transaction'] 	= 0;
    $transaction['credit_status'] 			= 1;
    $transaction['amount_paid'] 				= afl_commerce_amount($args['amount_paid']);
    $transaction['category'] 						= 'Enrolment fee';
    $transaction['notes'] 							= 'Enrolment fee';
    $transaction['transaction_day'] 		= $afl_date_splits['d'];
    $transaction['transaction_month'] 	= $afl_date_splits['m'];
    $transaction['transaction_year'] 		= $afl_date_splits['y'];
    
    $transaction['transaction_week'] 		= $afl_date_splits['w'];
    $transaction['transaction_date'] 		= afl_date_combined($afl_date_splits);
    $transaction['created'] 						= afl_date();
    $transaction['additional_notes'] 		= 'Enrolment joining Fee';
	  //to mbr transaction
		afl_business_transaction($transaction);
		$ins = 1;
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
 function eps_affiliates_calculate_affiliate_rank_callback ($uid = '') {

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
	 			if (!_check_required_distributors_meets($uid, $i)) {
	 				continue;
	 			}

				//check the required other ranks
				if (!_check_required_qualifications_meets($uid, $i)) {
	 				continue;
	 			}

	 			//check the customer rule
	 			//1 leg group volume * 55 % = customer sales
	 			if (!_check_required_customer_rule($uid,$i)) {
	 				continue;
	 			}
				// pr ('Rank '. $i) ;
	 		// 	pr ('------------------------------------------------') ;
				// pr ('Rank '. $i) ;
				// pr ('PV : '._check_required_pv_meets( $uid, $i )) ;
				// pr ('GV : '._check_required_gv_meets( $uid, $i )) ;
				// pr ('DI : '._check_required_distributors_meets( $uid, $i )) ;
				// pr ('QU : '._check_required_qualifications_meets( $uid, $i )) ;
				// pr ('------------------------------------------------') ;
	 		/*
	 		 * ---------------------------------------------------------
	 		 * After the condition success, run the below codes
	 		 * ---------------------------------------------------------
	 		*/
	 			/*------- Update the genealogy rank --------------------*/
	 			$node = afl_genealogy_node($uid);
	 			$member_rank = $node->member_rank;
	 			$update_id = '';
	 			// pr($member_rank);
	 			if ( $member_rank <= $i) : 
		 			$update_id = $wpdb->update(
												$table_prefix.'afl_user_genealogy',
												array(
													'member_rank' => $i
												),
												array(
													'uid' => $uid
												)
											);
		 			$update_id = $wpdb->update(
									$table_prefix.'afl_unilevel_user_genealogy',
									array(
										'member_rank' => $i
									),
									array(
										'uid' => $uid
									)
								);
		 		endif;

				// pr ($i) ;
	 			$date_splits 	= afl_date_splits(afl_date());

	 			if ( $update_id ) {
					//update rank in user downlines
					$update_id = $wpdb->update(
							_table_name('afl_user_downlines'),
							array(
								'member_rank' => $i
							),
							array('downline_user_id' => $uid)
						);
					$update_id = $wpdb->update(
							_table_name('afl_unilevel_user_downlines'),
							array(
								'member_rank' => $i
							),
							array('downline_user_id' => $uid)
						);
				/*
		 		 * ---------------------------------------------------------
		 		 * Rank table update /  Insert
		 		 *
		 		 * Check the uid already exist then update
		 		 * else insert
		 		 * ---------------------------------------------------------
		 		*/
	 				$rank_table = _table_name('afl_ranks');
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
		 			$rank_history_table  = _table_name('afl_rank_history');
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


/*-------------------------------------------------------------------------------------------------------------*/
/*
 * ---------------------------------------------------------
 * Place a user into the holding tank
 * ---------------------------------------------------------
*/
 function eps_affiliates_place_user_in_holding_tank_callback ($uid = '', $sponsor = '') {
 	
 		global $wpdb;
 		$reg_obj = new Eps_affiliates_registration;
    //adds to the holding tank
    $reg_obj->afl_add_to_holding_tank(
    							array(
										'sponsor_uid' => $sponsor,
										'uid'					=> $uid,
									)
							);
 }
 /*
 * ---------------------------------------------------------
 * Place a user into the holding tank
 * ---------------------------------------------------------
*/
 function eps_affiliates_unilevel_place_user_in_holding_tank_callback ($uid = '', $sponsor = '') {
 	
 		global $wpdb;
 		$reg_obj = new Eps_affiliates_unilevel_registration;
    //adds to the holding tank
    $reg_obj->afl_add_to_holding_tank(
    							array(
										'sponsor_uid' => $sponsor,
										'uid'					=> $uid,
									)
							);
 }
/*-------------------------------------------------------------------------------------------------------------*/


/*-------------------------------------------------------------------------------------------------------------*/
/*
 * -------------------------------------------------------
 * Place a user under a sponsor 
 * -------------------------------------------------------
*/
	function eps_affiliates_place_user_under_sponsor_callback ($uid = '', $sponsor = '') {
		$reg_obj = new Eps_affiliates_registration;
		$reg_obj->afl_join_member(
									array(
										'sponsor_uid' => $sponsor,
										'uid'					=> $uid,
									)
							);

	}
/*
 * -------------------------------------------------------
 * Place a unilevel user under a sponsor 
 * -------------------------------------------------------
*/
	function eps_affiliates_unilevel_place_user_under_sponsor_callback ($uid = '', $sponsor = '') {
		$reg_obj = new Eps_affiliates_unilevel_registration;
		$reg_obj->afl_join_unilevel_member(
									array(
										'sponsor_uid' => $sponsor,
										'uid'					=> $uid,
									)
							);

	}
/*-------------------------------------------------------------------------------------------------------------*/


/*-------------------------------------------------------------------------------------------------------------*/

/*
 * ------------------------------------------------------
 * Place user under a sponsor if the tank validity expires
 * ------------------------------------------------------
*/
 function eps_affiliates_force_place_after_holding_expired_callback ($uid = '', $sponsor = '') {
 		global $wpdb;
 		$reg_obj = new Eps_affiliates_registration;
		$reg_obj->afl_join_member(
									array(
										'sponsor_uid' => $sponsor,
										'uid'					=> $uid
									)
							);
		//get the details from the holding tank
		$holding_data = _get_holding_tank_data($uid);
		$remote_user_mlm_id 		= '';
		$remote_sponsor_mlm_id 	= '';

		if ( $holding_data ) {
			if (!empty( $holding_data->remote_user_mlmid)) {
				$remote_user_mlm_id = $holding_data->remote_user_mlmid;
			}
			if (!empty( $holding_data->remote_sponsor_mlmid)) {
				$remote_sponsor_mlm_id = $holding_data->remote_sponsor_mlmid;
			}
			if (!empty( $holding_data->status)) {
				$status = $holding_data->status;
			}

			$wpdb->update(
				_table_name('afl_user_genealogy'),
				array(
					'remote_user_mlmid' 		=> $remote_user_mlm_id,
					'remote_sponsor_mlmid' 	=> $remote_sponsor_mlm_id,
					'status' 								=> $status,
				),
				array(
					'uid' => $uid
				)
			);
		}
		$wpdb->delete(_table_name('afl_user_holding_tank'), array('uid'=>$uid));
 }

/*
 * -----------------------------------------------------------------
 * Place unilevel user under a sponsor if the tank validity expires
 * -----------------------------------------------------------------
*/
	function eps_affiliates_unilevel_force_place_after_holding_expired_callback ($uid = '', $sponsor = '') {
 		global $wpdb;
 		$reg_obj = new Eps_affiliates_unilevel_registration;
		$reg_obj->afl_join_unilevel_member(
									array(
										'sponsor_uid' => $sponsor,
										'uid'					=> $uid
									)
							);
		//get the details from the holding tank
		$holding_data = _get_holding_tank_data($uid, 'unilevel');
		$remote_user_mlm_id 		= '';
		$remote_sponsor_mlm_id 	= '';

		if ( $holding_data ) {
			if (!empty( $holding_data->remote_user_mlmid)) {
				$remote_user_mlm_id = $holding_data->remote_user_mlmid;
			}
			if (!empty( $holding_data->remote_sponsor_mlmid)) {
				$remote_sponsor_mlm_id = $holding_data->remote_sponsor_mlmid;
			}
			if (!empty( $holding_data->status)) {
				$status = $holding_data->status;
			}

			$wpdb->update(
				_table_name('afl_unilevel_user_genealogy'),
				array(
					'remote_user_mlmid' 		=> $remote_user_mlm_id,
					'remote_sponsor_mlmid' 	=> $remote_sponsor_mlm_id,
					'status' 								=> $status,
				),
				array(
					'uid' => $uid
				)
			);
		}
		$wpdb->delete(_table_name('afl_unilevel_user_holding_tank'), array('uid'=>$uid));
 	}
/*-------------------------------------------------------------------------------------------------------------*/


/*
 * -------------------------------------------------
 *  Block a affiliate member
 *
 *
 * set the genealogy status = 0
 * set the last deactived on = current timestamb
 * -------------------------------------------------
*/
 	function eps_affiliates_block_member_callback ($uid = '') {
	 	if (!empty($uid)) {
	 		global $wpdb;
	 		//update genealogy ststus
	 		$update = $wpdb->update(
	 			_table_name('afl_user_genealogy'),
	 			array(
	 				'status' => 0,
	 				'deactived_on' => afl_date()
	 			),
	 			array(
	 				'uid' => $uid
	 			)
	 		);
	 	 	if ( $update ) {
	 	 		return true;
	 	 	} else {
	 	 		return false;
	 	 	}
	 	}
 	}
/*
 * -------------------------------------------------
 *  UNBlock a affiliate member
 *
 *
 * set the genealogy status = 1
 * set the last actived on = current timestamb
 * -------------------------------------------------
*/
 	function eps_affiliates_unblock_member_callback ($uid = '') {
	 	if (!empty($uid)) {
	 		global $wpdb;
	 		$update = $wpdb->update(
	 			_table_name('afl_user_genealogy'),
	 			array(
	 				'status' => 1,
	 				'actived_on' => afl_date()
	 			),
	 			array(
	 				'uid' => $uid
	 			)
	 		);

	 	 	if ( $update ) {
	 	 		return true;
	 	 	} else {
	 	 		return false;
	 	 	}
	 	}
 	}



/*
 * -------------------------------------------------------
 * Place a customer under a sponsor 
 * -------------------------------------------------------
*/
	function eps_affiliates_place_customer_under_sponsor_callback ($uid = '', $sponsor = '') {
		$reg_obj = new Eps_affiliates_customer_registration;
		$reg_obj->afl_join_customer(
									array(
										'sponsor_uid' => $sponsor,
										'uid'					=> $uid,
									)
							);
	}


/*
 * -------------------------------------------------------
 * Become distributor from customer
 * -------------------------------------------------------
*/
	function eps_affiliates_become_distributor_from_customer_callback ($customer_uid = '') {
		global $wpdb;
		$reg_obj = new Eps_affiliates_registration;
		//get current sponsor of customer
		$sponsor = _get_current_customer_sponsor($customer_uid);
		if ( $sponsor ) {
			$reg_obj->afl_join_member(
									array(
										'sponsor_uid' => $sponsor,
										'uid'					=> $customer_uid
									)
							);
			//delete customer from customer table
			$wpdb->delete(_table_name('afl_customer'), array('uid'=>$customer_uid));
			//remove user role afl_customer
			eps_add_role($customer_uid, 'afl_member');
			eps_remove_role($customer_uid, 'afl_customer');
		}
	}


/*
 * -----------------------------------------------------
 * get the user ewallet balance amount
 * -----------------------------------------------------
*/
 	function afl_user_e_wallet_balance_callback($uid = ''){
 		if ( empty($uid))
 			$uid = get_uid();

 		$query = array();
		$query['#select'] = _table_name('afl_user_transactions');
		$query['#where'] = array(
			'uid = '.$uid,
			// 'credit_status = 1',
			'deleted = 0',
			'hidden_transaction = 0'
		);
		$query['#expression'] = array(
			'SUM(balance) as total'
		);

		$resp = db_select($query, 'get_row');
		return !empty($resp->total) ? afl_format_payment_amount($resp->total, TRUE) : 0;
 	}
/*
 * -----------------------------------------------------
 * get the user ewallet transaction complete
 * -----------------------------------------------------
*/
 	function afl_withdrawal_completed_callback($args = array()){
 			//need to save the details to purchases
		 	$response = array();

		 	$response['status'] 	= 1;
		 	$response['response'] = 'success';
		 	
		 	$required_fields = array(
		 		'uid','associated_uid','order_id','amount_paid',
		 		// 'credit_status',
		 		//'category',
		 		'transaction_date'
		 	);
		 	
		 	$required_error_messages = array(
		 		'uid' 						=> __('user id cannot be null', 'eps-affiliates'),
		 		'associated_uid' 	=> __('Associate user id cannot be null', 'eps-affiliates'),
		 		'order_id' 				=> __('order id cannot be null', 'eps-affiliates'),
		 		'amount_paid' 		=> __('amount paid cannot be null', 'eps-affiliates'),
		 		'credit_status' 	=> __('Credit status  cannot be null', 'eps-affiliates'),
		 		// 'category' 				=> __('Transaction category cannot be null', 'eps-affiliates'),
		 		'transaction_date'=> __('Transaction date cannot be null', 'eps-affiliates'),
		 	);

		 	foreach ($required_fields as $field) {
		 		if (empty($args[$field]) ){
			 		$response['status'] 	= 0;
			 		$response['response']	=	'Failure';
			 		$response['error'][$field] 	= $required_error_messages[$field];
			 	}
		 	}

		 	if ( $response['status'] 	== 0 ) {
		 		return $response;
		 	}

		 	$number_validation = array(
		 		'uid','associated_uid','order_id','amount_paid',
		 		// 'credit_status',
		 		'transaction_date'
		 	);
		 	
		 	$number_validation_error_messages = array(
		 		'uid' 						=> __('user id needs to be an integer number', 'eps-affiliates'),
		 		'associated_uid' 	=> __('Associate user id needs to be an integer number', 'eps-affiliates'),
		 		'order_id' 				=> __('order id needs to be an integer number', 'eps-affiliates'),
		 		'amount_paid' 		=> __('amount paid needs to be an integer number', 'eps-affiliates'),
		 		// 'credit_status' 	=> __('Credit status  needs to be an integer number', 'eps-affiliates'),
		 		'transaction_date'=> __('Transaction date needs to be an integer number', 'eps-affiliates'),
		 	);

		 	foreach ($number_validation as $field) {
		 		if (!empty($args[$field]) && !is_numeric($args[$field])){
			 		$response['status'] 	= 0;
			 		$response['response']	=	'Failure';
			 		$response['error'][$field] 	= $number_validation_error_messages[$field];
			 	}
		 	}

		 	if ( $response['status'] 	== 0 ) {
		 		return $response;
		 	}
			$afl_date_splits = afl_date_splits(afl_date());

		 	$transaction = array();
	    $transaction['uid'] 								= $args['uid'];
	    $transaction['associated_user_id'] 	= $args['associated_uid'];
	    $transaction['payout_id'] 					= 0;
	    $transaction['level']								= 0;
	    $transaction['currency_code'] 			= afl_currency();
	    $transaction['order_id'] 						= $args['order_id'];
	    $transaction['int_payout'] 					= 0;
	    $transaction['hidden_transaction'] 	= 0;
	    // $transaction['credit_status'] 			= $args['credit_status'];
	    $transaction['credit_status'] 			= 0;
	    $transaction['amount_paid'] 				= afl_commerce_amount($args['amount_paid']);
	    // $transaction['category'] 						= $args['category'];
	    // $transaction['notes'] 							= empty($args['notes']) ? $args['category'] : $args['notes'];

	    $transaction['category'] 						= 'WITHDRAWAL';
	    $transaction['notes'] 							= 'Wallet amount withdraw';
	    
	    $transaction['transaction_day'] 		= empty($args['transaction_day']) 	? $afl_date_splits['d'] : $args['transaction_day'];
	    $transaction['transaction_month'] 	= empty($args['transaction_month']) ? $afl_date_splits['m'] : $args['transaction_month'];
	    $transaction['transaction_year'] 		= empty($args['transaction_year']) 	? $afl_date_splits['y'] : $args['transaction_year'];
	    
	    $transaction['transaction_week'] 		= empty($args['transaction_week']) 	? $afl_date_splits['w'] : $args['transaction_week'];
	    $transaction['transaction_date'] 		= afl_date_combined(array(
	    																				'y' => $transaction['transaction_year'],
	    																				'm' => $transaction['transaction_month'],
	    																				'd' => $transaction['transaction_day']
	    																			));
	    $transaction['created'] 						= $args['transaction_date'];
	   	//insert to transactions
	    afl_member_transaction($transaction);

	    return $response;
 	}
/*
 * -----------------------------------------------------
 * get the business ewallet transaction complete
 * -----------------------------------------------------
*/
 	function afl_withdrawal_fee_credited_callback($args = array()){
 			//need to save the details to purchases
		 	$response = array();

		 	$response['status'] 	= 1;
		 	$response['response'] = 'success';
		 	
		 	$required_fields = array(
		 		'uid','associated_uid','order_id','amount_paid',
		 		//'credit_status',
		 		// 'category',
		 		'transaction_date'
		 	);
		 	
		 	$required_error_messages = array(
		 		'uid' 						=> __('user id cannot be null', 'eps-affiliates'),
		 		'associated_uid' 	=> __('Associate user id cannot be null', 'eps-affiliates'),
		 		'order_id' 				=> __('order id cannot be null', 'eps-affiliates'),
		 		'amount_paid' 		=> __('amount paid cannot be null', 'eps-affiliates'),
		 		'credit_status' 	=> __('Credit status  cannot be null', 'eps-affiliates'),
		 		'category' 				=> __('Transaction category cannot be null', 'eps-affiliates'),
		 		'transaction_date'=> __('Transaction date cannot be null', 'eps-affiliates'),
		 	);

		 	foreach ($required_fields as $field) {
		 		if (empty($args[$field]) ){
			 		$response['status'] 	= 0;
			 		$response['response']	=	'Failure';
			 		$response['error'][$field] 	= $required_error_messages[$field];
			 	}
		 	}

		 	if ( $response['status'] 	== 0 ) {
		 		return $response;
		 	}

		 	$number_validation = array(
		 		'uid','associated_uid','order_id','amount_paid',
		 		// 'credit_status',
		 		'transaction_date'
		 	);
		 	
		 	$number_validation_error_messages = array(
		 		'uid' 						=> __('user id needs to be an integer number', 'eps-affiliates'),
		 		'associated_uid' 	=> __('Associate user id needs to be an integer number', 'eps-affiliates'),
		 		'order_id' 				=> __('order id needs to be an integer number', 'eps-affiliates'),
		 		'amount_paid' 		=> __('amount paid needs to be an integer number', 'eps-affiliates'),
		 		'credit_status' 	=> __('Credit status  needs to be an integer number', 'eps-affiliates'),
		 		'transaction_date'=> __('Transaction date needs to be an integer number', 'eps-affiliates'),
		 	);

		 	foreach ($number_validation as $field) {
		 		if (!empty($args[$field]) && !is_numeric($args[$field])){
			 		$response['status'] 	= 0;
			 		$response['response']	=	'Failure';
			 		$response['error'][$field] 	= $number_validation_error_messages[$field];
			 	}
		 	}

		 	if ( $response['status'] 	== 0 ) {
		 		return $response;
		 	}
			$afl_date_splits = afl_date_splits(afl_date());

		 	$transaction = array();
	    $transaction['uid'] 								= $args['uid'];
	    $transaction['associated_user_id'] 	= $args['associated_uid'];
	    $transaction['payout_id'] 					= 0;
	    $transaction['level']								= 0;
	    $transaction['currency_code'] 			= afl_currency();
	    $transaction['order_id'] 						= $args['order_id'];
	    $transaction['int_payout'] 					= 0;
	    $transaction['hidden_transaction'] 	= 0;
	    // $transaction['credit_status'] 			= $args['credit_status'];
	    $transaction['credit_status'] 			= 1;
	    $transaction['amount_paid'] 				= afl_commerce_amount($args['amount_paid']);
	    // $transaction['category'] 						= $args['category'];
	    // $transaction['notes'] 							= empty($args['notes']) ? $args['category'] : $args['notes'];

	    $transaction['category'] 						= 'WITHDRAWAL FEE';
	    $transaction['notes'] 							= 'withdrawal fee';
	    
	    $transaction['transaction_day'] 		= empty($args['transaction_day']) 	? $afl_date_splits['d'] : $args['transaction_day'];
	    $transaction['transaction_month'] 	= empty($args['transaction_month']) ? $afl_date_splits['m'] : $args['transaction_month'];
	    $transaction['transaction_year'] 		= empty($args['transaction_year']) 	? $afl_date_splits['y'] : $args['transaction_year'];
	    
	    $transaction['transaction_week'] 		= empty($args['transaction_week']) 	? $afl_date_splits['w'] : $args['transaction_week'];
	    $transaction['transaction_date'] 		= afl_date_combined(array(
	    																				'y' => $transaction['transaction_year'],
	    																				'm' => $transaction['transaction_month'],
	    																				'd' => $transaction['transaction_day']
	    																			));
	    $transaction['created'] 						= $args['transaction_date'];
	   	//insert to transactions
	    afl_business_transaction($transaction);

	    return $response;
 	}
 /*
  * ----------------------------------------------------
  * Give fast start bonus
  * ----------------------------------------------------
 */
  function afl_calculate_fast_start_bonus_callback($uid = '', $sponsor_id = ''){
		require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/plan/matrix/fast-start-bonus-calc.php';
	 	if ( function_exists('calculate_distributor_fast_start_bonus')) {
	 		calculate_distributor_fast_start_bonus( $sponsor_id, $uid );
	 	}
  }

/*
 * ----------------------------------------------------
 * Provide fastart bonus pv when achieve fsb
 * ----------------------------------------------------
*/
 function afl_calculate_fast_start_bonus_pv_callback ($uid = '') {
 	if ( !empty($uid)) {
 		if ( afl_variable_get('enable_fast_start_bonus')) {
 			if ( afl_variable_get('fast_start_bonus_pv')) {
 					$afl_date_splits = afl_date_splits(afl_date());
				  $transaction = array();
			    $transaction['uid'] 								= $uid;
			    $transaction['associated_user_id'] 	= $uid;
			    $transaction['currency_code'] 			= afl_currency();
			    $transaction['order_id'] 						= 1;
			    $transaction['int_payout'] 					= 0;
			    $transaction['hidden_transaction'] 	= 0;
			    $transaction['credit_status'] 			= 1;
			    $transaction['amount_paid'] 				= afl_commerce_amount(afl_variable_get('fast_start_bonus_pv'));
			    $transaction['afl_points'] 					= afl_commerce_amount(afl_variable_get('fast_start_bonus_pv'));
			    $transaction['category'] 						= 'FAST START BONUS PV';
			    $transaction['notes'] 							= 'FAST START BONUS PV';
			    $transaction['transaction_day'] 		= $afl_date_splits['d'];
			    $transaction['transaction_month'] 	= $afl_date_splits['m'];
			    $transaction['transaction_year'] 		= $afl_date_splits['y'];
			    
			    $transaction['transaction_week'] 		= $afl_date_splits['w'];
			    $transaction['transaction_date'] 		= afl_date_combined($afl_date_splits);
			    $transaction['created'] 						= afl_date();
			    $transaction['additional_notes'] 		= 'Fast start bonus pv';
				  //to mbr transaction
					$res = apply_filters('eps_commerce_purchase_complete',$transaction);
 			}
 		}
 	}
 }
/*
 * ----------------------------------------------------
 * Rank holding days template
 * ----------------------------------------------------
*/
 	function afl_rank_holding_days_template_callback ($uid = '') {
 		$days = _user_current_rank_holding_days($uid);
 		return afl_get_template( 'eps-affiliate-holding-rank-days.php', array( 'days' => $days ) );
 	}
/*
 * ----------------------------------------------------
 * my customers total count
 * ----------------------------------------------------
*/
	function afl_my_customers_count_callback ($uid = '') {
		return count(_my_customers_uids($uid));
	}
/*
 * ----------------------------------------------------
 * my distributors total count
 * ----------------------------------------------------
*/
	function afl_my_distributors_count_callback ($uid = '') {
		return _get_user_distributor_count($uid);
	}
/*
 * ----------------------------------------------------
 * customers count template
 * ----------------------------------------------------
*/
	function afl_my_customers_count_template_callback () {
		return afl_get_template( 'plan/eps-affiliate-customer-count-template.php');
	}
/*
 * ----------------------------------------------------
 * distributor count template
 * ----------------------------------------------------
*/
	function afl_my_distributors_count_template_callback () {
		return afl_get_template( 'plan/eps-affiliate-distributor-count-template.php');
	}



/**
 | ---------------------------------------------------------------------------------------------------
 | All the ewallet summary blocks actions and filters
 | --------------------------------------------------------------------------------------------------
 | 
 | 
 | 
*/

/*
 * ---------------------------------------------------
 * E-walet today earnings
 * ---------------------------------------------------
*/
	function afl_ewallet_today_earnings_callback ($uid = '') {
		if (empty($uid)) {
			$uid = get_uid();
		}

		$afl_date = afl_date();
		$query = array();
		$query['#select'] = _table_name('afl_user_transactions');
		$query['#where'] = array(
			'uid = '.$uid,
			// 'credit_status = 1',
			'deleted = 0',
			'hidden_transaction = 0'
		);
		$query['#where'] = array(
			'created='.$afl_date
		);
		$query['#expression'] = array(
			'SUM(balance) as total'
		);

		$resp = db_select($query, 'get_row');
		return !empty($resp->total) ? afl_format_payment_amount($resp->total, TRUE) : 0;;
	}
/*
 * --------------------------------------------------
 * E-walet today earnings template
 * --------------------------------------------------
*/
	function afl_ewallet_today_earnings_template_callback () {
		return afl_get_template( 'eps-affiliates/e-wallet/eps-affiliate-e-wallet-today-template.php');
	}

/*
 * ---------------------------------------------------
 * E-walet yesterday earnings
 * ---------------------------------------------------
*/
	function afl_ewallet_yesterday_earnings_callback ($uid = '') {
		if (empty($uid)) {
			$uid = get_uid();
		}

		$afl_date 	= afl_date();
		$yesterday 	=  strtotime('-1 day',$afl_date);

		$query = array();
		$query['#select'] = _table_name('afl_user_transactions');
		$query['#where'] = array(
			'uid = '.$uid,
			// 'credit_status = 1',
			'deleted = 0',
			'hidden_transaction = 0'
		);
		$query['#where'] = array(
			'created='.$yesterday
		);
		$query['#expression'] = array(
			'SUM(balance) as total'
		);

		$resp = db_select($query, 'get_row');
		return !empty($resp->total) ? afl_format_payment_amount($resp->total, TRUE) : 0;;
	}
/*
 * --------------------------------------------------
 * E-walet yesterday earnings template
 * --------------------------------------------------
*/
	function afl_ewallet_yesterday_earnings_template_callback () {
		return afl_get_template( 'eps-affiliates/e-wallet/eps-affiliate-e-wallet-yesterday-template.php');
	}

/*
 * ---------------------------------------------------
 * E-walet last week earnings
 * ---------------------------------------------------
*/
	function afl_ewallet_last_week_earnings_callback ($uid = '') {
		if (empty($uid)) {
			$uid = get_uid();
		}

		$afl_date 	= afl_date();
		$last_week_date 	= strtotime('-1 week',$afl_date);
		$afl_date_splits 	= afl_date_splits($last_week_date);		

		$query = array();
		$query['#select'] = _table_name('afl_user_transactions');
		$query['#where'] = array(
			'uid = '.$uid,
			// 'credit_status = 1',
			'deleted = 0',
			'hidden_transaction = 0'
		);
		$query['#where'] = array(
			'transaction_month='.$afl_date_splits['m'],
			'transaction_year='.$afl_date_splits['y'],
			'transaction_week='.$afl_date_splits['w'],
		);
		$query['#expression'] = array(
			'SUM(balance) as total'
		);

		$resp = db_select($query, 'get_row');
		return !empty($resp->total) ? afl_format_payment_amount($resp->total, TRUE) : 0;;
	}
/*
 * --------------------------------------------------
 * E-walet last week earnings template
 * --------------------------------------------------
*/
	function afl_ewallet_last_week_earnings_template_callback () {
		return afl_get_template( 'eps-affiliates/e-wallet/eps-affiliate-e-wallet-last-week-template.php');
	}
/*
 * ---------------------------------------------------
 * E-walet last month earnings
 * ---------------------------------------------------
*/
	function afl_ewallet_last_month_earnings_callback ($uid = '') {
		if (empty($uid)) {
			$uid = get_uid();
		}

		$afl_date 	= afl_date();
		$last_mnth 	= strtotime('-1 month',$afl_date);
		$afl_date_splits = afl_date_splits($last_mnth);		
		// pr(date('Y-m-d',$afl_date));
		// pr(date('Y-m-d',$last_mnth));
		$query = array();
		$query['#select'] = _table_name('afl_user_transactions');
		$query['#where'] = array(
			'uid = '.$uid,
			// 'credit_status = 1',
			'deleted = 0',
			'hidden_transaction = 0'
		);
		$query['#where'] = array(
			'transaction_month='.$afl_date_splits['m'],
			'transaction_year='.$afl_date_splits['y'],
		);
		$query['#expression'] = array(
			'SUM(balance) as total'
		);

		$resp = db_select($query, 'get_row');
		return !empty($resp->total) ? afl_format_payment_amount($resp->total, TRUE) : 0;;
	}
/*
 * --------------------------------------------------
 * E-walet last month earnings template
 * --------------------------------------------------
*/
	function afl_ewallet_last_month_earnings_template_callback () {
		return afl_get_template( 'eps-affiliates/e-wallet/eps-affiliate-e-wallet-last-month-template.php');
	}


/*
 * ---------------------------------------------------
 * E-walet all time earnings
 * ---------------------------------------------------
*/
	function afl_ewallet_all_time_earnings_callback ($uid = '') {
		if (empty($uid)) {
			$uid = get_uid();
		}

		$afl_date 	= afl_date();
		$last_mnth 	= strtotime('-1 month',$afl_date);
		$afl_date_splits = afl_date_splits($last_mnth);		
		// pr(date('Y-m-d',$afl_date));
		// pr(date('Y-m-d',$last_mnth));
		$query = array();
		$query['#select'] = _table_name('afl_user_transactions');
		$query['#where'] = array(
			'uid = '.$uid,
			// 'credit_status = 1',
			'deleted = 0',
			'hidden_transaction = 0'
		);
		// $query['#where'] = array(
		// 	'transaction_month='.$afl_date_splits['m'],
		// 	'transaction_year='.$afl_date_splits['y'],
		// );
		$query['#expression'] = array(
			'SUM(balance) as total'
		);

		$resp = db_select($query, 'get_row');
		return !empty($resp->total) ? afl_format_payment_amount($resp->total, TRUE) : 0;;
	}
/*
 * --------------------------------------------------
 * E-walet all time earnings template
 * --------------------------------------------------
*/
	function afl_ewallet_all_time_earnings_template_callback () {
		return afl_get_template( 'eps-affiliates/e-wallet/eps-affiliate-e-wallet-all-time-template.php');
	}
/*
 * --------------------------------------------------
 * E-wallet-summary all blocks
 * --------------------------------------------------
*/
	function afl_ewallet_all_earnings_summary_blocks_template_callback () {
		return afl_get_template( 'eps-affiliates/e-wallet/eps-affiliate-e-wallet-all-summary-blocks.php');
	}
/*
 * --------------------------------------------------
 * Team volume
 * --------------------------------------------------
*/
	function afl_distributor_team_volume_callback ($uid = '') {
		$my_pv   = _get_user_pv($uid);
 		$legs_gv = _get_user_direct_legs_gv($uid);

 		//get maximum group volume required for this rank
 		// $max_taken 			= afl_variable_get('rank_'.$rank.'_max_gv_taken_1_leg',0);
 		// $maximum_taken 	= afl_commission($max_taken,$required_gv);

 		$user_gv = 0;
 		foreach ($legs_gv as $key => $amount) {
 			// $user_gv += ($amount > $maximum_taken) ? $maximum_taken : $amount;
 			$user_gv += $amount;
 		}
 		$user_gv = $my_pv + $user_gv;
 		return $user_gv;
	}
/*
 * --------------------------------------------------
 * PErsonal Volume
 * --------------------------------------------------
*/
	function afl_distributor_personal_volume_callback ($uid = '') {
		$my_pv   = _get_user_pv($uid);
		return $my_pv;
	}