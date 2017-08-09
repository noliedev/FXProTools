<?php
	/**
	 * @author < pratheesh@epixelsolutions.com >
	 *
	 * Here gives all the cron jobs for our plugin
	 *
	 *
	 *
	 *
	 *
	*/

/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function holding_tank_user_expiry_activation() {
		if( !wp_next_scheduled( 'holding_tank_user_expiry_scheduler' ) ) {  
		   wp_schedule_event( time(), 'everyhour', 'holding_tank_user_expiry_scheduler' );  
		}
	}

/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function holding_tank_user_expiry_deactivate() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('holding_tank_user_expiry_scheduler');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'holding_tank_user_expiry_scheduler');
	} 
/*
 * -------------------------------------------------------------
 * here's the function we'd like to call with our cron job
 * -------------------------------------------------------------
 * 
 * set the users remaining days in the holding tank
 *
 * If the remaining day is 0, means he expires from the holding tank,
 * get that user and frocelly place that user to the tree
 *
*/
	function holding_tank_user_expiry_cron_job_callback() {
		global $wpdb;
		//get the users from the holding tank
		$current_date = afl_date();
		$holding_days = afl_variable_get('holding_tank_holding_days',7);

		$query = array();
		$query['#select'] 	 = _table_name('afl_user_holding_tank');
		$query['#where'] 		 = array(
			'last_updated <'.$current_date
		);

		$query['#limit'] 		 = 100;
		$holding_tank_users  = db_select($query, 'get_results');

		foreach ($holding_tank_users as $key => $user) {
			$created_date  = $user->created;
			$datediff 		 = $current_date - $created_date;
			$remaining_day = floor($datediff / (60 * 60 * 24));
			$remaining_day = $holding_days - $remaining_day;
			
			//update the remaining day if it is not -1
			if ( $remaining_day < 0 ){
				do_action('eps_affiliates_force_place_after_holding_expired',$user->uid, $user->referrer_uid);
			} else {
				$wpdb->update(
					_table_name('afl_user_holding_tank'),
					array(
						'day_remains'  => $remaining_day,
						'last_updated' => $current_date
					),
					array(
						'uid' => $user->uid
					)
				);
			}
		}
	}







/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function monthly_matrix_compensation_payout_activation() {
		if( !wp_next_scheduled( 'monthly_matrix_compensation_payout' ) ) {  
		   wp_schedule_event( time(), 'everyhour', 'monthly_matrix_compensation_payout' );  
		}
	}
/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function monthly_matrix_compensation_payout_deactivation() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('monthly_matrix_compensation_payout');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'monthly_matrix_compensation_payout');
	} 
