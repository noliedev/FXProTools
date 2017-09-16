<?php
/**
 * -------------------
 * FXprotools Settings
 * -------------------
 * Fxprotools theme settings
 */

// Set the theme version number as a global variable
$theme          = wp_get_theme('fxprotools-theme');
$theme_version	= $theme['Version'];
$core_settings = [
	'core-admin-settings.php',
	'core-theme-settings.php',
];

function my_enqueue($hook) {
	wp_enqueue_script('custom_js_script', get_bloginfo('template_url').'/assets/js/custom_script.js', array('jquery'));
}

add_action('admin_enqueue_scripts', 'my_enqueue');
	
	
foreach ($core_settings as $cs) {
	require_once('inc/core/'.$cs);
}

function my_login_redirect( $url, $request, $user ){
    if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
        if( $user->has_cap( 'administrator' ) ) {
            $url = admin_url();
        } else {
			if(IS_LOCAL)
				$url = home_url('/index.php/dashboard/');
			else
				$url = home_url('/dashboard/');
        }
    }
    return $url;
}

add_filter('login_redirect', 'my_login_redirect', 10, 3 );

function redirect_login_page() {

    if( is_page( 'login' ) && is_user_logged_in() ) {
        wp_redirect( home_url() );
        exit;
    }

}
add_action( 'template_redirect', 'redirect_login_page' );

function active_subscription_list($from_date=null, $to_date=null) {

    // Get all customer orders
    $subscriptions = get_posts( array(
        'numberposts' => -1,
        'post_type'   => 'shop_subscription', // Subscription post type
        'post_status' => 'wc-in-active', // Active subscription
        'post_author' => '2913', // by user_id
        'orderby' => 'post_date', // ordered by date
        'order' => 'ASC',
        'date_query' => array( // Start & end date
            array(
                'after'     => $from_date,
                'before'    => $to_date,
                'inclusive' => true,
            ),
        ),
    ) );
    
    //return $subscriptions;

    // Styles (temporary, only for demo display) should be removed
    echo "<style>
        .subscription_list th, .subscription_list td{border:solid 1px #666; padding:2px 5px;}
        .subscription_list th{font-weight:bold}
        .subscription_list td{text-align:center}
    </style>";

    // Displaying list in an html table
    echo "<table class='shop_table subscription_list'>
        <tr>
            <th>" . __( 'User ID', 'your_theme_domain' ) . "</th>
            <th>" . __( 'User Name', 'your_theme_domain' ) . "</th>
            <th>" . __( 'Subscription Id', 'your_theme_domain' ) . "</th>
            <th>" . __( 'Date', 'your_theme_domain' ) . "</th>
        </tr>
            ";
    // Going through each current customer orders
    foreach ( $subscriptions as $subscription ) {
        $subscription_date = array_shift( explode( ' ', $subscription->post_date ) ); // subscription date
        $subscr_meta_data = get_post_meta($subscription->ID); // subscription meta data
        $customer_id = $subscr_meta_data['_customer_user'][0]; // customer ID
        $customer_name = $subscr_meta_data['_billing_first_name'][0] . ' ' . $subscr_meta_data['_billing_last_name'][0]; // customer name
        $wc_authorizeddotnet_gateway_subscription_id = $subscr_meta_data['wc_authorizeddotnet_gateway_subscription_id'][0];	// subscription ID
        echo "</tr>
				<td>$customer_id</td>
                <td>$customer_name</td>
                <td>$wc_authorizeddotnet_gateway_subscription_id</td>
                <td>$subscription_date</td>
            </tr>";
    }
    echo '</table>';
}

/**
 * -------------------
 * Metabox Extesntions
 * -------------------
 * Force Loading of metabox plugin and extensions
 */
$mb_extenstions = array(
	// Core Plugin
	'meta-box.php',
	// Extensions
	'extensions/mb-admin-columns/mb-admin-columns.php',
	'extensions/mb-settings-page/mb-settings-page.php',
	'extensions/mb-term-meta/mb-term-meta.php',
	'extensions/meta-box-builder/meta-box-builder.php',
	'extensions/meta-box-columns/meta-box-columns.php',
	'extensions/meta-box-conditional-logic/meta-box-conditional-logic.php',
	'extensions/meta-box-group/meta-box-group.php',
	'extensions/meta-box-include-exclude/meta-box-include-exclude.php',
	'extensions/meta-box-show-hide/meta-box-show-hide.php',
	'extensions/meta-box-tabs/meta-box-tabs.php',
	// 'extensions/meta-box-template/meta-box-template.php',
	'extensions/meta-box-tooltip/meta-box-tooltip.php',
);

if($mb_extenstions){
	foreach ($mb_extenstions as $key => $ext) {
		require_once('inc/core/meta-box/'.$ext);
	}
}

/**
 * ----------------
 * Custom Functions
 * ----------------
 * Includes all custom functions
 */
$custom_functions = array(
	'function-helper.php',
	'function-custom.php'
);

if($custom_functions){
	foreach($custom_functions as $key => $cf){
		require_once('inc/'.$cf);
	}
}


/**
 * ---------------------------------------------------
 * ANET - Customer Informatio and Subscription Manager
 * ---------------------------------------------------
 * Authorize.net customer information and subscription manager
 */
// $anet_includes = [
	// 'auth-api.php',
	// 'auth-ajax.php',
// ];
// foreach ($anet_includes as $a) {
// 	require_once('inc/authorize-net/'.$a);
// }

/**
 * --------------
 * ANET - Payment 
 * --------------
 * Authorize.net payment gateway class
 */
require('inc/odz-authorizeddotnet-payment-gateway/odz-authorizeddotnet-payment-gateway.php');
