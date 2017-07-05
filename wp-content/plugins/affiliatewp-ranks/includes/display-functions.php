<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display an affiliate's rank
 *
 * @since  1.0.2
 */
function show_affiliate_rank( $affiliate_id = 0, $state = '', $show = '' ) {

	if ( empty( $affiliate_id ) ) return;

	// Show the rank
	if ( $show == 'rank' ) {
		show_affiliate_rank_name( $affiliate_id, $state );
	}
	
	// Show the rank table
	if ( $show == 'table' ) {
		show_affiliate_rank_table( $affiliate_id );
	}
	
}

/**
 * Display an affiliate's rank (Name)
 *
 * @since  1.0.2
 */
function show_affiliate_rank_name( $affiliate_id = 0, $state = '' ) {

	if ( empty( $affiliate_id ) ) $affiliate_id = affwp_get_affiliate_id();
	
	if ( empty( $affiliate_id ) ) return;

	if ( empty( $state ) ) $state = 'current';

	// Show current rank
	if ( $state == 'current' )
		$rank_id = affwp_ranks_get_affiliate_rank( $affiliate_id );
	
	// Show last rank
	if ( $state == 'last' )
		$rank_id = affwp_ranks_get_affiliate_last_rank( $affiliate_id );
	
	// Show next rank
	if ( $state == 'next' )
		$rank_id = affwp_ranks_get_affiliate_next_rank( $affiliate_id );
	
	$rank_data = get_rank_by_id( $rank_id );
	$rank_name = $rank_data[0]['name'];
	
	if ( empty( $rank_id ) || empty( $rank_name ) ) $rank_name = 'None';
	
	?>
	
	<span class="affwp-ranks-aff-rank"><?php echo $rank_name; ?></span>
	
	<?php
	
}

/**
 * Display an affiliate's rank Table (Last, Current, & Next)
 *
 * @since  1.0.2
 */
function show_affiliate_rank_table( $affiliate_id = 0 ) {
	
	if ( empty( $affiliate_id ) ) $affiliate_id = affwp_get_affiliate_id();	
	
	if ( empty( $affiliate_id ) ) return;
	
	// Get affiliate's rank data
	$rank_id = affwp_ranks_get_affiliate_rank( $affiliate_id );
	$rank_data = get_rank_by_id( $rank_id );
	$rank_order = $rank_data[0]['order'];
	$rank_name = $rank_data[0]['name'];
	$rank_mode = $rank_data[0]['mode'];
	$rank_type = $rank_data[0]['type'];
	$rank_requirement = $rank_data[0]['requirement'];
	$rank_rate = $rank_data[0]['rate'];
	$rank_rate_type = $rank_data[0]['rate_type'];
	
	// Get last rank
	$last_rank_id = affwp_ranks_get_affiliate_last_rank( $affiliate_id );
	$last_rank_data = get_rank_by_id( $last_rank_id );
	$last_rank_order = $last_rank_data[0]['order'];
	$last_rank_name = $last_rank_data[0]['name'];
	$last_rank_mode = $last_rank_data[0]['mode'];
	$last_rank_type = $last_rank_data[0]['type'];
	$last_rank_requirement = $last_rank_data[0]['requirement'];
	$last_rank_rate = $last_rank_data[0]['rate'];
	$last_rank_rate_type = $last_rank_data[0]['rate_type'];
	
	// Get next rank
	$next_rank_id = affwp_ranks_get_affiliate_next_rank( $affiliate_id );
	$next_rank_data = get_rank_by_id( $next_rank_id );
	$next_rank_order = $next_rank_data[0]['order'];
	$next_rank_name = $next_rank_data[0]['name'];
	$next_rank_mode = $next_rank_data[0]['mode'];
	$next_rank_type = $next_rank_data[0]['type'];
	$next_rank_requirement = $next_rank_data[0]['requirement'];
	$next_rank_rate = $next_rank_data[0]['rate'];
	$next_rank_rate_type = $next_rank_data[0]['rate_type'];
	 
	?>
    <h4><?php _e( 'Affiliate Rank', 'affiliatewp-ranks' ); ?></h4>

    <table class="affwp-table table">
        <thead>
            <tr>
                <th><?php _e( 'Last Rank', 'affiliatewp-ranks' ); ?></th>
                <th><?php _e( 'Current Rank', 'affiliatewp-ranks' ); ?></th>
                <th><?php _e( 'Next Rank', 'affiliatewp-ranks' ); ?></th>
            </tr>
        </thead>

        <tbody>
            
            <?php 
                if ( empty( $last_rank_name ) ) $last_rank_name = 'None'; 
                if ( empty( $rank_name ) ) $rank_name = 'None'; 
                if ( empty( $next_rank_name ) ) $next_rank_name = 'None'; 
            ?>
            
            <tr>
                <td><?php echo $last_rank_name; ?></td>
                <td><?php echo $rank_name; ?></td>
                <td><?php echo $next_rank_name; ?></td>
            </tr>

        </tbody>
    </table>
	<?php
}