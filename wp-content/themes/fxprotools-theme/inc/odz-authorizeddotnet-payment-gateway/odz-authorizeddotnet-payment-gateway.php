<?php
/**
 * Plugin Name:       Ourdesignz authorizeddotnet Payment Gateway Integration
 * Plugin URI:        http://www.ourdesignz.com/
 * Description:       Ourdesignz authorizeddotnet Payment Gateway Integration allows you to accept payments on your Woocommerce store with your authorizeddotnet merchant account.
 * Version:           1.2
 * Author:            Ourdesignz
 * Author URI:        http://www.ourdesignz.com/
 * Text Domain:       odz-authorizeddotnet-payment-gateway
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Begins execution of the plugin.
 */
add_action('init', 'init_odz_authorizeddotnet_payment_gateway');
register_activation_hook(__FILE__, 'activate_odz_authorizeddotnet_payment_gateway');
register_deactivation_hook(__FILE__, 'deactivate_odz_authorizeddotnet_payment_gateway');

require 'sample-code-php/vendor/autoload.php';

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
date_default_timezone_set('America/Los_Angeles');

define("AUTHORIZENET_LOG_FILE", "phplog");

function init_odz_authorizeddotnet_payment_gateway() {
    /**
     * Tell WooCommerce that authorizeddotnet class exists 
     */
    function add_odz_authorizeddotnet_payment_gateway($methods) {
        $methods[] = 'Odz_authorizeddotnet_payment_gateway';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_odz_authorizeddotnet_payment_gateway');



    if (!class_exists('WC_Payment_Gateway'))
        return;

    /**
     * authorizeddotnet gateway class
     */
    class Odz_authorizeddotnet_payment_gateway extends WC_Payment_Gateway {

        /**
         * Constructor
         */
        public function __construct() {
            $this->id = 'odz_authorizeddotnet_payment_gateway';
            $this->icon = apply_filters('woocommerce_authorizeddotnet_icon', plugins_url('images/cards.png', __FILE__));
            $this->has_fields = true;
            $this->method_title = ' Ourdesignz authorizeddotnet Payment Gateway ';
            $this->method_description = 'Ourdesignz authorizeddotnet Payment Gateway authorizes credit card payments and processes them securely with your merchant account.';
           // $this->supports = array('products', 'refunds','subscriptions');
            $this->supports = array( 
               'products', 
               'refunds',
               'subscriptions',
               'subscription_cancellation', 
               'subscription_suspension', 
               'subscription_reactivation',
               'subscription_amount_changes',
               'subscription_date_changes',
               'subscription_payment_method_change'
          );
            // Load the form fields
            $this->init_form_fields();
            // Load the settings
            $this->init_settings();
            // Get setting values
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->enabled = $this->get_option('enabled');
            $this->sandbox = $this->get_option('sandbox');
            $this->environment = $this->sandbox == 'no' ? 'production' : 'sandbox';
            $this->merchant_id = $this->sandbox == 'no' ? $this->get_option('merchant_id') : $this->get_option('sandbox_merchant_id');
            $this->private_key = $this->sandbox == 'no' ? $this->get_option('private_key') : $this->get_option('sandbox_private_key');
            $this->public_key = $this->sandbox == 'no' ? $this->get_option('public_key') : $this->get_option('sandbox_public_key');
            $this->cse_key = $this->sandbox == 'no' ? $this->get_option('cse_key') : $this->get_option('sandbox_cse_key');
            $this->debug = isset($this->settings['debug']) ? $this->settings['debug'] : 'no';
            // Hooks

            add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));

            add_action('admin_notices', array($this, 'checks'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

		
		// New
		//add_action( 'woocommerce_subscription_status_updated', __CLASS__ . '::update_authorizeddotnet_subscription', 10, 3 );
		//add_action( 'woocommerce_scheduled_subscription_payment', array($this,'process_authorizeddotnet_subscription_payment'));


            //add_action('woocommerce_after_checkout_validation', 'rei_after_checkout_validation');
        }


	public  function process_authorizeddotnet_subscription_payment( $subscription_id) {
		$subscription = wcs_get_subscription($subscription_id);
		$authorizeddotnet_subs_id = get_post_meta($subscription->id,'wc_authorizeddotnet_gateway_subscription_id',true);
		if(!empty($authorizeddotnet_subs_id)){
		 	global $woocommerce;
            //require_once( 'includes/authorizeddotnet.php' );
            /*
            Odz_authorizeddotnet_Configuration::environment('sandbox');
            Odz_authorizeddotnet_Configuration::merchantId('v8rtv38tdyk445vw');
            Odz_authorizeddotnet_Configuration::publicKey('j8qy439m5zytt777');
            Odz_authorizeddotnet_Configuration::privateKey('abb9ebf58a5104bd087b59fa64bffcba');
            */
            Odz_authorizeddotnet_Configuration::environment($this->environment);
            Odz_authorizeddotnet_Configuration::merchantId($this->merchant_id);
            Odz_authorizeddotnet_Configuration::publicKey($this->public_key);
            Odz_authorizeddotnet_Configuration::privateKey($this->private_key);


		 $authorizeddotnet_subscription = authorizeddotnet_Subscription::find("$authorizeddotnet_subs_id");

			if($authorizeddotnet_subscription->status == 'Active'){
		      $subscription->payment_complete();
			}
	   }
	}

		public  function update_authorizeddotnet_subscription($subscription,$new_status,$old_status){
			 global $woocommerce;
            //require_once( 'includes/authorizeddotnet.php' );
            
            $settings = get_option('woocommerce_odz_authorizeddotnet_payment_gateway_settings');
            
            $environment = $settings['sandbox'] == 'no' ? 'production' : 'sandbox';
            $merchant_id = $settings['sandbox'] == 'no' ? $settings['merchant_id'] : $settings['sandbox_merchant_id'];
            $private_key = $settings['sandbox'] == 'no' ? $settings['private_key'] : $settings['sandbox_private_key'];
            $public_key = $settings['sandbox'] == 'no' ? $settings['public_key'] : $settings['sandbox_public_key'];

            Odz_authorizeddotnet_Configuration::environment($environment);
            Odz_authorizeddotnet_Configuration::merchantId($merchant_id);
            Odz_authorizeddotnet_Configuration::publicKey($public_key);
            Odz_authorizeddotnet_Configuration::privateKey($private_key);


 
			if($new_status == 'cancelled' || $new_status == 'expired'){
				$authorizeddotnet_subs_id = get_post_meta($subscription->id,'wc_authorizeddotnet_gateway_subscription_id',true);
				if(!empty($authorizeddotnet_subs_id)){
				$result = authorizeddotnet_Subscription::cancel($authorizeddotnet_subs_id);
				   if ($result->success){
					  $subscription->add_order_note( __( 'Subscription cancelled with authorizeddotnet.Subscription Id'.$authorizeddotnet_subs_id, 'woocommerce-subscriptions' ) );
					 }
					 else{
						 foreach (($result->errors->deepAll()) as $error) {
							$subscription->add_order_note( __( 'Auto-Cancellation of authorizeddotnet Subscription failed.Error Message : '.$error->message, 'woocommerce-subscriptions' ) );
						 }
						 
					}

				}
				
				
			}
		}

        /**
         * Admin Panel Options
         */
        public function admin_options() {

            global $wpdb;
            wp_enqueue_script('jquery-ui-dialog');
            $current_user = wp_get_current_user();
            if (!get_option('bpg_plugin_notice_shown')) {
                echo '<div id="bpg_dialog" title="Basic dialog"><p>Subscribe for latest plugin update and get notified when we update our plugin and launch new products for free! </p> <p><input type="text" id="txt_user_sub_bpg" class="regular-text" name="txt_user_sub_bpg" value="' . $current_user->user_email . '"></p></div>';
            }
            ?>
            <style type="text/css">
                #bpg_dialog {width:500px; font-size:15px; font-weight:bold;}
                #bpg_dialog p {font-size:15px; font-weight:bold;}
                .ui-dialog-titlebar-close:before { display:none;}
                .free_plugin {
                    margin-bottom: 20px;
                }

                .paid_plugin {
                    margin-bottom: 20px;
                }

                .paid_plugin h3 {
                    border-bottom: 1px solid #ccc;
                    padding-bottom: 20px;
                }

                .free_plugin h3 {
                    padding-bottom: 20px;
                    border-bottom: 1px solid #ccc;
                }
            </style>

            <script type="text/javascript">

                jQuery(document).ready(function() {
                    jQuery("#bpg_dialog").dialog({
                        modal: true, title: 'Subscribe Now', zIndex: 10000, autoOpen: true,
                        width: '500', resizable: false,
                        position: {my: "center", at: "center", of: window},
                        dialogClass: 'dialogButtons',
                        buttons: {
                            Yes: function() {
                                // $(obj).removeAttr('onclick');
                                // $(obj).parents('.Parent').remove();
                                var email_id = jQuery('#txt_user_sub_bpg').val();

                                var data = {
                                    'action': 'add_plugin_user_bpg',
                                    'email_id': email_id
                                };

                                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                                jQuery.post(ajaxurl, data, function(response) {
                                    jQuery('#bpg_dialog').html('<h2>You have been successfully subscribed');
                                    jQuery(".ui-dialog-buttonpane").remove();
                                });


                            },
                            No: function() {
                                var email_id = jQuery('#txt_user_sub_bpg').val();

                                var data = {
                                    'action': 'hide_subscribe_bpg',
                                    'email_id': email_id
                                };

                                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                                jQuery.post(ajaxurl, data, function(response) {

                                });

                                jQuery(this).dialog("close");

                            }
                        },
                        close: function(event, ui) {
                            jQuery(this).remove();
                        }
                    });

                    jQuery("div.dialogButtons .ui-dialog-buttonset button").removeClass('ui-state-default');
                    jQuery("div.dialogButtons .ui-dialog-buttonset button").addClass("button-primary woocommerce-save-button");
                    jQuery("div.dialogButtons .ui-dialog-buttonpane .ui-button").css("width", "80px");
                });
            </script>
            <h3><?php //_e('Ourdesignz authorizeddotnet Payment Gateway', 'woo-authorizeddotnet-payment-gateway');   ?></h3>
            <p><?php _e('Ourdesignz authorizeddotnet Payment Gateway authorizes credit card payments and processes them securely with your merchant account.', 'woo-authorizeddotnet-payment-gateway'); ?></p>
            <table class="form-table"> 
                <?php $this->generate_settings_html(); ?>
            </table> <?php
        }

        /**
         * Check if SSL is enabled and notify the user
         */
        public function checks() {
            if ($this->enabled == 'no') {
                return;
            }

            // PHP Version
            if (version_compare(phpversion(), '5.2.1', '<')) {
                echo '<div class="error"><p>' . sprintf(__('Ourdesignz authorizeddotnet Payment Gateway Error: authorizeddotnet requires PHP 5.2.1 and above. You are using version %s.', 'woo-authorizeddotnet-payment-gateway'), phpversion()) . '</p></div>';
            }

            // Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected
            elseif ('no' == get_option('woocommerce_force_ssl_checkout') && !class_exists('WordPressHTTPS')) {
                echo '<div class="error"><p>' . sprintf(__('Ourdesignz authorizeddotnet Payment Gateway is enabled, but the <a href="%s">force SSL option</a> is disabled; your checkout may not be secure!', 'woo-authorizeddotnet-payment-gateway'), admin_url('admin.php?page=wc-settings&tab=checkout')) . '</p></div>';
            }
        }

        /**
         * Check if this gateway is enabled
         */
        public function is_available() {
            if ('yes' != $this->enabled) {
                return false;
            }

            if (!is_ssl() && 'yes' != $this->sandbox) {
                //	return false;
            }

            return true;
        }

        /**
         * Initialise Gateway Settings Form Fields
         */
        public function init_form_fields() {



            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'odz-authorizeddotnet-payment-gateway'),
                    'label' => __('Enable Ourdesignz authorizeddotnet Payment Gateway', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'checkbox',
                    'description' => '',
                    'default' => 'no'
                ),
                'title' => array(
                    'title' => __('Title', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'odz-authorizeddotnet-payment-gateway'),
                    'default' => __('Credit card', 'woo-authorizeddotnet-payment-gateway'),
                    'desc_tip' => true
                ),
                'description' => array(
                    'title' => __('Description', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'odz-authorizeddotnet-payment-gateway'),
                    'default' => 'Pay securely with your credit card.',
                    'desc_tip' => true
                ),
                'sandbox' => array(
                    'title' => __('Sandbox', 'odz-authorizeddotnet-payment-gateway'),
                    'label' => __('Enable Sandbox Mode', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'checkbox',
                    'description' => __('Place the payment gateway in sandbox mode using sandbox API keys (real payments will not be taken).', 'odz-authorizeddotnet-payment-gateway'),
                    'default' => 'yes'
                ),
                'submit_settlement' => array(
                    'title' => __('Enable', 'odz-authorizeddotnet-payment-gateway'),
                    'label' => __('Submit For Settelment', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'checkbox',
                    'default' => 'yes'
                ),
                'sandbox_merchant_login_id' => array(
                    'title' => __('Sandbox Merchant LOGIN ID', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'text',
                    'description' => __('Get your API keys from your authorizeddotnet account.', 'odz-authorizeddotnet-payment-gateway'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'sandbox_merchant_transaction_key' => array(
                    'title' => __('Sandbox Merchant Transaction Key', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'text',
                    'description' => __('Get your API keys from your authorizeddotnet account.', 'odz-authorizeddotnet-payment-gateway'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'sandbox_valid_cards' => array(
                    'title' => __('Select Card type', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'multiselect',
                    'default' => '',
                    'options' => array('Visa', 'Mastercard', 'American Express', 'Discover', 'JCB', 'Diner`s', 'Maestro'),
                    'class' => 'wc-enhanced-select'
                ),
                'sandbox_cse_key' => array(
                    'title' => __('Sandbox CSE Key', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'textarea',
                    'description' => __('Get your API keys from your authorizeddotnet account.', 'odz-authorizeddotnet-payment-gateway'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'merchant_login_id' => array(
                    'title' => __('Production Merchant LOGIN ID', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'text',
                    'description' => __('Get your API keys from your authorizeddotnet account.', 'odz-authorizeddotnet-payment-gateway'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'merchant_transaction_key' => array(
                    'title' => __('Production Merchant Transaction Key', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'text',
                    'description' => __('Get your API keys from your authorizeddotnet account.', 'odz-authorizeddotnet-payment-gateway'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'cse_key' => array(
                    'title' => __('Production CSE Key', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'textarea',
                    'description' => __('Get your API keys from your authorizeddotnet account.', 'odz-authorizeddotnet-payment-gateway'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'debug' => array(
                    'title' => __('Debug', 'odz-authorizeddotnet-payment-gateway'),
                    'type' => 'checkbox',
                    'label' => __('Enable logging <code>/wp-content/uploads/wc-logs/woo-authorizeddotnet-payment-gateway-{tag}.log</code>', 'odz-authorizeddotnet-payment-gateway'),
                    'default' => 'no'
                ),
            );
        }

        /**
         * Initialise Credit Card Payment Form Fields
         */
        public function payment_fields() {
            ?>
            <p><?php echo $this->description; ?></p>
            <fieldset id="authorizeddotnet-cc-form">
                <p class="form-row form-row-wide">
                    <label for="authorizeddotnet-card-number"><?php echo __('Card Number', 'woo-authorizeddotnet-payment-gateway') ?> <span class="required">*</span></label>
                    <input type="text" data-encrypted-name="authorizeddotnet-card-number" placeholder="" autocomplete="off" maxlength="20" class="input-text wc-credit-card-form-card-number" id="woo-authorizeddotnet-payment-gateway-card-number" name='woo-authorizeddotnet-payment-gateway-card-number'>
                </p>

                <p class="form-row form-row-first authorizeddotnet-card-expiry">
                    <label for="authorizeddotnet-card-expiry-month"><?php echo __('Expiry', 'woo-authorizeddotnet-payment-gateway') ?> <span class="required">*</span></label>
                    <select name="woo-authorizeddotnet-payment-gateway-card-expiry-month" id="woo-authorizeddotnet-payment-gateway-card-expiry-month" class="input-text">
                        <option value=""><?php _e('Month', 'woo-authorizeddotnet-payment-gateway') ?></option>
                        <option value='01'>01</option>
                        <option value='02'>02</option>
                        <option value='03'>03</option>
                        <option value='04'>04</option>
                        <option value='05'>05</option>
                        <option value='06'>06</option>
                        <option value='07'>07</option>
                        <option value='08'>08</option>
                        <option value='09'>09</option>
                        <option value='10'>10</option>
                        <option value='11'>11</option>
                        <option value='12'>12</option>  
                    </select>

                    <select name="woo-authorizeddotnet-payment-gateway-card-expiry-year" id="woo-authorizeddotnet-payment-gateway-card-expiry-year" class="input-text">
                        <option value=""><?php _e('Year', 'woo-authorizeddotnet-payment-gateway') ?></option><?php
                        for ($iYear = date('Y'); $iYear < date('Y') + 21; $iYear++) {
                            echo '<option value="' . $iYear . '">' . $iYear . '</option>';
                        }
                        ?>
                    </select>
                </p>

                <p class="form-row form-row-last">
                    <label for="authorizeddotnet-card-cvc"><?php echo __('Card Code', 'woo-authorizeddotnet-payment-gateway') ?> <span class="required">*</span></label>
                    <input type="text" data-encrypted-name="authorizeddotnet-card-cvc" placeholder="CVC" autocomplete="off" class="input-text wc-credit-card-form-card-cvc" name ='woo-authorizeddotnet-payment-gateway-card-cvc' id="woo-authorizeddotnet-payment-gateway-card-cvc">
                </p>
            </fieldset>
            <?php
        }

        /**
         * Function is responsible for verify card type
         *
         * @param unknown_type $number
         * @return unknown
         */
        public function wc_card_type($number) {
            $number = preg_replace('/[^\d]/', '', $number);
            if (preg_match('/^3[47][0-9]{13}$/', $number)) {
                return '2'; //amex
            } elseif (preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/', $number)) {
                return '3'; //discover
            } elseif (preg_match('/^5[1-5][0-9]{14}$/', $number)) {
                return '1'; //master
            } elseif (preg_match('/^([30|36|38]{2})([0-9]{12})$/', $number)) {
                return '5'; //Diner's
            } elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/', $number)) {
                return '4'; //JCB
            } elseif (preg_match('/^(?:5020|6\\d{3})\\d{12}$/', $number)) {
                return '6'; //maestro
            } elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $number)) {
                return '0'; //visa
            } else {
                return 'Unknown';
            }
        }

        /**
         * Outputs style used for Ourdesignz authorizeddotnet Payment Gateway Payment fields
         * Outputs scripts used for Ourdesignz authorizeddotnet Payment Gateway
         */
        public function payment_scripts() {
            if (!is_checkout() || !$this->is_available()) {
                return;
            }
        }
        
        public function pr($array){
			echo "<pre>";
			print_r($array);
			echo "</pre>";
		}

        /**
         * Process the payment
         */
        public function process_payment($order_id) {
			
            global $woocommerce;
            $order = new WC_Order($order_id);
            $order_id = $order->id;
            
            $get_card_value = get_option('woocommerce_odz_authorizeddotnet_payment_gateway_settings');
            $card_value = maybe_unserialize($get_card_value);
            $card_array = $card_value['sandbox_valid_cards'];
            if (isset($card_array) && !empty($card_array)) {
                $card_no = $this->wc_card_type($_POST['woo-authorizeddotnet-payment-gateway-card-number']);
                if (!in_array($card_no, $card_array)) {
                    wc_add_notice(__("This card is not acceptable", 'woocommerce'), 'error');
                    return false;
                }
            }
            $submit_settelment_option = get_option('woocommerce_odz_authorizeddotnet_payment_gateway_settings');
            $settelment_value = $submit_settelment_option['submit_settlement'];
            
            // Get Order Items
            $subscriptionOrder = false;$subscription_products = array();
            foreach ($order->get_items() as $key => $lineItem) {
                $product_id = $lineItem['product_id'];
                $product = wc_get_product($product_id);
                if($product->product_type == 'subscription'){
                    $subscription_products[] = $product_id;
                    $subscriptionOrder = true;
                }
            }
            
            if($subscriptionOrder){
                // Currently working only for single subscription product in Cart
					$subs_price = 0;
                foreach($subscription_products as $product_id){
                    $product = wc_get_product($product_id);
					$signup_fee =	WC_Subscriptions_Product::get_sign_up_fee($product);
                    $subs_price +=  WC_Subscriptions_Product::get_price( $product );
                }
                echo '$order->order_total: '.$order->order_total;
                
                $SaleAmount = $order->order_total ;
                
                $signupFeeExists = false;
				/***** Sign up Fee Transaction Begins HERE ***************/
                if(!empty($SaleAmount) && $SaleAmount > 0){
					
					if(!$signup_fee){			// Recurring Payment
					
						/* Create a merchantAuthenticationType object with authentication details
						   retrieved from the constants file */
						    
						    $subscription_price = get_post_meta($product->id,'_subscription_price');
						  	$subscription_period = get_post_meta($product->id,'_subscription_period');
							$subscription_period_interval = get_post_meta($product->id,'_subscription_period_interval');
							$subscription_trial_period = get_post_meta($product->id,'_subscription_trial_period');
							$subscription_sign_up_fee = get_post_meta($product->id,'_subscription_sign_up_fee');
							$subscription_length = get_post_meta($product->id,'_subscription_length');
							
							$interval_Unit = $subscription_period[0].'s';
							$intervalLength = $subscription_period_interval[0];
							$startDate = $order->order_date;
 
						$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
						$merchantAuthentication->setName($this->settings['sandbox_merchant_login_id']);
						$merchantAuthentication->setTransactionKey($this->settings['sandbox_merchant_transaction_key']);
						
						// Set the transaction's refId
						$refId = 'ref' . time();

						// Subscription Type Info
						$subscription = new AnetAPI\ARBSubscriptionType();
						$subscription->setName(isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '');

						$interval = new AnetAPI\PaymentScheduleType\IntervalAType();
						$interval->setLength($intervalLength);
						$interval->setUnit($interval_Unit);

						$paymentSchedule = new AnetAPI\PaymentScheduleType();
						$paymentSchedule->setInterval($interval);
						$paymentSchedule->setStartDate(new DateTime($startDate));
						$paymentSchedule->setTotalOccurrences($subscription_length[0]/$intervalLength);
						//$paymentSchedule->setTrialOccurrences("1");
						
						/*echo "<pre>";
						$this->pr($subscription_price);
						echo "</pre>";
						die("sd");*/
						
						$subscription->setPaymentSchedule($paymentSchedule);
						$subscription->setAmount($subscription_price[0]);
						//$subscription->setTrialAmount("0.00");
						
						$creditCard = new AnetAPI\CreditCardType();
						$creditCard->setCardNumber($_POST['woo-authorizeddotnet-payment-gateway-card-number']);
						$creditCard->setExpirationDate($_POST['woo-authorizeddotnet-payment-gateway-card-expiry-year']."-".$_POST['woo-authorizeddotnet-payment-gateway-card-expiry-month']);

						$payment = new AnetAPI\PaymentType();
						$payment->setCreditCard($creditCard);
						$subscription->setPayment($payment);

						$order_Anet = new AnetAPI\OrderType();
						$order_Anet->setInvoiceNumber(rand());        
						$order_Anet->setDescription("Description of the subscription"); 
						$subscription->setOrder($order_Anet); 
						
						$billTo = new AnetAPI\NameAndAddressType();
						$billTo->setFirstName(isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '');
						$billTo->setLastName(isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '');

						$subscription->setBillTo($billTo);

						$request = new AnetAPI\ARBCreateSubscriptionRequest();
						$request->setmerchantAuthentication($merchantAuthentication);
						$request->setRefId($refId);
						$request->setSubscription($subscription);
						$controller = new AnetController\ARBCreateSubscriptionController($request);

						$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
						
						if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
						{
							echo "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n";
						}
						else
						{
							echo "ERROR :  Invalid response\n";
							$errorMessages = $response->getMessages()->getMessage();
							echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
						}
					
					}
					else{				// One Time  Payment
						
						/* Create a merchantAuthenticationType object with authentication details
							retrieved from the constants file */
							
						$amount = $SaleAmount;
						$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
						$merchantAuthentication->setName($this->settings['sandbox_merchant_login_id']);
						$merchantAuthentication->setTransactionKey($this->settings['sandbox_merchant_transaction_key']);
						
						// Set the transaction's refId
						$refId = 'ref' . time();

						// Create the payment data for a credit card
						$creditCard = new AnetAPI\CreditCardType();
						$creditCard->setCardNumber($_POST['woo-authorizeddotnet-payment-gateway-card-number']);
						$creditCard->setExpirationDate($_POST['woo-authorizeddotnet-payment-gateway-card-expiry-year']."-".$_POST['woo-authorizeddotnet-payment-gateway-card-expiry-month']);
						$creditCard->setCardCode($_POST['woo-authorizeddotnet-payment-gateway-card-cvc']);

						// Add the payment data to a paymentType object
						$paymentOne = new AnetAPI\PaymentType();
						$paymentOne->setCreditCard($creditCard);

						// Create order information
						$order_Anet = new AnetAPI\OrderType();
						$order_Anet->setInvoiceNumber("10103");
						$order_Anet->setDescription("Golf Shirts");

						// Set the customer's Bill To address
						$customerAddress = new AnetAPI\CustomerAddressType();
						$customerAddress->setFirstName(isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '');
						$customerAddress->setLastName(isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '');
						$customerAddress->setCompany(isset($_POST['billing_company']) ? $_POST['billing_company'] : '');
						$customerAddress->setAddress(isset($_POST['billing_city']) ? $_POST['billing_city'] : '');								//DOUBT
						$customerAddress->setCity(isset($_POST['billing_city']) ? $_POST['billing_city'] : '');
						$customerAddress->setState(isset($_POST['billing_state']) ? $_POST['billing_state'] : '');
						$customerAddress->setZip(isset($_POST['billing_postcode']) ? $_POST['billing_postcode'] : '');
						$customerAddress->setCountry(isset($_POST['billing_country']) ? $_POST['billing_country'] : '');

						// Set the customer's identifying information
						$customerData = new AnetAPI\CustomerDataType();
						$customerData->setEmail(isset($_POST['billing_email']) ? $_POST['billing_email'] : '');

						// Add values for transaction settings
						$duplicateWindowSetting = new AnetAPI\SettingType();
						//$duplicateWindowSetting->setSettingName("duplicateWindow");																//DOUBT
						//$duplicateWindowSetting->setSettingValue("60");																			//DOUBT

						// Add some merchant defined fields. These fields won't be stored with the transaction,
						// but will be echoed back in the response.
						$merchantDefinedField1 = new AnetAPI\UserFieldType();
						//$merchantDefinedField1->setName("customerLoyaltyNum");
						//$merchantDefinedField1->setValue("1128836273");

						//$merchantDefinedField2 = new AnetAPI\UserFieldType();
						//$merchantDefinedField2->setName("favoriteColor");
						//$merchantDefinedField2->setValue("blue");

						// Create a TransactionRequestType object and add the previous objects to it
						$transactionRequestType = new AnetAPI\TransactionRequestType();
						$transactionRequestType->setTransactionType("authCaptureTransaction");
						$transactionRequestType->setAmount($amount);
						$transactionRequestType->setOrder($order_Anet);
						$transactionRequestType->setPayment($paymentOne);
						$transactionRequestType->setBillTo($customerAddress);
						$transactionRequestType->setCustomer($customerData);
						$transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
						$transactionRequestType->addToUserFields($merchantDefinedField1);
						$transactionRequestType->addToUserFields($merchantDefinedField2);

						// Assemble the complete transaction request
						$request = new AnetAPI\CreateTransactionRequest();
						$request->setMerchantAuthentication($merchantAuthentication);
						$request->setRefId($refId);
						$request->setTransactionRequest($transactionRequestType);

						// Create the controller and get the response
						$controller = new AnetController\CreateTransactionController($request);
						$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
						

						if ($response != null) {
							die("zzzzz");
							// Check to see if the API request was successfully received and acted upon
							if ($response->getMessages()->getResultCode() == \SampleCode\Constants::RESPONSE_OK) {
								// Since the API request was successful, look for a transaction response
								// and parse it to display the results of authorizing the card
								$tresponse = $response->getTransactionResponse();
								die("xxxx");
							
								if ($tresponse != null && $tresponse->getMessages() != null) {
									echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
									echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
									echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
									echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
									echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";
								} else {
									echo "Transaction Failed \n";
									if ($tresponse->getErrors() != null) {
										echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
										echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
									}
								}
								// Or, print errors if the API request wasn't successful
							} else {
								echo "Transaction Failed \n";
								$tresponse = $response->getTransactionResponse();
							
								if ($tresponse != null && $tresponse->getErrors() != null) {
									echo " Error Code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
									echo " Error Message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
								} else {
									echo " Error Code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
									echo " Error Message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
								}
							}
						} else {
							echo  "No response returned \n";
						}
					}
					
					/*echo "<pre>";
					print_r($response);
					echo "</pre>";
					die("sd");*/
                
				}
				/***** Sign up Fee Transaction Ends HERE ***************/

                
              
                
               /* ******** Result Handeling Begins ***********/ 
               
				/*echo "<pre>";
				print_r($response);
				echo "</pre>";
				die("sd");*/
                
				if ($response != null) {
					// get subscription of order
					$subscriptions_ids = wcs_get_subscriptions_for_order( $order_id );
					foreach( $subscriptions_ids as $subscription_id => $subscription_obj ){
						update_post_meta($subscription_id, 'wc_authorizeddotnet_gateway_subscription_id', $response->getSubscriptionId());
						$subscription_obj->add_order_note(sprintf(__('%s authorizeddotnet Subscription ID: %s', 'woo-authorizeddotnet-payment-gateway'), $this->title, $response->getSubscriptionId()));
					}

					if($signupFeeExists){
						// Payment complete
						$trans_id = $response->getSubscriptionId();
						$order->payment_complete($trans_id);
						
						// Add order note
						$order->add_order_note(sprintf(__('%s payment approved! Transaction ID: %s', 'woo-authorizeddotnet-payment-gateway'), $this->title, $response->getSubscriptionId()));
						update_post_meta($order_id, 'wc_authorizeddotnet_gateway_transaction_id', $response->getSubscriptionId());
					}
					else{
						// Payment complete
						$trans_id = $response->getSubscriptionId();
						$order->payment_complete($trans_id);
						
						// Add order note
						$order->add_order_note(sprintf(__('%s payment approved! Subscription ID: %s', 'woo-authorizeddotnet-payment-gateway'), $this->title, $response->getSubscriptionId()));
						update_post_meta($order_id, 'wc_authorizeddotnet_gateway_transaction_id', $response->getSubscriptionId());
					}

					$checkout_note = array(
						'ID' => $order_id,
						'post_excerpt' => isset($_POST['order_comments']) ? $_POST['order_comments'] : '',
					);
					wp_update_post($checkout_note);

					if (is_user_logged_in()) {
						$userLogined = wp_get_current_user();
						update_post_meta($order_id, '_billing_email', isset($userLogined->user_email) ? $userLogined->user_email : '');
						update_post_meta($order_id, '_customer_user', isset($userLogined->ID) ? $userLogined->ID : '');
					} else {
						$payermail = method_exists($this, 'get_session') ? $this->get_session('payeremail') : '';
						update_post_meta($order_id, '_billing_email', $payermail);
					}
					
					$trn_bill_fname = isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '';
					$trn_bill_lname = isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '';

					$fullname = $trn_bill_fname . ' ' . $trn_bill_lname;

					update_post_meta($order_id, '_billing_first_name', isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '');
					update_post_meta($order_id, '_billing_last_name', isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '');
					update_post_meta($order_id, '_billing_full_name', isset($fullname) ? $fullname : '');
					update_post_meta($order_id, '_billing_company', isset($_POST['billing_company']) ? $_POST['billing_company'] : '');
					update_post_meta($order_id, '_billing_phone', isset($_POST['billing_phone']) ? $_POST['billing_phone'] : '');
					update_post_meta($order_id, '_billing_address_1', isset($_POST['billing_address_1']) ? $_POST['billing_address_1'] : '');
					update_post_meta($order_id, '_billing_address_2', isset($_POST['billing_address_2']) ? $_POST['billing_address_2'] : '');
					update_post_meta($order_id, '_billing_city', isset($_POST['billing_city']) ? $_POST['billing_city'] : '');
					update_post_meta($order_id, '_billing_postcode', isset($_POST['billing_postcode']) ? $_POST['billing_postcode'] : '');
					update_post_meta($order_id, '_billing_country', isset($_POST['billing_country']) ? $_POST['billing_country'] : '');
					update_post_meta($order_id, '_billing_state', isset($_POST['billing_state']) ? $_POST['billing_state'] : '');
					update_post_meta($order_id, '_customer_user', get_current_user_id());

					update_post_meta($order_id, '_shipping_first_name', isset($_POST['shipping_first_name']) ? $_POST['shipping_first_name'] : $_POST['billing_first_name']);
					update_post_meta($order_id, '_shipping_last_name', isset($_POST['shipping_last_name']) ? $_POST['shipping_last_name'] : $_POST['billing_last_name']);
					update_post_meta($order_id, '_shipping_full_name', isset($fullname) ? $fullname : '');
					update_post_meta($order_id, '_shipping_company', isset($_POST['shipping_company']) ? $_POST['shipping_company'] : $_POST['billing_company']);
					update_post_meta($order_id, '_billing_phone', isset($_POST['billing_phone']) ? $_POST['billing_phone'] : $_POST['billing_phone']);
					update_post_meta($order_id, '_shipping_address_1', isset($_POST['shipping_address_1']) ? $_POST['shipping_address_1'] : $_POST['billing_address_1']);
					update_post_meta($order_id, '_shipping_address_2', isset($_POST['shipping_address_2']) ? $_POST['shipping_address_2'] : $_POST['billing_address_2']);
					update_post_meta($order_id, '_shipping_city', isset($_POST['shipping_city']) ? $_POST['shipping_city'] : $_POST['billing_city']);
					update_post_meta($order_id, '_shipping_postcode', isset($_POST['shipping_postcode']) ? $_POST['shipping_postcode'] : $_POST['billing_postcode']);
					update_post_meta($order_id, '_shipping_country', isset($_POST['shipping_country']) ? $_POST['shipping_country'] : $_POST['billing_country']);
					update_post_meta($order_id, '_shipping_state', isset($_POST['shipping_state']) ? $_POST['shipping_state'] : $_POST['billing_state']);

					if (is_user_logged_in()) {
						$userLogined = wp_get_current_user();
						$customer_id = $userLogined->ID;
						update_user_meta($customer_id, 'billing_first_name', isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '');
						update_user_meta($customer_id, 'billing_last_name', isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '');
						update_user_meta($customer_id, 'billing_full_name', isset($fullname) ? $fullname : '');
						update_user_meta($customer_id, 'billing_company', isset($_POST['billing_company']) ? $_POST['billing_company'] : '');
						update_user_meta($customer_id, 'billing_phone', isset($_POST['billing_phone']) ? $_POST['billing_phone'] : '');
						update_user_meta($customer_id, 'billing_address_1', isset($_POST['billing_address_1']) ? $_POST['billing_address_1'] : '');
						update_user_meta($customer_id, 'billing_address_2', isset($_POST['billing_address_2']) ? $_POST['billing_address_2'] : '');
						update_user_meta($customer_id, 'billing_city', isset($_POST['billing_city']) ? $_POST['billing_city'] : '');
						update_user_meta($customer_id, 'billing_postcode', isset($_POST['billing_postcode']) ? $_POST['billing_postcode'] : '');
						update_user_meta($customer_id, 'billing_country', isset($_POST['billing_country']) ? $_POST['billing_country'] : '');
						update_user_meta($customer_id, 'billing_state', isset($_POST['billing_state']) ? $_POST['billing_state'] : '');
						update_user_meta($customer_id, 'customer_user', get_current_user_id());

						update_user_meta($customer_id, 'shipping_first_name', isset($_POST['shipping_first_name']) ? $_POST['shipping_first_name'] : $_POST['billing_first_name']);
						update_user_meta($customer_id, 'shipping_last_name', isset($_POST['shipping_last_name']) ? $_POST['shipping_last_name'] : $_POST['billing_last_name']);
						update_user_meta($customer_id, 'shipping_full_name', isset($fullname) ? $fullname : '');
						update_user_meta($customer_id, 'shipping_company', isset($_POST['shipping_company']) ? $_POST['shipping_company'] : $_POST['billing_company']);
						update_user_meta($customer_id, 'billing_phone', isset($_POST['billing_phone']) ? $_POST['billing_phone'] : '');
						update_user_meta($customer_id, 'shipping_address_1', isset($_POST['shipping_address_1']) ? $_POST['shipping_address_1'] : $_POST['billing_address_1']);
						update_user_meta($customer_id, 'shipping_address_2', isset($_POST['shipping_address_2']) ? $_POST['shipping_address_2'] : $_POST['billing_address_2']);
						update_user_meta($customer_id, 'shipping_city', isset($_POST['shipping_city']) ? $_POST['shipping_city'] : $_POST['billing_city']);
						update_user_meta($customer_id, 'shipping_postcode', isset($_POST['shipping_postcode']) ? $_POST['shipping_postcode'] : $_POST['billing_postcode']);
						update_user_meta($customer_id, 'shipping_country', isset($_POST['shipping_country']) ? $_POST['shipping_country'] : $_POST['billing_country']);
						update_user_meta($customer_id, 'shipping_state', isset($_POST['shipping_state']) ? $_POST['shipping_state'] : $_POST['billing_state']);
					}
					
					//$this->add_log(print_r($result, true));
					// Remove cart
					WC()->cart->empty_cart();
					
					// Return thank you page redirect

					if (is_ajax()) {
						$result = array(
							'redirect' => $this->get_return_url($order),
							'result' => 'success'
						);
						echo json_encode($result);
					} else {
					}
					
				}
				else if ($result->transaction) {
					$order->add_order_note(sprintf(__('%s payment declined.<br />Error: %s<br />Code: %s', 'woo-authorizeddotnet-payment-gateway'), $this->title, $result->message, $result->transaction->processorResponseCode));
					$this->add_log(print_r($result, true));
				}
				else {
					foreach (($result->errors->deepAll()) as $error) {
						wc_add_notice("Validation error - " . $error->message, 'error');
					}
					return array(
						'result' => 'fail',
						'redirect' => ''
					);
					$this->add_log($error->message);
				}
				/* ******** Result Handeling Ends ***********/
                
                
                
            }
            // Subscription Product Ends
            // For Non-subscription Order
            else{
                $result = authorizeddotnet_Transaction::sale(array(
                            "amount" => $order->order_total,
                            'orderId' => $order_id,
                            "creditCard" => array(
                                "number" => $_POST['woo-authorizeddotnet-payment-gateway-card-number'],
                                "cvv" => $_POST['woo-authorizeddotnet-payment-gateway-card-cvc'],
                                "expirationMonth" => $_POST['woo-authorizeddotnet-payment-gateway-card-expiry-month'],
                                "expirationYear" => $_POST['woo-authorizeddotnet-payment-gateway-card-expiry-year']
                            ),
                            'customer' => array(
                                'firstName' => isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '',
                                'lastName' => isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '',
                                'company' => isset($_POST['billing_company']) ? $_POST['billing_company'] : '',
                                'phone' => isset($_POST['billing_phone']) ? $_POST['billing_phone'] : '',
                                'email' => isset($_POST['billing_email']) ? $_POST['billing_email'] : ''
                            ),
                            'billing' => array(
                                'firstName' => isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '',
                                'lastName' => isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '',
                                'company' => isset($_POST['billing_company']) ? $_POST['billing_company'] : '',
                                'streetAddress' => isset($_POST['billing_address_1']) ? $_POST['billing_address_1'] : '',
                                'extendedAddress' => isset($_POST['billing_address_2']) ? $_POST['billing_address_2'] : '',
                                'locality' => isset($_POST['billing_city']) ? $_POST['billing_city'] : '',
                                'region' => isset($_POST['billing_state']) ? $_POST['billing_state'] : '',
                                'postalCode' => isset($_POST['billing_postcode']) ? $_POST['billing_postcode'] : '',
                                'countryCodeAlpha2' => isset($_POST['billing_country']) ? $_POST['billing_country'] : ''
                            ),
                            'shipping' => array(
                                'firstName' => isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '',
                                'lastName' => isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '',
                                'company' => isset($_POST['billing_company']) ? $_POST['billing_company'] : '',
                                'streetAddress' => isset(WC()->customer->shipping_address_1) ? WC()->customer->shipping_address_1 : '',
                                'extendedAddress' => isset(WC()->customer->shipping_address_2) ? WC()->customer->shipping_address_2 : '',
                                'locality' => isset(WC()->customer->shipping_city) ? WC()->customer->shipping_city : '',
                                'region' => isset(WC()->customer->shipping_state) ? WC()->customer->shipping_state : '',
                                'postalCode' => isset(WC()->customer->shipping_postcode) ? WC()->customer->shipping_postcode : '',
                                'countryCodeAlpha2' => isset(WC()->customer->shipping_country) ? WC()->customer->shipping_country : ''
                            ),
                            "options" => array(
                                "submitForSettlement" => (empty($settelment_value) || $settelment_value == 'yes') ? true : false
                            )
                ));
                
				if ($result->success) {
					// Payment complete
					$order->payment_complete($result->transaction->id);

					// Add order note
					$order->add_order_note(sprintf(__('%s payment approved! Trnsaction ID: %s', 'woo-authorizeddotnet-payment-gateway'), $this->title, $result->transaction->id));

					$checkout_note = array(
						'ID' => $order_id,
						'post_excerpt' => isset($_POST['order_comments']) ? $_POST['order_comments'] : '',
					);
					wp_update_post($checkout_note);

					$result_transaction_id = isset($result->transaction->id) ? $result->transaction->id : '';
					update_post_meta($order_id, 'wc_authorizeddotnet_gateway_transaction_id', $result_transaction_id);

					if (is_user_logged_in()) {
						$userLogined = wp_get_current_user();
						update_post_meta($order_id, '_billing_email', isset($userLogined->user_email) ? $userLogined->user_email : '');
						update_post_meta($order_id, '_customer_user', isset($userLogined->ID) ? $userLogined->ID : '');
					} else {

						$payermail = method_exists($this, 'get_session') ? $this->get_session('payeremail') : '';
						update_post_meta($order_id, '_billing_email', $payermail);
					}

					$trn_bill_fname = isset($result->transaction->billing['firstName']) ? $result->transaction->billing['firstName'] : '';
					$trn_bill_lname = isset($result->transaction->billing['lastName']) ? $result->transaction->billing['lastName'] : '';

					$fullname = $trn_bill_fname . ' ' . $trn_bill_lname;

					update_post_meta($order_id, '_billing_first_name', isset($result->transaction->billing['firstName']) ? $result->transaction->billing['firstName'] : '');
					update_post_meta($order_id, '_billing_last_name', isset($result->transaction->billing['lastName']) ? $result->transaction->billing['lastName'] : '');
					update_post_meta($order_id, '_billing_full_name', isset($fullname) ? $fullname : '');
					update_post_meta($order_id, '_billing_company', isset($result->transaction->billing['company']) ? $result->transaction->billing['company'] : '');
					update_post_meta($order_id, '_billing_phone', isset($_POST['billing_phone']) ? $_POST['billing_phone'] : '');
					update_post_meta($order_id, '_billing_address_1', isset($result->transaction->billing['streetAddress']) ? $result->transaction->billing['streetAddress'] : '');
					update_post_meta($order_id, '_billing_address_2', isset($result->transaction->billing['extendedAddress']) ? $result->transaction->billing['extendedAddress'] : '');
					update_post_meta($order_id, '_billing_city', isset($result->transaction->billing['locality']) ? $result->transaction->billing['locality'] : '');
					update_post_meta($order_id, '_billing_postcode', isset($result->transaction->billing['postalCode']) ? $result->transaction->billing['postalCode'] : '');
					update_post_meta($order_id, '_billing_country', isset($result->transaction->billing['countryCodeAlpha2']) ? $result->transaction->billing['countryCodeAlpha2'] : '');
					update_post_meta($order_id, '_billing_state', isset($result->transaction->billing['region']) ? $result->transaction->billing['region'] : '');
					update_post_meta($order_id, '_customer_user', get_current_user_id());

					update_post_meta($order_id, '_shipping_first_name', isset($result->transaction->shipping['firstName']) ? $result->transaction->shipping['firstName'] : '');
					update_post_meta($order_id, '_shipping_last_name', isset($result->transaction->shipping['lastName']) ? $result->transaction->shipping['lastName'] : '');
					update_post_meta($order_id, '_shipping_full_name', isset($fullname) ? $fullname : '');
					update_post_meta($order_id, '_shipping_company', isset($result->transaction->shipping['company']) ? $result->transaction->shipping['company'] : '');
					update_post_meta($order_id, '_billing_phone', isset($_POST['billing_phone']) ? $_POST['billing_phone'] : '');
					update_post_meta($order_id, '_shipping_address_1', isset($result->transaction->shipping['streetAddress']) ? $result->transaction->shipping['streetAddress'] : '');
					update_post_meta($order_id, '_shipping_address_2', isset($result->transaction->shipping['extendedAddress']) ? $result->transaction->shipping['extendedAddress'] : '');
					update_post_meta($order_id, '_shipping_city', isset($result->transaction->shipping['locality']) ? $result->transaction->shipping['locality'] : '');
					update_post_meta($order_id, '_shipping_postcode', isset($result->transaction->shipping['postalCode']) ? $result->transaction->shipping['postalCode'] : '');
					update_post_meta($order_id, '_shipping_country', isset($result->transaction->shipping['countryCodeAlpha2']) ? $result->transaction->shipping['countryCodeAlpha2'] : '');
					update_post_meta($order_id, '_shipping_state', isset($result->transaction->shipping['region']) ? $result->transaction->shipping['region'] : '');

					if (is_user_logged_in()) {
						$userLogined = wp_get_current_user();
						$customer_id = $userLogined->ID;
						update_user_meta($customer_id, 'billing_first_name', isset($result->transaction->billing['firstName']) ? $result->transaction->billing['firstName'] : '');
						update_user_meta($customer_id, 'billing_last_name', isset($result->transaction->billing['lastName']) ? $result->transaction->billing['lastName'] : '');
						update_user_meta($customer_id, 'billing_full_name', isset($fullname) ? $fullname : '');
						update_user_meta($customer_id, 'billing_company', isset($result->transaction->billing['company']) ? $result->transaction->billing['company'] : '');
						update_user_meta($customer_id, 'billing_phone', isset($_POST['billing_phone']) ? $_POST['billing_phone'] : '');
						update_user_meta($customer_id, 'billing_address_1', isset($result->transaction->billing['streetAddress']) ? $result->transaction->billing['streetAddress'] : '');
						update_user_meta($customer_id, 'billing_address_2', isset($result->transaction->billing['extendedAddress']) ? $result->transaction->billing['extendedAddress'] : '');
						update_user_meta($customer_id, 'billing_city', isset($result->transaction->billing['locality']) ? $result->transaction->billing['locality'] : '');
						update_user_meta($customer_id, 'billing_postcode', isset($result->transaction->billing['postalCode']) ? $result->transaction->billing['postalCode'] : '');
						update_user_meta($customer_id, 'billing_country', isset($result->transaction->billing['countryCodeAlpha2']) ? $result->transaction->billing['countryCodeAlpha2'] : '');
						update_user_meta($customer_id, 'billing_state', isset($result->transaction->billing['region']) ? $result->transaction->billing['region'] : '');
						update_user_meta($customer_id, 'customer_user', get_current_user_id());

						update_user_meta($customer_id, 'shipping_first_name', isset($result->transaction->shipping['firstName']) ? $result->transaction->shipping['firstName'] : '');
						update_user_meta($customer_id, 'shipping_last_name', isset($result->transaction->shipping['lastName']) ? $result->transaction->shipping['lastName'] : '');
						update_user_meta($customer_id, 'shipping_full_name', isset($fullname) ? $fullname : '');
						update_user_meta($customer_id, 'shipping_company', isset($result->transaction->shipping['company']) ? $result->transaction->shipping['company'] : '');
						update_user_meta($customer_id, 'billing_phone', isset($_POST['billing_phone']) ? $_POST['billing_phone'] : '');
						update_user_meta($customer_id, 'shipping_address_1', isset($result->transaction->shipping['streetAddress']) ? $result->transaction->shipping['streetAddress'] : '');
						update_user_meta($customer_id, 'shipping_address_2', isset($result->transaction->shipping['extendedAddress']) ? $result->transaction->shipping['extendedAddress'] : '');
						update_user_meta($customer_id, 'shipping_city', isset($result->transaction->shipping['locality']) ? $result->transaction->shipping['locality'] : '');
						update_user_meta($customer_id, 'shipping_postcode', isset($result->transaction->shipping['postalCode']) ? $result->transaction->shipping['postalCode'] : '');
						update_user_meta($customer_id, 'shipping_country', isset($result->transaction->shipping['countryCodeAlpha2']) ? $result->transaction->shipping['countryCodeAlpha2'] : '');
						update_user_meta($customer_id, 'shipping_state', isset($result->transaction->shipping['region']) ? $result->transaction->shipping['region'] : '');
					}

					$this->add_log(print_r($result, true));
					// Remove cart
					WC()->cart->empty_cart();
					// Return thank you page redirect

					if (is_ajax()) {
						$result = array(
							'redirect' => $this->get_return_url($order),
							'result' => 'success'
						);
						echo json_encode($result);
						exit;
					} else {
						exit;
					}
				} else if ($result->transaction) {
					$order->add_order_note(sprintf(__('%s payment declined.<br />Error: %s<br />Code: %s', 'woo-authorizeddotnet-payment-gateway'), $this->title, $result->message, $result->transaction->processorResponseCode));
					$this->add_log(print_r($result, true));
				} else {
					foreach (($result->errors->deepAll()) as $error) {
						wc_add_notice("Validation error - " . $error->message, 'error');
					}
					return array(
						'result' => 'fail',
						'redirect' => ''
					);
					$this->add_log($error->message);
				}
			}
			// Non Subscription Tarnsaction Ends
        
            
		}

        //  Process a refund if supported
        public function process_refund($order_id, $amount = null, $reason = '') {
            $order = new WC_Order($order_id);
            //require_once( 'includes/authorizeddotnet.php' );

            Odz_authorizeddotnet_Configuration::environment($this->environment);
            Odz_authorizeddotnet_Configuration::merchantId($this->merchant_id);
            Odz_authorizeddotnet_Configuration::publicKey($this->public_key);
            Odz_authorizeddotnet_Configuration::privateKey($this->private_key);
            $transation_id = get_post_meta($order_id, 'wc_authorizeddotnet_gateway_transaction_id', true);

            $result = authorizeddotnet_Transaction::refund($transation_id, $amount);

            if ($result->success) {

                $this->add_log(print_r($result, true));
                $max_remaining_refund = wc_format_decimal($order->get_total() - $amount);
                if (!$max_remaining_refund > 0) {
                    $order->update_status('refunded');
                }

                if (ob_get_length())
                    ob_end_clean();
                return true;
            }else {
                $wc_message = apply_filters('wc_authorizeddotnet_refund_message', $result->message, $result);
                $this->add_log(print_r($result, true));
                return new WP_Error('wc_authorizeddotnet_gateway_refund-error', $wc_message);
            }
        }

        /**
         * Use WooCommerce logger if debug is enabled.
         */
        function add_log($message) {
            if ($this->debug == 'yes') {
                if (empty($this->log))
                    $this->log = new WC_Logger();
                $this->log->add('odz_authorizeddotnet_payment_gateway', $message);
            }
        }

    }
}




/**
 * Run when plugin is activated
 */
function activate_odz_authorizeddotnet_payment_gateway() {
}

/**
 * Run when plugin is deactivate
 */
function deactivate_odz_authorizeddotnet_payment_gateway() {
    
}


function woo_add_custom_general_fields() {

  global $woocommerce, $post;
  
  echo '<div class="options_group">';
  
  // Text Field
woocommerce_wp_text_input( 
	array( 
		'id'          => '_subscription_plan_id', 
		'label'       => __( 'Subscription Plan Id', 'woocommerce' ), 
		'desc_tip'    => 'true',
		'description' => __( 'Enter tauthorizeddotnet Subscription Plan Id from your authorizeddotnet account.', 'woocommerce' ) 
	)
);
  
  echo '</div>';
	
}


function woo_add_custom_general_fields_save( $post_id ){
	
	// Text Field
	$woocommerce_text_field = $_POST['_subscription_plan_id'];
	if( !empty( $woocommerce_text_field ) )
		update_post_meta( $post_id, '_subscription_plan_id', esc_attr( $woocommerce_text_field ) );
	
}

// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );

// Save Fields
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );



