<?php
/**
 * -----------------------------------------------------------------
 * @author < pratheeshepixelsolutions.com >
 *
 * here check the hoding tank expiry of userm,
 * If the user expires, automatically place the user to available 
 * position
 * -----------------------------------------------------------------
*/
 function _check_holding_tank_expiry () {
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