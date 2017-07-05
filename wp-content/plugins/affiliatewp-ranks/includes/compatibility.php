<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


// Run if AffiliateWP Performance Bonuses is Active
if ( class_exists( 'AffiliateWP_Performance_Bonuses' ) ) {

	/**
	 * Adds Rank Promotion to the Bonus Types List
	 *
	 * @since 1.0
	 * @return array
	 */
	function affwp_pb_add_rank_promotion_type( $types = array() ) {
		
		$ranks_type = array(
			'rank_promotion'  => __( 'Rank Promotion', 'affiliatewp-ranks' )
		);
		$types = array_merge( $types, $ranks_type );
		
		return $types;
	}
	add_filter( 'affwp_pb_get_bonus_types', 'affwp_pb_add_rank_promotion_type', 10, 1 );
	
	/**
	 * Check for bonuses based on rank promotion
	 * 
	 * @since 1.0
	 */
	function affwp_pb_check_for_rank_promotion_bonus( $affiliate_id = 0, $current_rank_id = 0, $last_rank_id = 0 ) {
		
		if ( empty( $affiliate_id ) ) {
			return;
		}
		
		if ( empty( $last_rank_id ) )
			affwp_ranks_get_affiliate_last_rank( $affiliate_id );
		
		if ( empty( $current_rank_id ) )
			affwp_ranks_get_affiliate_rank( $affiliate_id );
		
		// Exclude bonuses that have already been earned	
		$bonuses = apply_filters( 'affwp_pb_get_active_bonuses', get_active_bonuses(), $affiliate_id );
		
		// Stop if all bonuses have been earned already
		if( empty( $bonuses ) ) {
			return;
		}
		
		foreach( $bonuses as $key => $bonus ) {
			
			// Check for rank promotion bonus
			if( $bonus['type'] == 'rank_promotion' ) {
			
				// Check if the affiliate's rank matches the required rank
				if( $current_rank_id == $bonus['requirement'] ) {
				
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
	add_action( 'affwp_ranks_affiliate_rank_promoted', 'affwp_pb_check_for_rank_promotion_bonus', 10, 3 );

}

// Run if AffiliateWP MLM is Active
if ( class_exists( 'AffiliateWP_Multi_Level_Marketing' ) ) {

	/**
	 * Adds Rank as Option for Disabled MLM Affiliate Data 
	 *
	 * @since 1.0
	 * @return array
	 */
	function affwp_mlm_add_disabled_aff_rank_data( $aff_data = array() ) {
		
		$disabled_aff_data_ranks = array(
			'rank'  => __( 'Rank', 'affiliatewp-ranks' )
		);

		$aff_data = array_merge( $aff_data, $disabled_aff_data_ranks );
	
		return $aff_data;
	}
	add_filter( 'affwp_mlm_aff_data_disabled', 'affwp_mlm_add_disabled_aff_rank_data', 10, 1 );

	/**
	 * Add Rank Data to MLM Affiliate Data
	 *
	 * @since 1.0.2
	 * @return array
	 */
	function affwp_mlm_add_aff_rank_data( $aff_data = array(), $affiliate_id = 0 ) {
		
		if ( empty( $affiliate_id ) ) return $aff_data;
		
		$rank_id = affwp_ranks_get_affiliate_rank( $affiliate_id );
		$rank_data = get_rank_by_id( $rank_id );
		$rank_name = $rank_data[0]['name'];

		$next_rank_id = affwp_ranks_get_affiliate_next_rank( $affiliate_id );
		$next_rank_data = get_rank_by_id( $next_rank_id );
		$next_rank_name = $next_rank_data[0]['name'];	

		$last_rank_id = affwp_ranks_get_affiliate_last_rank( $affiliate_id );
		$last_rank_data = get_rank_by_id( $last_rank_id );
		$last_rank_name = $last_rank_data[0]['name'];		
		
		$aff_rank_data = array(
						'rank' => array(
							'title'    => __( 'Rank', 'affiliatewp-ranks' ),
							'icon'     => 'fa-star',
							'content'  => array(						
								'current' => $rank_name,
								'next'    => $next_rank_name,
								'last'    => $last_rank_name,
							)
						)
					);
		$aff_data = array_merge( $aff_data, $aff_rank_data );
		
		return $aff_data;
	}
	add_filter( 'affwp_mlm_aff_data', 'affwp_mlm_add_aff_rank_data', 10, 2 );

	/**
	 * Get Per-Level Rank Rate for the Parent
	 *
	 * @since 1.1
	 */
	function get_parent_rank_rate( $rate, $product_id = 0, $args = array(), $affiliate_id = 0 , $context = '', $parent_affiliate_id = 0 , $level_count = 0 ) {	 

		if ( empty( $parent_affiliate_id ) || empty( $level_count ) )
			return $rate;
		
		$ranks = AffiliateWP_Ranks_Settings::get_ranks();
		
		// Make sure there are ranks setup 
		if ( empty( $ranks ) ) {
			return $rate;
		}

		if ( $ranks && ! empty( $ranks ) ) {
			
			$aff_rank_id = affwp_ranks_get_affiliate_rank( $parent_affiliate_id );
			
			foreach( $ranks as $rank_key => $rank ) {
	
				$rank_id = $rank['id'];
				
				// Check for the parent affiliate's rank
				if ( $aff_rank_id == $rank_id ) {					
				
					$mlm_rank_rates = AffiliateWP_Ranks_Settings::get_level_rank_rates();

					if ( isset( $mlm_rank_rates[$rank_key] ) ) {
						
						// Index the arrays numerically
						$mlm_rank_rates[$rank_key] = array_values( $mlm_rank_rates[$rank_key] );

						// Match the level count by offseting array values to start from 1
						array_unshift( $mlm_rank_rates[$rank_key], '' );

						$mlm_rank_rates_rate = $mlm_rank_rates[$rank_key][$level_count]['rate'];

						// Use the rate passed if no per-level rank rate is set
						$rate = isset( $mlm_rank_rates_rate ) ? $mlm_rank_rates_rate : $rate;

					}
				}
			}
		}

		return apply_filters( 'affwp_mlm_get_affiliate_rank_rate', (float) $rate, $product_id, $args, $affiliate_id, $context, $parent_affiliate_id, $level_count );

	}
	add_filter( 'affwp_mlm_get_affiliate_rate', 'get_parent_rank_rate', 10, 7 );

	/**
	 * Get Per-Level Rank Rate Type for the Parent
	 *
	 * @since 1.1
	 */
	function get_parent_rank_rate_type( $type = '', $product_id = 0, $args = array(), $affiliate_id = 0 , $context = '', $parent_affiliate_id = 0 ) {	 

		if ( empty( $parent_affiliate_id ) )
			return $type;

		// Check for indirect referral
		if ( ( 'indirect' !== $args['custom'] ) ) {
			return $type;
		}

		// Get the per-level rank rate type
		$rank_mlm_rate_type = affiliate_wp()->settings->get( 'affwp_ranks_mlm_referral_rate_type' );	
		
		// Use the passed rate type if no per-level rank rate type is set
		if ( ! empty( $rank_mlm_rate_type ) ) {
			$type = $rank_mlm_rate_type;
	
			return apply_filters( 'affwp_mlm_get_affiliate_rank_rate_type', (string) $type, $product_id, $args, $affiliate_id, $context, $parent_affiliate_id );
		} else {
			return $type;	
		}

	}
	add_filter( 'affwp_mlm_get_affiliate_rate_type', 'get_parent_rank_rate_type', 10, 6 );
}