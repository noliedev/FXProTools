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
 * and make sure it's called whenever WordPress loads
 * -------------------------------------------------------------
*/
	add_action('wp', 'holding_tank_user_expiry_activation');


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
 * callback function when the plugin deactivated
 * -------------------------------------------------------------
*/
	register_deactivation_hook (__FILE__, 'holding_tank_user_expiry_deactivate');




/*
 * -------------------------------------------------------------
 * Custom interval
 * -------------------------------------------------------------
*/
	function cron_add_hour( $schedules ) {
		// Adds once every hour to the existing schedules.
	    $schedules['everyhour'] = array(
		    'interval' => 360,
		    'display' => __( 'Once Every Hour' )
	    );
	    return $schedules;
	}
	add_filter( 'cron_schedules', 'cron_add_hour' );



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
 
