<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * [affiliate_rank] shortcode
 *
 * @since  1.0.2
 */
function affwp_affiliate_rank_shortcode( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'affiliate_id' 	=> affwp_get_affiliate_id(),
		'state' 		=> 'current',
		'show' 			=> 'rank'
		
	), $atts ) );

	ob_start();
	
	show_affiliate_rank( $affiliate_id, $state, $show );
	
	$content = ob_get_clean();
	return $content;
}
add_shortcode( 'affiliate_rank', 'affwp_affiliate_rank_shortcode' );