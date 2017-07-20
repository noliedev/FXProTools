<?php 
/*
 * ------------------------------------------------------------
 * Un-Install functions and Features 
 * ------------------------------------------------------------
*/
	function eps_affiliates_uninstall() {
		if (get_option( 'eps_afl_is_installed' ) ) {
			update_option( 'eps_afl_is_installed', '0' );
		}
	}