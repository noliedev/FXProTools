<?php

/**
 * Check to see if bonus requirements have been met when changing a referral's status
 *
 * @since 1.0
 * @return array
 */
function affwp_pb_bonus_check_on_referral_status_change( $referral_id, $new_status, $old_status ) {

	if( 'paid' == $new_status ) { 
	
		do_action( 'affwp_pb_bonus_check_on_paid_referral', $referral_id );
		
	} elseif ( 'unpaid' == $new_status && ( 'pending' == $old_status || 'rejected' == $old_status ) ) {
	
		do_action( 'affwp_pb_bonus_check_on_unpaid_referral', $referral_id );
		
	} elseif( 'pending' == $new_status ) {
	
		do_action( 'affwp_pb_bonus_check_on_pending_referral', $referral_id );
		
	}
}
add_action( 'affwp_set_referral_status', 'affwp_pb_bonus_check_on_referral_status_change', 10, 3 );



/**
 * Check to see if bonus requirements have been met when auto completing a referral
 *
 * @since 1.0
 * @return array
 */
function affwp_pb_bonus_check_on_complete_referral( $referral_id ) {

	do_action( 'affwp_pb_bonus_check_on_unpaid_referral', $referral_id );

}
add_action( 'affwp_complete_referral', 'affwp_pb_bonus_check_on_complete_referral', 10, 1 );



/**
 * Check for bonuses based on referrals
 * 
 * @since 1.0
 */
function affwp_pb_check_for_referral_bonus( $referral_id ) {
	
	$referral = affwp_get_referral( $referral_id );

	// Stop if this is a bonus referral
	if( $referral->custom == 'performance_bonus' ) {
		return;
	}
	
	$affiliate_id = $referral->affiliate_id;

	// Exclude bonuses that have already been earned	
 	$bonuses = apply_filters( 'affwp_pb_get_active_bonuses', get_active_bonuses(), $affiliate_id );
	
	// Stop if all bonuses have been earned already
	if( empty( $bonuses ) ) {
		return;
	}
	
	foreach( $bonuses as $key => $bonus ) {
		
		// Check for referral bonus
		if( $bonus['type'] == 'referral' ) {
		
			$referral_count = affwp_pb_get_affiliate_referral_count( $affiliate_id );
		
			// Check if the affiliate has met the requirements
			if( $referral_count >= $bonus['requirement'] ) {
			
				$bonus_earned = affwp_pb_get_bonus_log( $affiliate_id, $bonus['pre_bonus'] );
				
				// Check for prerequisite bonus
				if( !empty( $bonus['pre_bonus'] ) && empty( $bonus_earned ) )
						continue;
				
				// Create the bonus
				affwp_pb_create_bonus_referral( $affiliate_id, $bonus['id'], $bonus['title'], $bonus['amount'] );
			
			}
		}
	}
}
add_action( 'affwp_pb_bonus_check_on_paid_referral', 'affwp_pb_check_for_referral_bonus', 10, 1 );
add_action( 'affwp_pb_bonus_check_on_unpaid_referral', 'affwp_pb_check_for_referral_bonus', 10, 1 );

/**
 * Check for bonuses based on earnings
 * 
 * @since 1.0
 */
function affwp_pb_check_for_earnings_bonus( $referral_id ) {
	
	$referral = affwp_get_referral( $referral_id );

	// Stop if this is a bonus referral
	if( $referral->custom == 'performance_bonus' ) {
		return;
	}
	
	$affiliate_id = $referral->affiliate_id;
 	$bonuses = apply_filters( 'affwp_pb_get_active_bonuses', get_active_bonuses(), $affiliate_id );	
	
	// Stop if all bonuses have been earned already
	if( empty( $bonuses ) ) {
		return;
	}
	
	foreach( $bonuses as $key => $bonus ) {

		// Check for earnings bonus
		if( $bonus['type'] == 'earnings' ) {
				
			$earnings = affwp_pb_get_affiliate_earnings_count( $affiliate_id );
		
			// Check if the affiliate has met the requirements
			if( $earnings >= $bonus['requirement'] ) {
			
				$bonus_earned = affwp_pb_get_bonus_log( $affiliate_id, $bonus['pre_bonus'] );
				
				// Check for prerequisite bonus
				if( !empty( $bonus['pre_bonus'] ) && empty( $bonus_earned ) )
						continue;
						
				// Create the bonus
				affwp_pb_create_bonus_referral( $affiliate_id, $bonus['id'], $bonus['title'], $bonus['amount'] );
			
			}
		}
	}
}
add_action( 'affwp_pb_bonus_check_on_paid_referral', 'affwp_pb_check_for_earnings_bonus', 10, 1 );
add_action( 'affwp_pb_bonus_check_on_unpaid_referral', 'affwp_pb_check_for_earnings_bonus', 10, 1 );
add_action( 'affwp_pb_bonus_check_on_pending_referral', 'affwp_pb_check_for_earnings_bonus', 10, 1 );


