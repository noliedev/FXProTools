<?php
/**
 * --------------
 * Theme Settings
 * --------------
 * Theme related settings
 */

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('WoocommerceSettings')){

	class WoocommerceSettings {
		
		public function __construct()
		{
			add_action('woocommerce_thankyou', array($this, 'wc_after_checkout_redirect'));
		}

		public function wc_after_checkout_redirect( $order_id )
		{
		    $order = new WC_Order( $order_id );
		    $url = home_url(). '/dashboard';
		    if ( $order->status != 'failed' ) {
		        wp_redirect($url);
		        exit;
		    }
		}

	}
}

return new WoocommerceSettings();