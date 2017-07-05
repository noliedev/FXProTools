<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Force the frontend scripts to load on affiliate area shortcode tabs
 * 
 * @since  1.0
 */
if ( !function_exists( 'affwp_aas_force_frontend_scripts' ) && !function_exists( 'affwp_bp_force_frontend_scripts' ) && !function_exists( 'affwp_mlm_force_frontend_scripts' ) ) {
	function affwp_pb_force_frontend_scripts( $ret ) {
		global $post;
		
		if ( has_shortcode( $post->post_content, 'affiliate_area_bonuses' ) ) {
			$ret = true;
		}
	}
	add_filter( 'affwp_force_frontend_scripts', 'affwp_pb_force_frontend_scripts' );
}

/**
* [affiliate_area_bonuses] shortcode
*
* @since  1.0
*/
function affwp_aas_affiliate_bonuses_shortcode( $atts, $content = null ) {
	if ( ! ( is_user_logged_in() && affwp_is_affiliate() ) ) {
		return $content;
	}
	ob_start();
	echo '<div id="affwp-affiliate-dashboard">';
	affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'bonuses' );
	echo '</div>';
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'affiliate_area_bonuses', 'affwp_aas_affiliate_bonuses_shortcode' );


// Run if AffiliateWP Ranks is Active
if ( class_exists( 'AffiliateWP_Ranks' ) ) {
	
	/**
	 * Check for ranks based on referrals
	 * 
	 * @since 1.0
	 */
	function affwp_ranks_check_for_referral_rank( $referral_id ) {
	
		$referral = affwp_get_referral( $referral_id );
		
		// Stop if this is a bonus referral
		if( $referral->custom == 'performance_bonus' ) {
			return;
		}
		
		$ranks = get_ranks();
		
		// Make sure there are ranks setup 
		if( empty( $ranks ) ) {
			return;
		}
	
		$affiliate_id = $referral->affiliate_id;	
		
		if( function_exists( 'affwp_pb_get_affiliate_referral_count' ) ) {
			$referral_count = affwp_pb_get_affiliate_referral_count( $affiliate_id );
		} else{
			return;
		}
		
		foreach( $ranks as $key => $rank ) {
			
			// Skip this rank if it's the affiliate's current rank
			if( affwp_ranks_has_rank( $affiliate_id, $rank['id'] ) )
				continue;
			
			// Check for referral based rank
			if( $rank['type'] == 'referral' ) {

				// Check if the affiliate has met the requirements
				if( $referral_count >= $rank['requirement'] ) {

					$rank_id = $rank['id'];
					
					// Set the affiliate's rank
					affwp_ranks_set_affiliate_rank( $affiliate_id, $rank_id );
				
				}
			}
		}
	}
	add_action( 'affwp_pb_bonus_check_on_paid_referral', 'affwp_ranks_check_for_referral_rank', 10, 1 );
	add_action( 'affwp_pb_bonus_check_on_unpaid_referral', 'affwp_ranks_check_for_referral_rank', 10, 1 );
	
	/**
	 * Check for ranks based on earnings
	 * 
	 * @since 1.0
	 */
	function affwp_ranks_check_for_earnings_rank( $referral_id ) {
	
		$referral = affwp_get_referral( $referral_id );
		
		// Stop if this is a bonus referral
		if( $referral->custom == 'performance_bonus' ) {
			return;
		}
		
		$ranks = get_ranks();
		
		// Make sure there are ranks setup 
		if( empty( $ranks ) ) {
			return;
		}
	
		$affiliate_id = $referral->affiliate_id;	
		
		if( function_exists( 'affwp_pb_get_affiliate_earnings_count' ) ) {
			$earnings = affwp_pb_get_affiliate_earnings_count( $affiliate_id );
		} else{
			return;
		}
		
		foreach( $ranks as $key => $rank ) {
			
			// Skip this rank if it's the affiliate's current rank
			if( affwp_ranks_has_rank( $affiliate_id, $rank['id'] ) )
				continue;
			
			// Check for earnings based rank
			if( $rank['type'] == 'earnings' ) {
			
				// Check if the affiliate has met the requirements
				if( $earnings >= $rank['requirement'] ) {	
					
					$rank_id = $rank['id'];
					
					// Set the affiliate's rank
					affwp_ranks_set_affiliate_rank( $affiliate_id, $rank_id );
				
				}
			}
		}
	}
	add_action( 'affwp_pb_bonus_check_on_paid_referral', 'affwp_ranks_check_for_earnings_rank', 10, 1 );
	add_action( 'affwp_pb_bonus_check_on_unpaid_referral', 'affwp_ranks_check_for_earnings_rank', 10, 1 );
	add_action( 'affwp_pb_bonus_check_on_pending_referral', 'affwp_ranks_check_for_earnings_rank', 10, 1 );
	
}