/*
 * ------------------------------------------------------------
 * Monthly matrix commision payout
 *
 * check month starting
 * get all active users
 * get total actived month of a user
 * get actived downlines of this user
 * give count * actived month count amount
 * ------------------------------------------------------------
*/
// monthly_matrix_compensation_payout_cron_job_callback();
 function monthly_matrix_compensation_payout_cron_job_callback () {
 	$matrix_given_date 	= afl_variable_get('matrix_compensation_given_day', 1);
 	$current_date				= afl_date();
 	$afl_date_splits 		= afl_date_splits(afl_date());

 	if ( $afl_date_splits['d'] == $matrix_given_date) {

 		$query = array();
 		$query['#select'] = _table_name('afl_user_genealogy');
 		$query['#where']  = array(
 			'deleted = 0',
 			'status = 1'
 		);
		$query['#limit'] 		 = 100;
 		$users  = db_select($query, 'get_results');
 		// pr($users,1);
 		foreach ($users as $key => $user) {
 			//get actived on
 		/*
 		 * -----------------------------------------------------
 		 * IF a user status is 1 then check the months he actived 
 		 * 
 		 * If user status is 0, check howmany months he has been,
 		 * actived then give the commission only once
 		 * -----------------------------------------------------
 		*/

 			$actived_on  		= $user->actived_on;
 			$diff						= $current_date - $actived_on;
 			$years 					= floor($diff / (365*60*60*24));
 			$months_actived = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
 			$months_actived = $months_actived + 1;
 			$maximum_period = afl_variable_get('matrix_compensation_period_maximum', 3);

 			if (!empty($months_actived)) {
 				//check the difference greater than maximum period
 				//if yes, taken the maximum
 				if ($months_actived > $maximum_period) {
 					$months_actived = $maximum_period;
 				}
 				//get the countof actived downlines under this user
 				$query = array();
		 		$query['#select'] = _table_name('afl_user_downlines');
		 		$query['#join'] 	= array(
		 			_table_name('afl_user_genealogy') => array(
		 				'#condition' => '`'._table_name('afl_user_downlines').'`.`downline_user_id` = `'._table_name('afl_user_genealogy').'`.`uid`'
		 			)
		 		);
		 		$query['#where']  = array(
		 			'`'._table_name('afl_user_genealogy').'`.`status` = 1',
		 			'`'._table_name('afl_user_downlines').'`.`uid` = '.$user->uid
		 		);
		 		$query['#expression'] = array(
		 			'COUNT(`'._table_name('afl_user_genealogy').'`.`uid`) as count'
		 		);

		 		$respo = db_select($query, 'get_row');
		 		$count = !empty($respo->count) ? $respo->count : 0;
		 		
		 		//get the bonus for the $months_actived 
		 		$amount_for_actived_month = afl_variable_get('month_'.$months_actived.'_matrix_compensation', 0);
		 		$user_amount = $amount_for_actived_month * $count;

		 		if ( $user_amount > 0) {
		 			 $transaction = array();
				   $transaction['uid'] 								= $user->uid;
				   $transaction['associated_user_id'] = $user->uid;
				   $transaction['payout_id'] 					= 0;
				   $transaction['level']							= 0;
				   $transaction['currency_code'] 			= afl_currency();
				   $transaction['order_id'] 					= 1;
				   $transaction['int_payout'] 				= 0;
				   $transaction['hidden_transaction'] = 0;
				   $transaction['credit_status'] 			= 1;
				   $transaction['amount_paid'] 				= afl_commerce_amount($user_amount);
				   $transaction['category'] 					= 'MATRIX COMPENSATION';
				   $transaction['notes'] 							= 'Matrix compensation for '.$months_actived.' actived months';

				   //check already paid
				   $query = array();
				   $afl_date_splits = afl_date_splits(afl_date());
				   $query['#select'] = _table_name('afl_user_transactions');
				   $query['#where'] = array(
				   	'`uid`='.$user->uid,
				   	'`category` = "MATRIX COMPENSATION"',
				   	'`transaction_month` = '.$afl_date_splits['m'],
				   	'`transaction_year` = '.$afl_date_splits['y'],
				   );
				   $check = db_select($query, 'get_row');
				   
				   if ( empty($check) ){
				   	 afl_member_transaction($transaction, FALSE, FALSE);

					   $b_transactions['category'] 						= 'MATRIX COMPENSATION';
					   $b_transactions['additional_notes']		= 'Matrix compensation';
					   $b_transactions['uid'] 								= $user->uid;
					   $b_transactions['associated_user_id'] 	= $user->uid;
					   $b_transactions['credit_status'] 			= 0;
					   $b_transactions['amount_paid'] 				= afl_commerce_amount($user_amount);
					   $b_transactions['notes'] 							= 'Matrix compensation for '.$months_actived.' actived months';
					   $b_transactions['currency_code'] 			= afl_currency();
					   $b_transactions['order_id'] 						= 1;
					   afl_business_transaction($b_transactions);
				   }
		 		}
 			}
 			
 		}
 	}
 }




/*
 * -------------------------------------------------------------
 * Custom interval
 * -------------------------------------------------------------
*/
	function cron_add_hour( $schedules ) {
	    $schedules['everyhour'] = array(
		    'interval' => 3600,
		    'display' => __( 'Once Every Hour' )
	    );
	    return $schedules;
	}
	add_filter( 'cron_schedules', 'cron_add_hour' );



/*
 * -------------------------------------------------------------
 * All the scheduler activation hook callback comes here
 * and make sure it's called whenever WordPress loads
 * -------------------------------------------------------------
*/
	add_action('wp', 'holding_tank_user_expiry_activation');
	
	add_action('wp', 'monthly_matrix_compensation_payout_activation');



/*
 * -------------------------------------------------------------
 * All the scheduler deactivation hooks comes here
 * callback function when the plugin deactivated
 * -------------------------------------------------------------
*/
	register_deactivation_hook (__FILE__, 'holding_tank_user_expiry_deactivate');
	register_deactivation_hook (__FILE__, 'monthly_matrix_compensation_payout_deactivation');


/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * check the user expired from the holding tank, if yes auto place
 * user under sponsor
 * -------------------------------------------------------------
*/
	add_action ('holding_tank_user_expiry_scheduler', 'holding_tank_user_expiry_cron_job_callback');

/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * Here calculate the monthly matrix bonus compemsation
 * -------------------------------------------------------------
*/
	add_action ('monthly_matrix_compensation_payout', 'monthly_matrix_compensation_payout_cron_job_callback');
	