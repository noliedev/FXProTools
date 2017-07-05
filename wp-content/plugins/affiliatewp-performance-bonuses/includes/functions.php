<?php

/**
 * Get a Particular Bonus
 *
 * @since 1.0
 * @return array
 */
function get_bonus_by_id( $bonus_id ) {

	$bonuses = get_bonuses();
	
	foreach( $bonuses as $key => $bonus ) {

		if( $bonus['id'] != $bonus_id ) {
		
			// Remove inactive bonuses
			unset( $bonuses[ $key ] );
		
		}

	}
	
	return array_values( $bonuses );
}

/**
 * Get ALL Bonuses
 *
 * @since 1.0
 * @return array
 */
function get_bonuses() {
	$bonuses = affiliate_wp()->settings->get( 'bonuses', array() );
	return array_values( $bonuses );
}

/**
 * Get Active Bonuses
 *
 * @since 1.0
 * @return array
 */
function get_active_bonuses() {

	$bonuses = get_bonuses();
	
	foreach( $bonuses as $key => $bonus ) {

		if( empty( $bonus['active'] ) ) {
		
			// Remove inactive bonuses
			unset( $bonuses[ $key ] );
		
		}

	}
	
	return array_values( $bonuses );
}

/**
 * Creates a unique bonus id
 *
 * @since 1.0
 * @return int
 */
function affwp_pb_new_bonus_id() {

	// Generate a random number
	$bonus_id = rand( 1, 9999 );
	
	if( $bonus_id ) {
		return $bonus_id;
	}
}

/**
 * Removes an affiliate's earned bonuses from the active bonuses array
 *
 * @since 1.0
 * @return array
 */
function affwp_pb_exclude_earned_bonuses( $bonuses = array(), $affiliate_id = 0 ) {

	if( empty( $affiliate_id ) || empty( $bonuses ) ) {
		return $bonuses;
	}
	
	foreach( $bonuses as $key => $bonus ) {

		$earned_bonus = affwp_pb_get_bonus_log( $affiliate_id, $bonus['id'] );

		if( !empty( $earned_bonus ) ) {
		
			// Remove inactive bonuses
			unset( $bonuses[ $key ] );
		
		}

	}

	return $bonuses;
}
add_filter( 'affwp_pb_get_active_bonuses', 'affwp_pb_exclude_earned_bonuses', 10, 2 );

/**
 * Retrieves an array of allowed bonus frequencies
 *
 * @since 1.0
 * @return array
 */
function affwp_pb_get_bonus_frequencies() {
	// Allowed frequencies
	$types = array(
		'one-time' => __( 'One Time', 'affiliatewp-performance-bonuses' ),
		'ongoing'  => __( 'Ongoing', 'affiliatewp-performance-bonuses' )
	);
	return apply_filters( 'affwp_pb_get_bonus_frequencies', $frequencies );
}

/**
 * Retrieves an array of allowed bonus types
 *
 * @since 1.0
 * @return array
 */
function affwp_pb_get_bonus_types() {
	// Allowed types
	$types = array(
		'referral' => __( 'Referral', 'affiliatewp-performance-bonuses' ),
		'earnings'  => __( 'Earnings', 'affiliatewp-performance-bonuses' ),
		'earnings_percentage'  => __( 'Earnings Percentage', 'affiliatewp-performance-bonuses' )
	);
	return apply_filters( 'affwp_pb_get_bonus_types', $types );
}

/**
 * Retrieves an array of allowed bonus intervals
 *
 * @since 1.0
 * @return array
 */
function affwp_pb_get_bonus_intervals() {
	// Allowed intervals
	$intervals = array(
		'weekly' => __( 'Weekly', 'affiliatewp-performance-bonuses' ),
		'monthly' => __( 'Monthly', 'affiliatewp-performance-bonuses' ),
		'yearly'  => __( 'Yearly', 'affiliatewp-performance-bonuses' )
	);
	return apply_filters( 'affwp_pb_get_bonus_intervals', $intervals );
}

/**
 * Get the amount of referrals for an affiliate based on calculation settings
 *
 * @since 1.0
 * @return float
 */
function affwp_pb_get_affiliate_referral_count( $affiliate ) {

	if ( empty( $affiliate ) ) {
		return;
	}

	if ( 'paid' == affiliate_wp()->settings->get( 'affwp_pb_referrals_basis' ) ) {
		$referrals = affwp_get_affiliate_referral_count( $affiliate );
	}
	
	if ( 'unpaid' == affiliate_wp()->settings->get( 'affwp_pb_referrals_basis' ) ) {
		$referrals = affwp_count_referrals( $affiliate, 'unpaid' );
	}
	
	if ( 'total' == affiliate_wp()->settings->get( 'affwp_pb_referrals_basis' ) ) {
		$paid_referrals = affwp_get_affiliate_referral_count( $affiliate );
		$unpaid_referrals = affwp_count_referrals( $affiliate, 'unpaid' );
		$referrals = $paid_referrals + $unpaid_referrals;
	}

	return $referrals;
}

