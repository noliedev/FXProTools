<?php 
/* --------- All the action hooks ------------------------*/


/*
 * ----------------------------------------------------------
 *  Check eps-afl installed or not
 * ----------------------------------------------------------
*/
	add_action( 'admin_init', 'eps_affiliate_check_if_installed' );
/*
 * ------------------------------------------------------------
 * Install features on install plugin 
 * ------------------------------------------------------------
*/
	register_activation_hook( EPSAFFILIATE_PLUGIN_FILE, 'eps_affiliates_install'  );
/*
 * ------------------------------------------------------------
 * Disable features on un-install plugin 
 * ------------------------------------------------------------
*/
	register_deactivation_hook( EPSAFFILIATE_PLUGIN_FILE, 'eps_affiliates_uninstall'); 
/*
 * ------------------------------------------------------------
 * Set the content of the page eps_affiliates
 * ------------------------------------------------------------
*/
	add_shortcode('eps_affiliates', 'afl_eps_afiliate_dashboard_shortcode');
/*
 * ------------------------------------------------------------
 * Create widget
 * ------------------------------------------------------------
*/
	add_action( 'widgets_init', 'eps_affiliates_dashboard_menu_widget' );
/*
 * ------------------------------------------------------------
 * 
 * ------------------------------------------------------------
*/
	add_action( 'eps_account_content', 'eps_account_content' );
	
/*
 * ------------------------------------------------------------
 * Replace the menu icons using css
 * ------------------------------------------------------------
*/
	add_action( 'admin_head', 'replace_afl_eps_custom_pages_icons' );
/*
 * ------------------------------------------------------------
 * Users autocomplete initialization
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_users_auto_complete', 'users_auto_complete_callback');
 add_action('wp_ajax_nopriv_users_auto_complete', 'users_auto_complete_callback');

/*
 * ------------------------------------------------------------
 * Get the admin bar
 * ------------------------------------------------------------
*/
	add_filter('woocommerce_disable_admin_bar', '_wc_disable_admin_bar', 10, 1);
	 
	function _wc_disable_admin_bar($prevent_admin_access) {
	 
	    return false;
	}

/*
 * -------------------------------------------------------------
 * Get the dashboard
 * -------------------------------------------------------------
*/
	add_filter('woocommerce_prevent_admin_access', '_wc_prevent_admin_access', 10, 1);
	 
	function _wc_prevent_admin_access($prevent_admin_access) {
	    if (!current_user_can('eps_system_member')) {
	        return $prevent_admin_access;
	    }
	    return false;
	}
/*
 * ------------------------------------------------------------
 * Users downline users datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_downlines_data_table', 'afl_user_downlines_data_table_callback');
 add_action('wp_ajax_nopriv_afl_user_downlines_data_table', 'afl_user_downlines_data_table_callback');

/*
 * ------------------------------------------------------------
 * Genealogy tree expand
 * ------------------------------------------------------------
*/
	add_action('wp_ajax_afl_user_expand_genealogy', 'afl_expand_user_genealogy_tree');
 	add_action('wp_ajax_nopriv_afl_user_expand_genealogy', 'afl_expand_user_genealogy_tree');