/**
 * Check for bonuses based on earnings and award a percentage commission
 * 
 * @since 1.0.3
 */
function affwp_pb_check_for_earnings_percentage_bonus( $referral_id ) {
	
	$referral = affwp_get_referral( $referral_id );

	// Stop if this is a bonus referral
	if( $referral->custom == 'performance_bonus' ) {
		return;
	}
	
	$affiliate_id = $referral->affiliate_id;
 	$bonuses = apply_filters( 'affwp_pb_get_active_bonuses', get_active_bonuses(), $affiliate_id );	
	
	// Stop if all bonuses have been earned already
	if( empty( $bonuses ) ) {
		return;
	}
	
	foreach( $bonuses as $key => $bonus ) {

		// Check for earnings bonus
		if( $bonus['type'] == 'earnings_percentage' ) {
				
			$earnings = affwp_pb_get_affiliate_earnings_count( $affiliate_id );
		
			// Check if the affiliate has met the requirements
			if( $earnings >= $bonus['requirement'] ) {
			
				$bonus_earned = affwp_pb_get_bonus_log( $affiliate_id, $bonus['pre_bonus'] );
				
				// Check for prerequisite bonus
				if( !empty( $bonus['pre_bonus'] ) && empty( $bonus_earned ) )
						continue;

				$bonus_rate = $bonus['amount'];
				$bonus_amount = affwp_pb_calc_bonus_amount( $bonus_rate, $earnings );
				
				// Create the bonus
				affwp_pb_create_bonus_referral( $affiliate_id, $bonus['id'], $bonus['title'], $bonus_amount );
			
			}
		}
	}
}
add_action( 'affwp_pb_bonus_check_on_paid_referral', 'affwp_pb_check_for_earnings_percentage_bonus', 10, 1 );
add_action( 'affwp_pb_bonus_check_on_unpaid_referral', 'affwp_pb_check_for_earnings_percentage_bonus', 10, 1 );
add_action( 'affwp_pb_bonus_check_on_pending_referral', 'affwp_pb_check_for_earnings_percentage_bonus', 10, 1 );

/**
 * Log the Affiliate's Bonus
 *
 * @since  1.0
 * @return void
 */
function affwp_pb_log_bonus( $affiliate_id = 0, $bonus_id = 0, $bonus_name = '', $bonus_amount = 0, $referral_id = 0 ) {
	
	if( empty( $affiliate_id ) || empty( $bonus_id ) ) {
		return;
	}
	
	$bonus_log = affwp_pb_get_bonus_log( $affiliate_id, $bonus_id );
	
	if( empty( $bonus_log ) ) {
	
		$bonus_log = 1;
		
		// Add new bonus log
		affwp_add_affiliate_meta( $affiliate_id, 'bonus_'. $bonus_id .'_log', $bonus_log );
		
	} else{

		$bonus_log++;
		
		// Update existing bonus log
		affwp_update_affiliate_meta( $affiliate_id, 'bonus_'. $bonus_id .'_log', $bonus_log );
	
	}
	
	do_action( 'affwp_pb_bonus_logged', $bonus_id );

}
add_action( 'affwp_pb_bonus_created', 'affwp_pb_log_bonus', 10, 5 );