// Run if AffiliateWP Group Commissions is Active
if ( class_exists( 'AffiliateWP_Group_Commissions' ) ) {
	
	/**
	 * Check for group commissions based on group referrals
	 * 
	 * @since 1.0
	 */
	function affwp_gc_check_for_referral_group_commission( $referral_id ) {
	
		$referral = affwp_get_referral( $referral_id );
		
		// Stop if this is a bonus referral
		if( $referral->custom == 'performance_bonus' ) {
			return;
		}
		
		// Stop if this is a group commission referral
		if( $referral->custom == 'group_commission' ) {
			return;
		}
		
		$group_commissions = get_group_commissions();
		
		// Make sure there are group commissions setup 
		if( empty( $group_commissions ) ) {
			return;
		}
	
		$affiliate_id = affwp_mlm_get_parent_affiliate( $referral->affiliate_id );	
		
		if( function_exists( 'affwp_pb_get_affiliate_referral_count' ) ) {
			
			$referral_count = affwp_gc_get_group_referral_count( $affiliate_id );
			
		} else{
			return;
		}
		
		foreach( $group_commissions as $key => $group_commission ) {
			
			// Skip this if the affiliate has already earned the commission
			if( affwp_gc_has_earned_group_commission( $affiliate_id, $group_commission['id'] ) )
				continue;
			
			// Check for group commissions of the group referral type
			if( $group_commission['type'] == 'group_referral' ) {

				// Check if the affiliate has met the requirements
				if( $referral_count >= $group_commission['requirement'] ) {
					
					$earnings_count = affwp_gc_get_group_earnings_count( $affiliate_id );
					$group_commission_amount = affwp_gc_calc_group_commission_amount( $group_commission['rate'], $earnings_count );
					
					// Create the group commission
					affwp_gc_create_group_commission( $affiliate_id, $group_commission['id'], $group_commission['title'], $group_commission_amount );
				
				}
			}
		}
	}
	add_action( 'affwp_pb_bonus_check_on_paid_referral', 'affwp_gc_check_for_referral_group_commission', 10, 1 );
	add_action( 'affwp_pb_bonus_check_on_unpaid_referral', 'affwp_gc_check_for_referral_group_commission', 10, 1 );
	
	/**
	 * Check for group commissions based on group earnings
	 * 
	 * @since 1.0
	 */
	function affwp_gc_check_for_earnings_group_commission( $referral_id ) {
	
		$referral = affwp_get_referral( $referral_id );
		
		// Stop if this is a bonus referral
		if( $referral->custom == 'performance_bonus' ) {
			return;
		}

		// Stop if this is a group commission referral
		if( $referral->custom == 'group_commission' ) {
			return;
		}
		
		$group_commissions = get_group_commissions();
		
		// Make sure there are group commissions setup 
		if( empty( $group_commissions ) ) {
			return;
		}
	
		$affiliate_id = affwp_mlm_get_parent_affiliate( $referral->affiliate_id );	
		
		if( function_exists( 'affwp_pb_get_affiliate_earnings_count' ) ) {
			
			$earnings_count = affwp_gc_get_group_earnings_count( $affiliate_id );
			
		} else{
			return;
		}
		
		foreach( $group_commissions as $key => $group_commission ) {
			
			// Skip this if the affiliate has already earned the commission
			if( affwp_gc_has_earned_group_commission( $affiliate_id, $group_commission['id'] ) )
				continue;
			
			// Check for group commissions of the group referral type
			if( $group_commission['type'] == 'group_earnings' ) {

				// Check if the affiliate has met the requirements
				if( $earnings_count >= $group_commission['requirement'] ) {
					
					$group_commission_amount = affwp_gc_calc_group_commission_amount( $group_commission['rate'], $group_commission['requirement'] );
					
					// Create the group commission
					affwp_gc_create_group_commission( $affiliate_id, $group_commission['id'], $group_commission['title'], $group_commission_amount );
				
				}
			}
		}
	}
	add_action( 'affwp_pb_bonus_check_on_paid_referral', 'affwp_gc_check_for_earnings_group_commission', 10, 1 );
	add_action( 'affwp_pb_bonus_check_on_unpaid_referral', 'affwp_gc_check_for_earnings_group_commission', 10, 1 );
	add_action( 'affwp_pb_bonus_check_on_pending_referral', 'affwp_gc_check_for_earnings_group_commission', 10, 1 );

}