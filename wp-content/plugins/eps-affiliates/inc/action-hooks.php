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
<<<<<<< HEAD
	
=======
/*
 * ------------------------------------------------------------
 * Create some roles on init
 * ------------------------------------------------------------
*/
 	add_action('init', 'add_roles_on_init' );

	function add_roles_on_init() {
	  add_role( 'afl_member', 'AFL Member', array( 'read' => true, 'level_0' => true,'level_1' => true) );
	}
>>>>>>> a3eb117dca110ed02010bf0895e5c78cdae5e735
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
<<<<<<< HEAD

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


=======
>>>>>>> a3eb117dca110ed02010bf0895e5c78cdae5e735
