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
				// pr ('------------------------------------------------') ;
				// pr ('Rank '. $i) ;
				// pr ('PV : '._check_required_pv_meets( $uid, $i )) ;
				// pr ('GV : '._check_required_gv_meets( $uid, $i )) ;
				// pr ('DI : '._check_required_distributors_meets( $uid, $i )) ;
				// pr ('------------------------------------------------') ;
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
 		$required_distrib = afl_variable_get('rank_'.$rank.'no_of_distributors',0);
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
	              $down_downlines_count = afl_get_user_downlines_uid($value->downline_user_id, array('member_rank'=>$i), true);
	              if ( $down_downlines_count )
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
	              '`'._table_name('afl_user_downlines').'`.`uid` IN ('.$implodes.')'
	            );
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
 	$downlines    = afl_get_sponsor_downlines_uid($uid, array(), TRUE);
	// pr($downlines);
 	if ($downlines) {
 		return $downlines;
 	} else
 		return 0;
 }
