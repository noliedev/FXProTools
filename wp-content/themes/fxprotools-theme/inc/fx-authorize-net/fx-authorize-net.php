<?php

require ABSPATH . 'vendor/autoload.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
date_default_timezone_set('America/Los_Angeles');

define("AUTHORIZENET_LOG_FILE", "fx-authorize-net");

/**
* Tell WooCommerce that authorize.net class exists 
*/
function add_fx_authorize_net($methods) {
    $methods[] = 'fx_authorize_net';
    return $methods;
}
add_filter('woocommerce_payment_gateways', 'add_fx_authorize_net');



if (!class_exists('WC_Payment_Gateway'))  return;

class fx_authorize_net extends WC_Payment_Gateway {

    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'fx_authorize_net';
        $this->icon = apply_filters('woocommerce_authorize_net_icon', plugins_url('images/cards.png', __FILE__));
        $this->has_fields = true;
        $this->method_title = 'FX Authorize.net';
        $this->method_description = 'Authorizes credit card payments and processes them securely with your merchant account.';

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
    }


    public function process_authorize_net_subscription_payment( $subscription_id) {
    	$subscription = wcs_get_subscription($subscription_id);
    	$authorize_net_subs_id = get_post_meta($subscription->id,'wc_authorize_net_gateway_subscription_id',true);
    	if(!empty($authorize_net_subs_id)){
    	 	global $woocommerce;

            FX_Authorize_Net_Configuration::environment($this->environment);
            FX_Authorize_Net_Configuration::merchantId($this->merchant_id);
            FX_Authorize_Net_Configuration::publicKey($this->public_key);
            FX_Authorize_Net_Configuration::privateKey($this->private_key);


    	 $authorize_net_subscription = authorize_net_Subscription::find("$authorize_net_subs_id");

    		if($authorize_net_subscription->status == 'Active'){
    	      $subscription->payment_complete();
    		}
       }
    }

    public  function update_authorize_net_subscription($subscription,$new_status,$old_status){
    	global $woocommerce;
        
        $settings = get_option('woocommerce_fx_authorize_net_settings');
        
        $environment = $settings['sandbox'] == 'no' ? 'production' : 'sandbox';
        $merchant_id = $settings['sandbox'] == 'no' ? $settings['merchant_id'] : $settings['sandbox_merchant_id'];
        $private_key = $settings['sandbox'] == 'no' ? $settings['private_key'] : $settings['sandbox_private_key'];
        $public_key = $settings['sandbox'] == 'no' ? $settings['public_key'] : $settings['sandbox_public_key'];

        FX_Authorize_Net_Configuration::environment($environment);
        FX_Authorize_Net_Configuration::merchantId($merchant_id);
        FX_Authorize_Net_Configuration::publicKey($public_key);
        FX_Authorize_Net_Configuration::privateKey($private_key);



    	if($new_status == 'cancelled' || $new_status == 'expired'){
    		$authorize_net_subs_id = get_post_meta($subscription->id,'wc_authorize_net_gateway_subscription_id',true);
    		if(!empty($authorize_net_subs_id)){
    		$result = authorize_net_Subscription::cancel($authorize_net_subs_id);
    		   if ($result->success){
    			  $subscription->add_order_note( __( 'Subscription cancelled with authorizeddotnet.Subscription Id'.$authorize_net_subs_id, 'woocommerce-subscriptions' ) );
    			 }
    			 else{
    				 foreach (($result->errors->deepAll()) as $error) {
    					$subscription->add_order_note( __( 'Auto-Cancellation of authorize.net Subscription failed.Error Message : '.$error->message, 'woocommerce-subscriptions' ) );
    				 }
    				 
    			}

    		}
    		
    		
    	}
    }

    /**
     * Admin Panel Options
     */
    public function admin_options() { ?>
        <h3>FX Authorize.net Settings</h3>
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
            echo '<div class="error"><p>' . sprintf(__('Fx authorize.net Payment Gateway Error: authorize.net requires PHP 5.2.1 and above. You are using version %s.', 'fx-authorize-net'), phpversion()) . '</p></div>';
        }

        // Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected
        elseif ('no' == get_option('woocommerce_force_ssl_checkout') && !class_exists('WordPressHTTPS')) {
            echo '<div class="error"><p>' . sprintf(__('Fx authorize.net Payment Gateway is enabled, but the <a href="%s">force SSL option</a> is disabled; your checkout may not be secure!', 'fx-authorize-net'), admin_url('admin.php?page=wc-settings&tab=checkout')) . '</p></div>';
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
                'title' => __('Enable/Disable', 'fx-authorize-net'),
                'label' => __('Enable Fx authorize.net Payment Gateway', 'fx-authorize-net'),
                'type' => 'checkbox',
                'description' => '',
                'default' => 'no'
            ),
            'title' => array(
                'title' => __('Title', 'fx-authorize-net'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'fx-authorize-net'),
                'default' => __('Credit card', 'fx-authorize-net'),
                'desc_tip' => true
            ),
            'description' => array(
                'title' => __('Description', 'fx-authorize-net'),
                'type' => 'textarea',
                'description' => __('This controls the description which the user sees during checkout.', 'fx-authorize-net'),
                'default' => 'Pay securely with your credit card.',
                'desc_tip' => true
            ),
            'sandbox' => array(
                'title' => __('Sandbox', 'fx-authorize-net'),
                'label' => __('Enable Sandbox Mode', 'fx-authorize-net'),
                'type' => 'checkbox',
                'description' => __('Place the payment gateway in sandbox mode using sandbox API keys (real payments will not be taken).', 'fx-authorize-net'),
                'default' => 'yes'
            ),
            'submit_settlement' => array(
                'title' => __('Enable', 'fx-authorize-net'),
                'label' => __('Submit For Settlement', 'fx-authorize-net'),
                'type' => 'checkbox',
                'default' => 'yes'
            ),
            'sandbox_merchant_login_id' => array(
                'title' => __('Sandbox Merchant LOGIN ID', 'fx-authorize-net'),
                'type' => 'text',
                'description' => __('Get your API keys from your authorize.net account.', 'fx-authorize-net'),
                'default' => '',
                'desc_tip' => true
            ),
            'sandbox_merchant_transaction_key' => array(
                'title' => __('Sandbox Merchant Transaction Key', 'fx-authorize-net'),
                'type' => 'text',
                'description' => __('Get your API keys from your authorize.net account.', 'fx-authorize-net'),
                'default' => '',
                'desc_tip' => true
            ),
            'sandbox_valid_cards' => array(
                'title' => __('Select Card type', 'fx-authorize-net'),
                'type' => 'multiselect',
                'default' => '',
                'options' => array('Visa', 'Mastercard', 'American Express', 'Discover', 'JCB', 'Diner`s', 'Maestro'),
                'class' => 'wc-enhanced-select'
            ),
            'sandbox_cse_key' => array(
                'title' => __('Sandbox CSE Key', 'fx-authorize-net'),
                'type' => 'textarea',
                'description' => __('Get your API keys from your authorize.net account.', 'fx-authorize-net'),
                'default' => '',
                'desc_tip' => true
            ),
            'merchant_login_id' => array(
                'title' => __('Production Merchant LOGIN ID', 'fx-authorize-net'),
                'type' => 'text',
                'description' => __('Get your API keys from your authorize.net account.', 'fx-authorize-net'),
                'default' => '',
                'desc_tip' => true
            ),
            'merchant_transaction_key' => array(
                'title' => __('Production Merchant Transaction Key', 'fx-authorize-net'),
                'type' => 'text',
                'description' => __('Get your API keys from your authorize.net account.', 'fx-authorize-net'),
                'default' => '',
                'desc_tip' => true
            ),
            'cse_key' => array(
                'title' => __('Production CSE Key', 'fx-authorize-net'),
                'type' => 'textarea',
                'description' => __('Get your API keys from your authorize.net account.', 'fx-authorize-net'),
                'default' => '',
                'desc_tip' => true
            ),
            'debug' => array(
                'title' => __('Debug', 'fx-authorize-net'),
                'type' => 'checkbox',
                'label' => __('Enable logging <code>/wp-content/uploads/wc-logs/fx-authorize-net.log</code>', 'fx-authorize-net'),
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
                <label for="authorizeddotnet-card-number"><?php echo __('Card Number', 'fx-authorize-net') ?> <span class="required">*</span></label>
                <input type="text" data-encrypted-name="authorizeddotnet-card-number" placeholder="" autocomplete="off" maxlength="20" class="input-text wc-credit-card-form-card-number" id="fx-authorize-net-card-number" name='fx-authorize-net-card-number'>
            </p>

            <p class="form-row form-row-first authorizeddotnet-card-expiry">
                <label for="authorizeddotnet-card-expiry-month"><?php echo __('Expiry', 'fx-authorize-net') ?> <span class="required">*</span></label>
                <select name="fx-authorize-net-card-expiry-month" id="fx-authorize-net-card-expiry-month" class="input-text">
                    <option value=""><?php _e('Month', 'fx-authorize-net') ?></option>
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

                <select name="fx-authorize-net-card-expiry-year" id="fx-authorize-net-card-expiry-year" class="input-text">
                    <option value=""><?php _e('Year', 'fx-authorize-net') ?></option><?php
                    for ($iYear = date('Y'); $iYear < date('Y') + 21; $iYear++) {
                        echo '<option value="' . $iYear . '">' . $iYear . '</option>';
                    }
                    ?>
                </select>
            </p>

            <p class="form-row form-row-last">
                <label for="authorizeddotnet-card-cvc"><?php echo __('Card Code', 'fx-authorize-net') ?> <span class="required">*</span></label>
                <input type="text" data-encrypted-name="authorizeddotnet-card-cvc" placeholder="CVC" autocomplete="off" class="input-text wc-credit-card-form-card-cvc" name ='fx-authorize-net-card-cvc' id="fx-authorize-net-card-cvc">
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


    public function cancelSubscription($subscriptionId){
    	/* Create a merchantAuthenticationType object with authentication details
    	   retrieved from the constants file */
    	$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    	$merchantAuthentication->setName($this->settings['sandbox_merchant_login_id']);
    	$merchantAuthentication->setTransactionKey($this->settings['sandbox_merchant_transaction_key']);
    	
    	// Set the transaction's refId
    	$refId = 'ref' . time();

    	$request = new AnetAPI\ARBCancelSubscriptionRequest();
    	$request->setMerchantAuthentication($merchantAuthentication);
    	$request->setRefId($refId);
    	$request->setSubscriptionId($subscriptionId);

    	$controller = new AnetController\ARBCancelSubscriptionController($request);

    	$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

    	if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
    	{
    		$successMessages = $response->getMessages()->getMessage();
    		//echo "SUCCESS : " . $successMessages[0]->getCode() . "  " .$successMessages[0]->getText() . "\n";
    		
    	 }
    	else
    	{
    		echo "ERROR :  Invalid response\n";
    		$errorMessages = $response->getMessages()->getMessage();
    		echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    		
    	}
    	return $response;
    }


    public function free_subscription($order_id){
    	$order = new WC_Order($order_id);													// Get Order To Free Subscription
    	if($order){
    		$subs_id = $order->meta_data[0]->value;											// Get Subscription Id of the given Order
    		$cancelSubscription = $this->cancelSubscription($subs_id);						// Cancel Authorize.Net Subcription
    	}
    	else{
    		wc_add_notice("Subscription error - Order not found. Please re-check OrderId" , 'error');
    		return array('result' => 'fail','redirect' => '');
    	}
    }


    public function process_payment($order_id) {
    	$userLogined = wp_get_current_user();
    	$userLogined_id = $userLogined->data->ID;
    	$subs = wcs_get_users_subscriptions($userLogined_id);
    	
    	foreach($subs as $subscriptions => $data){
    		
    		/*echo "<br>";
    		echo $data->data['status'];
    		echo "<br>";*/
    		
    		if(count($data->meta_data) > 0){
    			$current_subs_id = $data->meta_data[0]->value;
    			$current_order_id = $data->id;
    			
    			if($data->data['status'] === 'active'){
    				$cancelSubscription = $this->cancelSubscription($current_subs_id);			// Cancel Authorize Subcription
    				$order = new WC_Order($current_order_id);									// Get Order To Cancel Subscription
    				$order->update_status('cancelled', 'order_status');							// Cancel Subscription
    			}
    		}
    		
    	}
    	
        global $woocommerce;
        $order = new WC_Order($order_id);
        $order_id = $order->id;
        
        $get_card_value = get_option('woocommerce_fx_authorize_net_settings');
        $card_value = maybe_unserialize($get_card_value);
        $card_array = $card_value['sandbox_valid_cards'];
        if (isset($card_array) && !empty($card_array)) {
            $card_no = $this->wc_card_type($_POST['fx-authorize-net-card-number']);
            if (!in_array($card_no, $card_array)) {
                wc_add_notice(__("This card is not acceptable", 'woocommerce'), 'error');
                return false;
            }
        }
        $submit_settlement_option = get_option('woocommerce_fx_authorize_net_settings');
        $settlement_value = $submit_settlement_option['submit_settlement'];
        
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
            
            $OrderTotal = $order->order_total;

    		/***** PAYMENT Process Begins HERE ***************/
            //if(!empty($OrderTotal) && $OrderTotal > 0){
    			
    			if(!$signup_fee){			// No Signup Fee, Just Subscription
    				$subscription_period = get_post_meta($product->id,'_subscription_period');
    				$subscription_period_interval = get_post_meta($product->id,'_subscription_period_interval');
    				$subscription_length = get_post_meta($product->id,'_subscription_length');
    				$subscription_price = get_post_meta($product->id,'_subscription_price');
    				
    				$subscription_trial_period = get_post_meta($product->id,'_subscription_trial_period');				//Trial Period
    				$subscription_trial_length = get_post_meta($product->id,'_subscription_trial_length');				//Trial Length
    				$subscription_trial_amount = get_post_meta($product->id,'_subscription_trial_amount');				//Trial Amount
    				
    				
    				$subscription_trial = false;
    				if($subscription_trial_length[0] > 0)
    					$subscription_trial = true;
    					
    				if($subscription_trial){
    					//If Trial Amount exists? Then it will get deducted at the time of Transcation
    					if($subscription_trial_amount[0] > 0){
    						die("XXXX");
    						/********************************************************
    						**************** Trial Amount Deduction *****************
    						********************************************************/
    						
    						$card_no = $_POST['fx-authorize-net-card-number'];
    						$card_exp_yr = $_POST['fx-authorize-net-card-expiry-year'];
    						$card_exp_mnth = $_POST['fx-authorize-net-card-expiry-month'];
    						$card_cod = $_POST['fx-authorize-net-card-cvc'];
    						
    						$set_desc = "Description of the Trial Amount";
    						
    						$set_first_name = isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '';
    						$set_last_name = isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '';
    						$set_company = isset($_POST['billing_company']) ? $_POST['billing_company'] : '';
    						$set_city = isset($_POST['billing_city']) ? $_POST['billing_city'] : '';
    						$set_address = isset($_POST['billing_city']) ? $_POST['billing_city'] : '';
    						$set_state = isset($_POST['billing_state']) ? $_POST['billing_state'] : '';
    						$set_postcode = isset($_POST['billing_postcode']) ? $_POST['billing_postcode'] : '';
    						$set_country = isset($_POST['billing_country']) ? $_POST['billing_country'] : '';
    						
    						$set_email = isset($_POST['billing_email']) ? $_POST['billing_email'] : '';
    						
    						$set_amt = $subscription_trial_amount[0];			// Trial Amount
    						
    						/* One Time Payment */
    						$response = $this->create_one_time_payment($card_no,$card_exp_yr,$card_exp_mnth,$card_cod,$set_desc,$set_first_name,$set_last_name,$set_company,$set_city,$set_address,$set_state,$set_postcode,$set_country,$set_email,$set_amt);

    						if ($response != null) {
    							// Check to see if the API request was successfully received and acted upon
    							if ($response->getMessages()->getResultCode() == "Ok") {
    								// Since the API request was successful, look for a transaction response
    								// and parse it to display the results of authorizing the card
    								$tresponse = $response->getTransactionResponse();
    								if ($tresponse != null && $tresponse->getMessages() != null) {
    									/*echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
    									echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
    									echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
    									echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
    									echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";*/
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
    						
    						/********************************************************
    						*************** //Trial Amount Deduction ****************
    						********************************************************/
    					}
    				}
    				
    				/********************************************************
    				************ Recurring After Trial(If There) ************
    				********************************************************/
    				
    				$set_first_name = isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '';
    				$set_last_name = isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '';
    				$set_start_date= new DateTime($order->order_date);
    				
    				if($subscription_trial){
    					if($subscription_trial_period[0]=='day'){
    						$trial_days = $subscription_trial_length[0]; 
    					}
    					elseif($subscription_trial_period[0]=='week'){
    						$trial_days = $subscription_trial_length[0]*7;
    					}
    					elseif($subscription_trial_period[0]=='month'){
    						$trial_days = $subscription_trial_length[0]*30;
    					}
    					elseif($subscription_trial_period[0]=='year'){
    						$trial_days = $subscription_trial_length[0]*365;
    					}
    					$set_start_date= new DateTime(Date('Y-m-d h:i:s', strtotime($trial_days." days")));
    				}
    				
    				if($subscription_period[0] == 'week'){
    					$interval_Unit = 'days';
    					$intervalLength = $subscription_period_interval[0] * 7;
    					$subscriptionLength = $subscription_length[0] * 7;
    				}
    				else{
    					$interval_Unit = $subscription_period[0].'s';
    					$intervalLength = $subscription_period_interval[0];
    					$subscriptionLength = $subscription_length[0];
    				}
    				
    				if($subscriptionLength > 0){
    					$set_total_occurrences = $subscriptionLength/$intervalLength;
    				}
    				else{
    					$set_total_occurrences = "9999";
    				}
    				
    				$set_amount = $subscription_price[0];
    				
    				$card_no = $_POST['fx-authorize-net-card-number'];
    				$card_exp_yr = $_POST['fx-authorize-net-card-expiry-year'];
    				$card_exp_mnth = $_POST['fx-authorize-net-card-expiry-month'];
    				
    				$set_desc = "Description of the Subscription";
    				
    				$signup_amount = "";      //NOTE: Because there is not signup fee, so there will be no extra deduction after the end of Trial Period.
    				
    				/* Create Subscription */
    				$response = $this->create_subscription($set_first_name,$set_last_name,$intervalLength,$interval_Unit,$set_start_date,$set_total_occurrences,$set_amount,$signup_amount,$card_no,$card_exp_yr,$card_exp_mnth,$set_desc,$subscription_trial);
    				
    				if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    				{
    					//echo "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n";
    				}
    				else
    				{
    					echo "ERROR :  Invalid response\n";
    					$errorMessages = $response->getMessages()->getMessage();
    					echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    				}
    				
    				/********************************************************
    				*********** //Recurring After Trial(If There) ***********
    				********************************************************/
    			
    			}
    			else{				// With Trial/Signup Fee and Subscription
    				
    				$subscription_trial_length = get_post_meta($product->id,'_subscription_trial_length');				//Trial Length
    				$subscription_trial_amount = get_post_meta($product->id,'_subscription_trial_amount');				//Trial Amount
    				
    				$subscription_price = get_post_meta($product->id,'_subscription_price');
    				$signup_amount = $OrderTotal - $subscription_price[0];												//Signup Fee Only
    				
    				$subscription_trial = false;
    				if($subscription_trial_length[0] > 0)
    					$subscription_trial = true;
    				
    				if($subscription_trial){
    					//No Signup Deduction at the time of Transaction
    					//But if Trial Amount exists? Then it will get deducted at the time of Transcation
    					if($subscription_trial_amount[0] > 0){
    						
    						/********************************************************
    						**************** Trial Amount Deduction *****************
    						********************************************************/
    						
    						$card_no = $_POST['fx-authorize-net-card-number'];
    						$card_exp_yr = $_POST['fx-authorize-net-card-expiry-year'];
    						$card_exp_mnth = $_POST['fx-authorize-net-card-expiry-month'];
    						$card_cod = $_POST['fx-authorize-net-card-cvc'];
    						
    						$set_desc = "Description of the Trial Amount";
    						
    						$set_first_name = isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '';
    						$set_last_name = isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '';
    						$set_company = isset($_POST['billing_company']) ? $_POST['billing_company'] : '';
    						$set_city = isset($_POST['billing_city']) ? $_POST['billing_city'] : '';
    						$set_address = isset($_POST['billing_city']) ? $_POST['billing_city'] : '';
    						$set_state = isset($_POST['billing_state']) ? $_POST['billing_state'] : '';
    						$set_postcode = isset($_POST['billing_postcode']) ? $_POST['billing_postcode'] : '';
    						$set_country = isset($_POST['billing_country']) ? $_POST['billing_country'] : '';
    						
    						$set_email = isset($_POST['billing_email']) ? $_POST['billing_email'] : '';
    						
    						$set_amt = $subscription_trial_amount[0];			// Trial Amount
    						
    						/* One Time Payment */
    						$response = $this->create_one_time_payment($card_no,$card_exp_yr,$card_exp_mnth,$card_cod,$set_desc,$set_first_name,$set_last_name,$set_company,$set_city,$set_address,$set_state,$set_postcode,$set_country,$set_email,$set_amt);
    						

    						if ($response != null) {
    							// Check to see if the API request was successfully received and acted upon
    							if ($response->getMessages()->getResultCode() == "Ok") {
    								// Since the API request was successful, look for a transaction response
    								// and parse it to display the results of authorizing the card
    								$tresponse = $response->getTransactionResponse();
    								if ($tresponse != null && $tresponse->getMessages() != null) {
    									//echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
    									/*echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
    									echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
    									echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
    									echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";*/
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
    						
    						/********************************************************
    						*************** //Trial Amount Deduction ****************
    						********************************************************/
    					}
    				}
    				else{
    					
    					/********************************************************
    					***************** Signup Fee Deduction ******************
    					********************************************************/
    					
    					$card_no = $_POST['fx-authorize-net-card-number'];
    					$card_exp_yr = $_POST['fx-authorize-net-card-expiry-year'];
    					$card_exp_mnth = $_POST['fx-authorize-net-card-expiry-month'];
    					$card_cod = $_POST['fx-authorize-net-card-cvc'];
    					
    					$set_desc = "Description of the Signup Fee";
    					
    					$set_first_name = isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '';
    					$set_last_name = isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '';
    					$set_company = isset($_POST['billing_company']) ? $_POST['billing_company'] : '';
    					$set_city = isset($_POST['billing_city']) ? $_POST['billing_city'] : '';
    					$set_address = isset($_POST['billing_city']) ? $_POST['billing_city'] : '';
    					$set_state = isset($_POST['billing_state']) ? $_POST['billing_state'] : '';
    					$set_postcode = isset($_POST['billing_postcode']) ? $_POST['billing_postcode'] : '';
    					$set_country = isset($_POST['billing_country']) ? $_POST['billing_country'] : '';
    					
    					$set_email = isset($_POST['billing_email']) ? $_POST['billing_email'] : '';
    					
    					$set_amt = $signup_amount;			//Singup Fee
    					
    					/* One Time Payment */
    					$response = $this->create_one_time_payment($card_no,$card_exp_yr,$card_exp_mnth,$card_cod,$set_desc,$set_first_name,$set_last_name,$set_company,$set_city,$set_address,$set_state,$set_postcode,$set_country,$set_email,$set_amt);

    					if ($response != null) {
    						// Check to see if the API request was successfully received and acted upon
    						if ($response->getMessages()->getResultCode() == "Ok") {
    							// Since the API request was successful, look for a transaction response
    							// and parse it to display the results of authorizing the card
    							$tresponse = $response->getTransactionResponse();
    							if ($tresponse != null && $tresponse->getMessages() != null) {
    								/*echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
    								echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
    								echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
    								echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
    								echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";*/
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
    					
    					/********************************************************
    					***************** //Signup Fee Deduction ******************
    					********************************************************/
    				}
    				
    				/********************************************************
    				************* Recurring After Trial/Signup **************
    				********************************************************/
    				
    				$subscription_period = get_post_meta($product->id,'_subscription_period');
    				$subscription_period_interval = get_post_meta($product->id,'_subscription_period_interval');
    				$subscription_length = get_post_meta($product->id,'_subscription_length');
    				$subscription_trial_period = get_post_meta($product->id,'_subscription_trial_period');				//Trial Period
    				
    				$set_first_name = isset($_POST['billing_first_name']) ? $_POST['billing_first_name'] : '';
    				$set_last_name = isset($_POST['billing_last_name']) ? $_POST['billing_last_name'] : '';
    				$set_start_date= new DateTime($order->order_date);
    				
    				if($subscription_trial){
    					if($subscription_trial_period[0]=='day'){
    						$trial_days = $subscription_trial_length[0]; 
    					}
    					elseif($subscription_trial_period[0]=='week'){
    						$trial_days = $subscription_trial_length[0]*7;
    					}
    					elseif($subscription_trial_period[0]=='month'){
    						$trial_days = $subscription_trial_length[0]*30;
    					}
    					elseif($subscription_trial_period[0]=='year'){
    						$trial_days = $subscription_trial_length[0]*365;
    					}
    					$set_start_date= new DateTime(Date('Y-m-d h:i:s', strtotime($trial_days." days")));
    				}
    				
    				if($subscription_period[0] == 'week'){
    					$interval_Unit = 'days';
    					$intervalLength = $subscription_period_interval[0] * 7;
    					$subscriptionLength = $subscription_length[0] * 7;
    				}
    				else{
    					$interval_Unit = $subscription_period[0].'s';
    					$intervalLength = $subscription_period_interval[0];
    					$subscriptionLength = $subscription_length[0];
    				}
    				
    				if($subscriptionLength > 0){
    					$set_total_occurrences = $subscriptionLength/$intervalLength;
    				}
    				else{
    					$set_total_occurrences = "9999";
    				}
    				
    				$set_amount = $subscription_price[0];
    				
    				if($subscription_trial){
    					$signup_amount = $OrderTotal;										//Because in this case Order Total will not contain Subscription Fee
    				}
    				
    				$card_no = $_POST['fx-authorize-net-card-number'];
    				$card_exp_yr = $_POST['fx-authorize-net-card-expiry-year'];
    				$card_exp_mnth = $_POST['fx-authorize-net-card-expiry-month'];
    				
    				$set_desc = "Description of the Subscription";
    				
    				/* Create Subscription */
    				$response = $this->create_subscription($set_first_name,$set_last_name,$intervalLength,$interval_Unit,$set_start_date,$set_total_occurrences,$set_amount,$signup_amount,$card_no,$card_exp_yr,$card_exp_mnth,$set_desc,$subscription_trial);
    				
    				if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
    				{
    					//echo "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n";
    				}
    				else
    				{
    					echo "ERROR :  Invalid response\n";
    					$errorMessages = $response->getMessages()->getMessage();
    					echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
    				}
    				
    				//echo "<br>";
    				//die("END");
    				
    				/********************************************************
    				************ //Recurring After Trial/Signup *************
    				********************************************************/
    				
    			}
            
    		//}
    		/***** //PAYMENT Process Ends HERE ***************/

            
          
            
           /* ******** Result Handeling Begins ***********/ 
    		if ($response != null) {
    			// get subscription of order
    			$subscriptions_ids = wcs_get_subscriptions_for_order( $order_id );
    			
    			foreach( $subscriptions_ids as $subscription_id => $subscription_obj ){
    				update_post_meta($subscription_id, 'wc_authorize_net_gateway_subscription_id', $response->getSubscriptionId());
    				$subscription_obj->add_order_note(sprintf(__('%s authorize.net Subscription ID: %s', 'fx-authorize-net'), $this->title, $response->getSubscriptionId()));
    			}					

    			// Payment complete
    			$trans_id = $response->getSubscriptionId();
    			$order->payment_complete($trans_id);
    			
    			// Add order note
    			$order->add_order_note(sprintf(__('%s payment approved! Subscription ID: %s', 'fx-authorize-net'), $this->title, $response->getSubscriptionId()));
    			//$order->add_order_note(sprintf(__('%s payment approved! Transaction ID: %s', 'fx-authorize-net'), $this->title, $response->getSubscriptionId()));
    			//update_post_meta($order_id, 'wc_authorize_net_gateway_transaction_id', $response->getSubscriptionId());

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
    			if(is_ajax()){
    				return $result = array(
    					'redirect' => $this->get_return_url($order),
    					'result' => 'success'
    				);
    			}
    			else{
    				
    				if(IS_LOCAL){
    					wp_redirect(get_site_url().'/index.php/index.php/dashboard/');
    				}
    				else{
    					wp_redirect(get_site_url().'/dashboard/');
    				}
    				
    				exit();
    			}
    			
    		}
    		else if ($result->transaction) {
    			$order->add_order_note(sprintf(__('%s payment declined.<br />Error: %s<br />Code: %s', 'fx-authorize-net'), $this->title, $result->message, $result->transaction->processorResponseCode));
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
        /*else{
    	}*/
    	// Non Subscription Tarnsaction Ends
        
    }

    public function create_one_time_payment($card_no,$card_exp_yr,$card_exp_mnth,$card_cod,$set_desc,$set_first_name,$set_last_name,$set_company,$set_city,$set_address,$set_state,$set_postcode,$set_country,$set_email,$set_amt){
    	
    	/* Create a merchantAuthenticationType object with authentication details */
    	$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    	$merchantAuthentication->setName($this->settings['sandbox_merchant_login_id']);
    	$merchantAuthentication->setTransactionKey($this->settings['sandbox_merchant_transaction_key']);
    	
    	// Create the payment data for a credit card
    	$creditCard = new AnetAPI\CreditCardType();
    	$creditCard->setCardNumber($card_no);
    	$creditCard->setExpirationDate($card_exp_yr."-".$card_exp_mnth);
    	$creditCard->setCardCode($card_cod);
    	
    	// Add the payment data to a paymentType object
    	$paymentOne = new AnetAPI\PaymentType();
    	$paymentOne->setCreditCard($creditCard);
    	
    	// Set the transaction's refId
    	$refId = 'ref' . time();
    	
    	// Create order information
    	$order_Anet = new AnetAPI\OrderType();
    	$order_Anet->setInvoiceNumber(rand());
    	$order_Anet->setDescription($set_desc);
    	
    	// Set the customer's Bill To address
    	$customerAddress = new AnetAPI\CustomerAddressType();
    	$customerAddress->setFirstName($set_first_name);
    	$customerAddress->setLastName($set_last_name);
    	$customerAddress->setCompany($set_company);
    	$customerAddress->setCity($set_city);
    	$customerAddress->setAddress($set_address);									//DBT
    	$customerAddress->setState($set_state);
    	$customerAddress->setZip($set_postcode);
    	$customerAddress->setCountry($set_country);
    	
    	// Set the customer's identifying information
    	$customerData = new AnetAPI\CustomerDataType();
    	$customerData->setType("individual");
    	$customerData->setId(rand());
    	$customerData->setEmail($set_email);
    	
    	// Add some merchant defined fields. These fields won't be stored with the transaction,
    	// but will be echoed back in the response.
    	$merchantDefinedField1 = new AnetAPI\UserFieldType();
    	$merchantDefinedField1->setName("customerLoyaltyNum");
    	$merchantDefinedField1->setValue(rand());

    	$merchantDefinedField2 = new AnetAPI\UserFieldType();
    	$merchantDefinedField2->setName("favoriteColor");
    	$merchantDefinedField2->setValue("blue");
    	
    	// Create a TransactionRequestType object and add the previous objects to it
    	$transactionRequestType = new AnetAPI\TransactionRequestType();
    	$transactionRequestType->setTransactionType("authCaptureTransaction");
    	$transactionRequestType->setAmount($set_amt);										//Trial Amount
    	$transactionRequestType->setOrder($order_Anet);
    	$transactionRequestType->setPayment($paymentOne);
    	$transactionRequestType->setBillTo($customerAddress);
    	$transactionRequestType->setCustomer($customerData);
    	$transactionRequestType->addToUserFields($merchantDefinedField1);
    	$transactionRequestType->addToUserFields($merchantDefinedField2);
    	
    	// Assemble the complete transaction request
    	$request = new AnetAPI\CreateTransactionRequest();
    	$request->setMerchantAuthentication($merchantAuthentication);
    	$request->setRefId($refId);
    	$request->setTransactionRequest($transactionRequestType);
    	
    	// Create the controller and get the response
    	$controller = new AnetController\CreateTransactionController($request);
    	return $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }

    public function create_subscription($set_first_name,$set_last_name,$intervalLength,$interval_Unit,$set_start_date,$set_total_occurrences,$set_amount,$signup_amount,$card_no,$card_exp_yr,$card_exp_mnth,$set_desc,$subscription_trial){
    	/* Create a merchantAuthenticationType object with authentication details */
    	$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    	$merchantAuthentication->setName($this->settings['sandbox_merchant_login_id']);
    	$merchantAuthentication->setTransactionKey($this->settings['sandbox_merchant_transaction_key']);
    	
    	// Set the transaction's refId
    	$refId = 'ref' . time();

    	// Subscription Type Info
    	$subscription = new AnetAPI\ARBSubscriptionType($intervalLength);
    	$subscription->setName($set_first_name);
    	
    	$interval = new AnetAPI\PaymentScheduleType\IntervalAType();
    	$interval->setLength($intervalLength);				//Exp: 1
    	$interval->setUnit($interval_Unit);					//Exp: months

    	$paymentSchedule = new AnetAPI\PaymentScheduleType();
    	$paymentSchedule->setInterval($interval);
    	
    	$paymentSchedule->setStartDate($set_start_date);
    	$paymentSchedule->setTotalOccurrences($set_total_occurrences);
    	
    	if($subscription_trial && $signup_amount){
    		$paymentSchedule->setTrialOccurrences("1");							//i.e. it will charge only once for Trial Period
    		$subscription->setTrialAmount($signup_amount);						//It is actually the Signup Fee, which will got deducted after the end of Trial Period
    	}
    	
    	$subscription->setPaymentSchedule($paymentSchedule);
    	$subscription->setAmount($set_amount);
    	
    	// Create the payment data for a credit card
    	$creditCard = new AnetAPI\CreditCardType();
    	$creditCard->setCardNumber($card_no);
    	$creditCard->setExpirationDate($card_exp_yr."-".$card_exp_mnth);
    	
    	// Add the payment data to a paymentType object
    	$payment = new AnetAPI\PaymentType();
    	$payment->setCreditCard($creditCard);
    	$subscription->setPayment($payment);
    	
    	// Create order information
    	$order_Anet = new AnetAPI\OrderType();
    	$order_Anet->setInvoiceNumber(rand());        
    	$order_Anet->setDescription($set_desc); 
    	$subscription->setOrder($order_Anet);
    	
    	$billTo = new AnetAPI\NameAndAddressType();
    	$billTo->setFirstName($set_first_name);
    	$billTo->setLastName($set_last_name);
    	$subscription->setBillTo($billTo);
    	
    	// Assemble the complete transaction request
    	$request = new AnetAPI\ARBCreateSubscriptionRequest();
    	$request->setmerchantAuthentication($merchantAuthentication);
    	$request->setRefId($refId);
    	$request->setSubscription($subscription);
    	
    	// Create the controller and get the responses
    	$controller = new AnetController\ARBCreateSubscriptionController($request);
    	return $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }

    public function process_refund($order_id, $amount = null, $reason = '') {
        $order = new WC_Order($order_id);
        //require_once( 'includes/authorizeddotnet.php' );

        FX_Authorize_Net_Configuration::environment($this->environment);
        FX_Authorize_Net_Configuration::merchantId($this->merchant_id);
        FX_Authorize_Net_Configuration::publicKey($this->public_key);
        FX_Authorize_Net_Configuration::privateKey($this->private_key);
        $transation_id = get_post_meta($order_id, 'wc_authorize_net_gateway_transaction_id', true);

        $result = authorize_net_Transaction::refund($transation_id, $amount);

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
            $wc_message = apply_filters('wc_authorize_net_refund_message', $result->message, $result);
            $this->add_log(print_r($result, true));
            return new WP_Error('wc_authorize_net_gateway_refund-error', $wc_message);
        }
    }

    /**
     * Use WooCommerce logger if debug is enabled.
     */
    function add_log($message) {
        if ($this->debug == 'yes') {
            if (empty($this->log))
                $this->log = new WC_Logger();
            $this->log->add('fx_authorize_net', $message);
        }
    }

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
    		'description' => __( 'Enter authorizeddotnet Subscription Plan Id from your authorize.net account.', 'woocommerce' ) 
    	)
    );

    /*woocommerce_wp_text_input(
    	array(
    		'id' => '_trial_amount_text_field',
    		'placeholder' => 'Trial Amount',
    		'label' => __('Trial Amount', 'woocommerce'),
    		'desc_tip' => 'true'
    	)
    );*/
  
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
