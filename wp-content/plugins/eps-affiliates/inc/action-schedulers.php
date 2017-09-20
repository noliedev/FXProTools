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
	function eps_affiliates_holding_tank_user_expiry_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_holding_tank_user_expiry_scheduler' ) ) {  
		   wp_schedule_event( time(), 'everyhour', 'eps_affiliates_holding_tank_user_expiry_scheduler' );  
		}
	}

/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_holding_tank_user_expiry_deactivate() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_holding_tank_user_expiry_scheduler');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_holding_tank_user_expiry_scheduler');
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
	function eps_affiliates_holding_tank_user_expiry_cron_callback() {
		require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/plan/matrix/holding-tank-expiry-check.php';
		if (function_exists('_check_holding_tank_expiry')) {
			_check_holding_tank_expiry();
		}
	}



/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function eps_affiliates_unilevel_holding_tank_user_expiry_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_unilevel_holding_tank_user_expiry_scheduler' ) ) {  
		   wp_schedule_event( time(), 'everyhour', 'eps_affiliates_unilevel_holding_tank_user_expiry_scheduler' );  
		}
	}

/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_unilevel_holding_tank_user_expiry_deactivate() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_unilevel_holding_tank_user_expiry_scheduler');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_unilevel_holding_tank_user_expiry_scheduler');
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
	function eps_affiliates_unilevel_holding_tank_user_expiry_cron_callback() {
		require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/plan/unilevel/holding-tank-expiry-check.php';
		if (function_exists('_unilevel_check_holding_tank_expiry')) {
			_unilevel_check_holding_tank_expiry();
		}
	}



/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function eps_affiliates_monthly_matrix_compensation_payout_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_monthly_matrix_compensation_payout' ) ) {  
		   wp_schedule_event( time(), 'everyhour', 'eps_affiliates_monthly_matrix_compensation_payout' );  
		}
	}
/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_monthly_matrix_compensation_payout_deactivation() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_monthly_matrix_compensation_payout');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_monthly_matrix_compensation_payout');
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
 function eps_affiliates_monthly_matrix_compensation_payout_cron_callback () {
	 	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/plan/matrix/matrix-compensation-bonus-calculation.php';
		if (function_exists('_calculate_matrix_compensation')) {
			_calculate_matrix_compensation();
		}
 }



/*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function eps_affiliates_monthly_pool_bonus_payout_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_monthly_pool_bonus_payout' ) ) {  
		   wp_schedule_event( time(), 'everyhour', 'eps_affiliates_monthly_pool_bonus_payout' );  
		}
	}
/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_monthly_pool_bonus_payout_deactivation() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_monthly_pool_bonus_payout');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_monthly_pool_bonus_payout');
	} 
/*
 * -------------------------------------------------------------
 * Monthly sales pool bonus calculation callback
 * -------------------------------------------------------------
 * 
 * Get the monthly profit 
 * get maximum rank
 * check the count of each rank occured users
 * get sales under their downlines and only take the maximum amount
 * calulate the % for each ranked member based on their total purchase
 * -------------------------------------------------------------
*/
 function eps_affiliates_monthly_pool_bonus_payout_cron_callback () {
	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/plan/matrix/global-pool-bonus-calculation.php';
	if (function_exists('_calculate_global_pool_bonus')) {
		_calculate_global_pool_bonus();
	}

 }




 /*
 * -------------------------------------------------------------
 * create a scheduled event (if it does not exist already)
 * -------------------------------------------------------------
*/
	function eps_affiliates_remote_users_embedd_cron_activation() {
		if( !wp_next_scheduled( 'eps_affiliates_remote_users_embedd_cron' ) ) {  
		   wp_schedule_event( time(), 'everyhour', 'eps_affiliates_remote_users_embedd_cron' );  
		}
	}
/*
 * -------------------------------------------------------------
 * unschedule event upon plugin deactivation
 * -------------------------------------------------------------
*/
	function eps_affiliates_remote_users_embedd_cron_deactivation() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('eps_affiliates_remote_users_embedd_cron');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'eps_affiliates_remote_users_embedd_cron');
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

 function eps_affiliates_remote_users_embedd_cron_callback () {
	 	require_once EPSAFFILIATE_PLUGIN_DIR . 'inc/API/api-remote-user-embedd-cron-callback.php';
		if (function_exists('_process_embedd_users_queue')) {
			_process_embedd_users_queue();
		}
 }


/*
 * -------------------------------------------------------------
 * Custom interval
 * -------------------------------------------------------------
*/
	function cron_add_hour( $schedules ) {
	    $schedules['everyhour'] = array(
		    'interval' => 360,
		    'display' => __( 'Once Every Hour' )
	    );

	    $schedules['eps_queue_processing'] = array(
		    'interval' => 300,
		    'display' => __( 'Every 5 minute' )
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
	add_action('wp', 'eps_affiliates_holding_tank_user_expiry_activation');
	
	add_action('wp', 'eps_affiliates_unilevel_holding_tank_user_expiry_activation');
	
	add_action('wp', 'eps_affiliates_monthly_matrix_compensation_payout_activation');

	add_action('wp', 'eps_affiliates_monthly_pool_bonus_payout_activation');

	add_action('wp', 'eps_affiliates_remote_users_embedd_cron_activation');

/*
 * -------------------------------------------------------------
 * All the scheduler deactivation hooks comes here
 * callback function when the plugin deactivated
 * -------------------------------------------------------------
*/
	register_deactivation_hook (__FILE__, 'eps_affiliates_holding_tank_user_expiry_deactivate');
	register_deactivation_hook (__FILE__, 'eps_affiliates_unilevel_holding_tank_user_expiry_deactivate');
	register_deactivation_hook (__FILE__, 'eps_affiliates_monthly_matrix_compensation_payout_deactivation');
	register_deactivation_hook (__FILE__, 'eps_affiliates_monthly_pool_bonus_payout_deactivation');
	register_deactivation_hook (__FILE__, 'eps_affiliates_remote_users_embedd_cron_deactivation');


/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * check the user expired from the holding tank, if yes auto place
 * user under sponsor
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_holding_tank_user_expiry_scheduler', 'eps_affiliates_holding_tank_user_expiry_cron_callback');

/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * check the user expired from the holding tank, if yes auto place
 * user under sponsor
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_unilevel_holding_tank_user_expiry_scheduler', 'eps_affiliates_unilevel_holding_tank_user_expiry_cron_callback');
/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * Here calculate the monthly matrix bonus compemsation
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_monthly_matrix_compensation_payout', 'eps_affiliates_monthly_matrix_compensation_payout_cron_callback');

/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * Here calculate the pool bonus for each and every  user
 * Monthly calculation
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_monthly_pool_bonus_payout', 'eps_affiliates_monthly_pool_bonus_payout_cron_callback');
/*
 * -------------------------------------------------------------
 * hook that function into our scheduled event: 
 * Here get the remoet users from the queue
 * every 5 mins
 * -------------------------------------------------------------
*/
	add_action ('eps_affiliates_remote_users_embedd_cron', 'eps_affiliates_remote_users_embedd_cron_callback');

