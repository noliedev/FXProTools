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
			add_filter('wc_authorize_net_cim_credit_card_payment_form_save_payment_method_checkbox_html', array($this,'wc_auth_net_cim_save_payment_method_default_checked'), 10, 2 );
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

		function wc_auth_net_cim_save_payment_method_default_checked( $html, $form ) {
			if ( empty( $html ) || $form->tokenization_forced() ) {
				return $html;
			}
			
			return str_replace( 'type="checkbox"', 'type="checkbox" checked="checked"', $html );
		}
		

	}
}

return new WoocommerceSettings();