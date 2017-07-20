<?php
/*
 * ------------------------------------------------------------
 * Install functions and Features 
 * ------------------------------------------------------------
*/
function eps_affiliates_install() {
	// Create affiliate caps
	$roles = new Eps_affiliates_Capabilities;
	$roles->add_caps();

	//install tables
	$afl_tables = new Eps_affiliates_tables;

	//create page 
	if ( ! get_option( 'eps_afl_is_installed' ) ) {
		$affiliate_area = wp_insert_post(
			array(
				'post_title'     => __( 'EPS-Affiliates', 'eps-affiliates' ),
				'post_content'   => '[eps_affiliates]',
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id(),
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);
	}
	//set the variable
	update_option( 'eps_afl_is_installed', '1' );
	$Eps_affiliate_install  = new stdClass();
}
/*
 * ------------------------------------------------------------
 * Check the plugin installed or not
 * ------------------------------------------------------------
*/
	function eps_affiliate_check_if_installed() {
		// this is mainly for network activated installs
		if( ! get_option( 'eps_afl_is_installed' ) ) {
			eps_affiliates_install();
		}
	}
