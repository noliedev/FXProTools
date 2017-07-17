<?php

function eps_affiliates_install() {

	// Create affiliate caps
	$roles = new Eps_affiliates_Capabilities;
	$roles->add_caps();

	//install tables
	// $afl_tables = new Eps_affiliates_tables();

	//set the variable
	update_option( 'eps_afl_is_installed', '1' );
	$Eps_affiliate_install  = new stdClass();
}

register_activation_hook( EPSAFFILIATES_PLUGIN_FILE, 'eps_affiliates_install' );

/*
 * ------------------------------------------------------------
 * Check the plugin installed or not
 * ------------------------------------------------------------
 *
*/
function eps_affiliate_check_if_installed() {
	// this is mainly for network activated installs
	if( ! get_option( 'eps_afl_is_installed' ) ) {
		eps_affiliates_install();
	}
}
add_action( 'admin_init', 'eps_affiliate_check_if_installed' );
