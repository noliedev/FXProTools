<?php
/**
 * EPS Affiliates Template Hooks
 *
 * Action/filter hooks used for WooCommerce functions/templates.
 *
 * @author 		Epixel
 * @category 	Core
 * @package 	eps-affiliates/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Afl dashboard page.
 *
 * @param array $atts
*/
	function afl_dashboard( ) {
		
		afl_get_template( 'eps-affiliates.php', array(
		) );
	}
echo afl_dashboard();
add_action( 'eps_account_content', 'eps_account_content' );