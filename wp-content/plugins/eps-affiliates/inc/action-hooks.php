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

/*
 * ------------------------------------------------------------
 * E wallet summary datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_ewallet_summary_data_table', 'afl_user_ewallet_summary_data_table_callback');
 add_action('wp_ajax_nopriv_afl_user_ewallet_summary_data_table', 'afl_user_ewallet_summary_data_table_callback');

/*
 * ------------------------------------------------------------
 * E wallet all transaction datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_ewallet_all_transaction_data_table', 'afl_user_ewallet_all_transaction_data_table');
 add_action('wp_ajax_nopriv_afl_user_ewallet_all_transaction_data_table', 'afl_user_ewallet_all_transaction_data_table');

/*
 * ------------------------------------------------------------
 * 
 * ------------------------------------------------------------
*/
 add_action( 'admin_print_styles', '_load_common_styles');
 //our custom styles only applied to the following pages only
 function _load_common_styles () {
 	$page = isset($_GET['page']) ?$_GET['page'] : '';
 	$common_include = new Eps_affiliates_common;
 	if ($page) {
 		switch ($page) {
 			//dashboard
 			case 'eps-dashboard':
 			//network
 			case 'user-network':
 			case 'downline-members':
 			case 'genealogy-tree':
 			//e-wallet
 			case 'e-wallet-summary':
 			case 'e-wallet':
 			case 'ewallet-all-transactions':
 			case 'ewallet-income-report':
 			case 'ewallet-withdraw-report':
 			case 'ewallet-withdraw-fund':
 			case 'ewallet-pending-withdrawal':

 			//system configurations
 			case 'business-system-members':
 			case 'system-configurations':
 			case 'compensation-plan-configurations':
 			case 'rank-configurations':
 			case 'role-config-settings':
 			case 'genealogy-configurations':
 			case 'payout-configurations':
 			case 'pool-bonus-configurations':
 			case 'payment-method-configurations':
 				wp_enqueue_style( 'app', EPSAFFILIATE_PLUGIN_ASSETS.'css/app.css');
				wp_enqueue_style( 'developer', EPSAFFILIATE_PLUGIN_ASSETS.'css/developer.css');
			break;
			
 		}
 	}
 }