/**
 * Get the amount of earnings for an affiliate based on calculation settings
 *
 * @since 1.0
 * @return float
 */
function affwp_pb_get_affiliate_earnings_count( $affiliate ) {

	if ( empty( $affiliate ) ) {
		return;
	}

	if ( 'paid' == affiliate_wp()->settings->get( 'affwp_pb_earnings_basis' ) ) {
		$earnings = affwp_get_affiliate_earnings( $affiliate );
	}
	
	if ( 'unpaid' == affiliate_wp()->settings->get( 'affwp_pb_earnings_basis' ) ) {
		$earnings = affwp_get_affiliate_unpaid_earnings( $affiliate );
	}
	
	if ( 'total' == affiliate_wp()->settings->get( 'affwp_pb_earnings_basis' ) ) {
		$paid_earnings = affwp_get_affiliate_earnings( $affiliate );
		$unpaid_earnings = affwp_get_affiliate_unpaid_earnings( $affiliate );
		$earnings = $paid_earnings + $unpaid_earnings;
	}

	return $earnings;
}


/**
 * Returns a percentage based bonus amount
 * 
 * @since 1.0.3
 */
function affwp_pb_calc_bonus_amount( $bonus_rate, $base_amount ) {

	if ( empty( $bonus_rate ) || empty( $base_amount ) ) {
		return;
	}
	
	$decimals = function_exists( 'affwp_get_decimal_count' ) ? affwp_get_decimal_count() : 2;
	$rate = $bonus_rate;
	
	if ( $rate > 1 ) {
		$rate = $rate / 100;
	}
	
	$bonus_amount = round( $base_amount * $rate, $decimals );

	if ( $bonus_amount < 0 ) {
		$bonus_amount = 0;
	}

	return $bonus_amount;
}

/**
 * Create the referral for the bonus
 * 
 * @since 1.0
 */
function affwp_pb_create_bonus_referral( $affiliate_id = 0, $bonus_id = 0, $bonus_name = '', $bonus_amount = 0 ) {

	if ( ! $affiliate_id || empty( $bonus_id ) || empty( $bonus_amount ) ) {
		return;
	}

	// Get the default bonus status
	$status = affiliate_wp()->settings->get( 'affwp_pb_default_status' ); 

	$data = array(
		'affiliate_id' => $affiliate_id,
		'amount'       => $bonus_amount,
		'custom'       => 'performance_bonus', // Add referral type as custom referral data
		'reference'	   => $bonus_id,
		'description'  => ! empty( $bonus_name ) ? $bonus_name : __( 'Performance Bonus', 'affiliatewp-performance-bonuses' ),
		'status'       => $status
	);

	// Insert new referral for the bonus
	$referral_id = affiliate_wp()->referrals->add( $data );

	if( $referral_id ) {
	
		do_action( 'affwp_pb_bonus_created', $affiliate_id, $bonus_id, $bonus_name, $bonus_amount, $referral_id );

	}
}

/**
 * Subtract a Bonus from the Affiliate's Log
 *
 * @since  1.0
 * @return boolean
 */
function affwp_pb_subtract_from_bonus_log( $affiliate_id = 0, $bonus_id = 0, $bonus_name = '', $bonus_amount = 0, $referral_id = 0 ) {
	
	if( empty( $affiliate_id ) || empty( $bonus_id ) ) {
		return;
	}
	
	$bonus_log = affwp_pb_get_bonus_log( $affiliate_id, $bonus_id );
	
	if( empty( $bonus_log ) ) {
			return false;
	} else{

		$bonus_log--;
		
		// Update existing bonus log
		affwp_update_affiliate_meta( $affiliate_id, 'bonus_'. $bonus_id .'_log', $bonus_log );
		
		if( $bonus_log == 0 ){
			// Make sure that we don't create duplicate logs
			affwp_pb_delete_bonus_log( $affiliate_id, $bonus_id );
		}
		
		return true;
	}
}

/**
 * Get the Affiliate's Bonus Log for a Given Bonus
 *
 * @since  1.0
 * @return int
 */
function affwp_pb_get_bonus_log( $affiliate_id = 0, $bonus_id = 0 ) {
	
	if( empty( $affiliate_id ) || empty( $bonus_id ) ) {
		return;
	}
	
	// Returns an array
	$bonus_log = affwp_get_affiliate_meta( $affiliate_id, 'bonus_'. $bonus_id .'_log' );
	
	if( ! (int)$bonus_log[0] )
		$bonus_log = 0;
		
	return $bonus_log;
}

/**
 * Delete the Affiliate's Bonus Log for a Given Bonus
 *
 * @since  1.0
 * @return void
 */
function affwp_pb_delete_bonus_log( $affiliate_id = 0, $bonus_id = 0 ) {
	
	if( empty( $affiliate_id ) || empty( $bonus_id ) ) {
		return;
	}
	
	affwp_delete_affiliate_meta( $affiliate_id, 'bonus_'. $bonus_id .'_log' );

}