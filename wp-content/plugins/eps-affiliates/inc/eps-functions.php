<?php
/*
 * ------------------------------------------------------
 * After the completion of checkout
 * ------------------------------------------------------
*/
	function eps_commerce_checkout_complete( $order_id ){
		afl_purchase($order_id);
	}
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
										'uid'					=> $uid
									)
							);
	}
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
		$wpdb->delete(_table_name('afl_user_holding_tank'), array('uid'=>$uid));
 }
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
										'uid'					=> $uid
									)
							);
 }
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
	 		$response['error'][] 	= 'Amount needs to be an integer number';
	 	}

	 	//details enter to the purchase table
	 	$ins = afl_purchase($args);

	 	//calculate rank
	 	do_action('eps_affiliates_calculate_affiliate_rank', $args['uid']);

		//calculte the rank of uplines
		$refers_uids = afl_get_referrer_upline_uids($args['uid']);
		foreach ($refers_uids as $uid) {
			do_action('eps_affiliates_calculate_affiliate_rank', $uid);
		}

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
/*
 * ----------------------------------------------------
 * check required pv
 * ----------------------------------------------------
*/
 function _check_required_pv_meets ($uid = '', $rank = '') {
 		$user_pv = _get_user_pv($uid);
 		//check conidition meets
 		$required_pv = afl_variable_get('rank_'.$rank.'_pv',0);

 		if ($required_pv <= $user_pv  ){
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
 		if ($required_gv <= $user_gv  ){
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
 		$required_distrib = afl_variable_get('rank_'.$rank.'_no_of_distributors',0);
 		// pr($rank);
 		// pr($required_distrib);
 		
 		if ($required_distrib <= $user_distrib  ){
 			return true;
 		} else {
 			return false;
 		}
 }
 /*
 * ----------------------------------------------------
 * check required qualifications
 * ----------------------------------------------------
 */
  function _check_required_qualifications_meets ($uid = '', $rank = '') {
		$below_rank = $rank - 1;
	  $meets_flag = 0;

	  if ( $below_rank > 0 ){
	    //loop through the below ranks qualifications exists or not
	    for ( $i = $below_rank; $i > 0; $i-- ) {
	      /*
	       * --------------------------------------------------------------
	       * get the required rank holders neede in one leg
	       * --------------------------------------------------------------
	      */

	        $required_in_one_count = afl_variable_get('rank_'.$rank.'_rank_'.$i.'_required_count', 0);


	      if ( $required_in_one_count ) {
	        /*
	         * --------------------------------------------------------------
	         * get the required count in how many legs
	         * --------------------------------------------------------------
	        */
	          $required_in_legs_count    = afl_variable_get('rank_'.$rank.'_rank_'.$i.'_required_in_legs ', 0);


	        //if in legs count specified
	        if ( $required_in_legs_count ) {
	          /*
	           * ---------------------------------------------------------------
	           * get the first level downlines of this user
	           * get count of the first level users having the rank
	           * if the rank users exists set the status as 1
	           * else unset status as 0
	           * this status adds to the condition_statuses array
	           *
	           * count the occurence of 0 and 1 in this array
	           *
	           * if the occurence of status is greater than or equals the count of
	           *  required in howmany legs count set the meets flag
	           * else unset
	           * ---------------------------------------------------------------
	          */


	          $downlines = afl_get_user_downlines_uid($uid, array('level'=>1), false);

	          $condition_statuses  = array();
	          //find the ranks ($i) of this downlines
	          foreach ($downlines as $key => $value) {
	              //get the downlines users downlines count having the rank $i
	              $down_downlines_count = afl_get_user_downlines_uid($value->downline_user_id, array('member_rank'=>$i),TRUE);
	              /*
	               * --------------------------------------------------
	               * Get the downlines count of members having the rank
	               * $i
	               * check the downline count meets the required count 
	               * in one leg
	               * if it meets set status as 1
	               * else set 0
	               * --------------------------------------------------
	              */
	              if ( $down_downlines_count >= $required_in_one_count )
	                $status = 1;
	              else
	                $status = 0;
	              $condition_statuses[] = $status;
	          }

	          //count the occurence of 1 and 0
	          $occurence = array_count_values($condition_statuses);
	          
	          //if the occurence of 1 is greater than or equals the count of legs needed it returns true
	          if ( isset($occurence[1])  && $occurence[1] >= $required_in_legs_count ){
	            $meets_flag = 1;
	          } else {
	            $meets_flag = 0;
	            break;
	          }

	        } else {
	          /*
	           * ---------------------------------------------------------------
	           * get the first level downlines of this user
	           * get count of the first level users downlines having the rank
	           * if the count meets required_count_in_leg set meets_flag
	           * else unset
	           * ---------------------------------------------------------------
	          */
	            $downlines = array();
	            $result = afl_get_user_downlines_uid($uid, array('level'=>1), false);
	            foreach ($result as $key => $value) {
	              $downlines[] = $value->downline_user_id;
	            }

	            $implodes = implode(',', $downlines);
	            //check the ranks under this users
	            $query = array();

	            $query['#select'] = _table_name('afl_user_downlines');
	            $query['#where'] = array(
	              '`'._table_name('afl_user_downlines').'`.`member_rank`='.$i,
	            );
	            if (!empty($implodes)) {
	              $query['#where'][] = '`'._table_name('afl_user_downlines').'`.`uid` IN ('.$implodes.')';
	            }

	            $query['#expression'] = array(
	              'COUNT(`'._table_name('afl_user_downlines').'`.`member_rank`) as count'
	            );
	            $result = db_select($query, 'get_row');
	            $rank_existed_count = $result->count;


              if ( $rank_existed_count >= $required_in_one_count ){
                $meets_flag = 1;
              } else {
                $meets_flag = 0;
                break;
              }
	        }
	      } else {
	        $meets_flag = 1;
	      }
	    }
	  } else {
			$meets_flag = 1;
		}

		return $meets_flag;
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
 		return afl_get_payment_amount($result['sum']);
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
 		$downlines_uid[] = $down_uid->downline_user_id;
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
	 		return afl_get_payment_amount($result['sum']);
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
 	$downlines    = afl_get_sponsor_downlines_uid($uid, array(), TRUE);
	// pr($downlines);
 	if ($downlines) {
 		return $downlines;
 	} else
 		return 0;
 }
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

/**
	* @param $id = payout id from tale wp_afl_payout_requests	 
 	* -----------------------------------------------------------
 	*  withdrawal approval bul operation a affiliate member
 	* -----------------------------------------------------------
**/
function eps_affiliates_withdrawal_approve_callback($id = ''){
	global $wpdb;
	$uid = afl_current_uid();
	$table_prefix = $wpdb->prefix ? $wpdb->prefix : 'wp_';
	$table 				= $table_prefix.'afl_payout_requests';

 	$query 						=   array();
 	$query['#select'] = $table;
 	$query['#where'] 	= array(
 		'afl_payout_id ='.$id
 	);
 	$row = db_select($query, 'get_row');
 	
 	if ($row->request_status != 1) {
      wp_set_message(__('Withdrawal Request is already processed of user id' .$row->uid ) ) ;
    	return false;
  }
	  $update = $wpdb->update(
		 	_table_name('afl_payout_requests'),
		 		array(
		 			'modified'				=> afl_date(),
					'paid_by' 				=> $uid,
					'notes'						=> 'Approved | Waiting For Payment',
					'request_status'	=> 2,
					'paid_status'			=> 1,
		 		),
		 		array(
		 			'afl_payout_id' => $id,
		 			'uid'						=> $row->uid,
		 		)
		);
    if($update){
    	return true;
    }else{
    	return false;
    }
}

/**
	* @param $id = payout id from tale wp_afl_payout_requests	 
 	* -----------------------------------------------------------
 	*  withdrawal reject bulk operation 
 	* -----------------------------------------------------------
**/
function eps_affiliates_withdrawal_reject_callback($id =''){
	
	global $wpdb;
	$uid 					= afl_current_uid();
	$table_prefix = $wpdb->prefix ? $wpdb->prefix : 'wp_';
	$table 				= $table_prefix.'afl_payout_requests';

 
 	$query1 						=   array();
 	$query1['#select'] 	= $table;

 	$query1['#where'] 	= array(
 		'afl_payout_id ='.$id
 	);
 	$row = db_select($query1, 'get_row');
 	
 	if ($row->request_status != 1) {
      wp_set_message(__('Withdrawal Request is already processed of user id' .$row->uid ) ) ;
    	return false;
  }

  $transaction 											=	array();
  $transaction['uid'] 							=	$row->uid;
  $transaction['associated_user_id']= $row->uid;
  $transaction['level'] 						= 0;
  $transaction['currency_code'] 		= afl_currency($row->uid);
  $transaction['order_id'] 					= 1;
  $transaction['int_payout'] 				= 0;
  $transaction['credit_status'] 		= 1;
  $transaction['amount_paid'] 			= $row->amount_requested + $row->charges;
  $transaction['amount_paid'] 			= $row->amount_requested;
  $transaction['category'] 					= 'WITHDRAWAL CANCELLATION';
  $transaction['notes'] 						= __('Amount credited back on withdrawal request rejection');
  $transaction['hidden_transaction']= 0;
  afl_member_transaction($transaction, FALSE, FALSE);

  $b_transactions['category'] 					= 'RE WITHDRAWAL CHARGES';
  $b_transactions['additional_notes'] 	= __('Return of Withdrawal Charges');
  $b_transactions['uid'] 								= $row->uid;
  $b_transactions['associated_user_id'] = $row->uid;
  $b_transactions['credit_status'] 			= 0;
  $b_transactions['amount_paid'] 				= $row->charges;
  $b_transactions['notes']		 					= __('Return of  Withdrawal Charges');
  $b_transactions['currency_code'] 			= afl_currency();
  $b_transactions['order_id'] 					= 1;
  afl_business_transaction($b_transactions);

    $update = $wpdb->update(
	 		_table_name('afl_payout_requests'),
	 			array(
	 				'modified'				=> afl_date(),
	 				'paid_by' 				=> $uid,
	 				'notes'						=> 'Rejected | Try Later',
	 				'paid_status'			=> -99,
	 				'request_status'	=> 3,
	 				'processed_method'=> '',
	 			),
	 			array(
	 				'afl_payout_id' => $id,
	 				'uid'						=> $row->uid,
	 			)
	 		);

  if($update){
    return true;
  }else{
    return false;
  }  
}

/**
	* @param $id = payout id from tale wp_afl_payout_requests	 
 	* -----------------------------------------------------------
 	*  Payout approval bulk operation
 	* -----------------------------------------------------------
**/
function eps_affiliates_payout_paid_callback($id = ''){
	
	$afl_date 				= afl_date();
 	$afl_date_splits 	= afl_date_splits($afl_date);
	$uid 							= afl_current_uid();
 	global $wpdb;

	$table_prefix 		= $wpdb->prefix ? $wpdb->prefix : 'wp_';
	$table 						= $table_prefix.'afl_payout_requests';
 
 	$query1 					= array();
 	$query1['#select']= $table;

 	$query1['#where']	= array(
 		'afl_payout_id ='.$id
 	);
 	$row = db_select($query1, 'get_row');
 	
 	if ($row->request_status != 2) {
      wp_set_message(__('Admin Approval is required' .$row->uid ) ) ;
    	return false;
  }
  if($row->paid_status != 1){
    	wp_set_message(__('Admin Approval is required' .$row->uid ) ) ;
      return false;
  }

  $update = $wpdb->update(
	 			_table_name('afl_payout_requests'),
	 			array(
	 				'modified'				=> $afl_date,
	 				'paid_date' 				=> $afl_date,
	 				'notes'						=> 'Paid | Payment Processed',
	 				'paid_status'			=> 2,
	 				'request_status'	=> 3,
	 				'payment_date' => $afl_date_splits['d'],
          'payment_month' => $afl_date_splits['m'],
          'payment_year' => $afl_date_splits['y'],
          'payment_week' => $afl_date_splits['w'],
	 			),
	 			array(
	 				'afl_payout_id' => $id,
	 				'uid'						=> $row->uid,
	 			)
	 		);
   if($update){
    return true;
  }else{
    return false;
  } 
}

/**
	* @param $id = payout id from tale wp_afl_payout_requests	 
 	* -----------------------------------------------------------
 	*  withdrawal cancellation by user bulk operation
 	* -----------------------------------------------------------
**/
function eps_affliate_user_cancel_withdraw_callback($id = ''){
	global $wpdb;
	$afl_date 		= afl_date();
	$uid 					= afl_current_uid();
	$table_prefix = $wpdb->prefix ? $wpdb->prefix : 'wp_';
	$table 				= $table_prefix.'afl_payout_requests';

 
 	$query 							=   array();
 	$query['#select'] 	= $table;

 	$query['#where'] 		= array(
 		'afl_payout_id ='.$id
 	);
 	$row = db_select($query, 'get_row');
 	
 	if ($uid != $row->uid) {
     wp_set_message(__('You can not cancel other members withdrawal request' .$row->uid ) ) ;
    	return false;
  }
  if ($row->request_status != 1) {
      wp_set_message(__('Withdrawal Request is already processed of user id' .$row->uid ) ) ;
    	return false;
  }
   	$transaction 												= array();
    $transaction['uid'] 								= $row->uid;
    $transaction['associated_user_id'] 	= $row->uid;
    $transaction['level'] 							= 0;
    $transaction['currency_code'] 			= afl_currency($row->uid);
    $transaction['order_id'] 						= 1;
    $transaction['int_payout'] 					= 0;
    $transaction['credit_status'] 			= 1;
    $transaction['hidden_transaction']	= 0;
    $transaction['amount_paid'] 				= $row->amount_requested + $row->charges;
    $transaction['amount_paid'] 				= $row->amount_requested;
    $transaction['category'] 						= 'WITHDRAWAL CANCELLATION';
    $transaction['notes'] 							= __('Amount credited back on withdrawal request cancelation');
    afl_member_transaction($transaction, FALSE, FALSE);


    $b_transactions['category'] 					= 'RE WITHDRAWAL CHARGES';
    $b_transactions['additional_notes'] 	= __('Return of Withdrawal Charges');
    $b_transactions['uid'] 								= $row->uid;
    $b_transactions['associated_user_id'] = $row->uid;
    $b_transactions['credit_status'] 			= 0;
    $b_transactions['amount_paid'] 				= $row->charges;
    $b_transactions['notes'] 							= __('Return of  Withdrawal Charges');
    $b_transactions['currency_code'] 			= afl_currency();
    $b_transactions['order_id']						= 1;
    afl_business_transaction($b_transactions);

    $update = $wpdb->update(
	 			_table_name('afl_payout_requests'),
	 			array(
	 				'modified'				=> $afl_date,
	 				'paid_date' 				=> $afl_date,
	 				'notes'						=> 'Cancelled',
	 				'paid_status'			=> -99,
	 				'request_status'	=> 3,
	 			),
	 			array(
	 				'afl_payout_id' => $id,
	 				'uid'						=> $row->uid,
	 			)
	 		);
   if($update){
    return true;
  }else{
    return false;
  } 

}
 
