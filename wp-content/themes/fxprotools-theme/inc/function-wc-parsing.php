<?php
/**
 * ------------
 * WC Add Order
 * ------------
 * Add order to woocommerce programatically
 */
function wc_add_order($user_data, $user)
{
	global $woocommerce;

	$product_id 	   = $user_data['sub_product'];
	$variation_id      = $user_data['sub_variation'];
	$user_first_name   = $user_data['first_name'];
	$user_last_name    = $user_data['last_name'];
	$user_email        = $user_data['user_email'];
	$billing_email     = $user_data['user_email'];
	$billing_state     = $user_data['cpm_state'];
	$billing_post_code = $user_data['cpm_zip'];
	$billing_country   = $user_data['cpm_country'];

	$variation_factory = new WC_Product_Variation($variation_id);
	$variation_obj     = $variation_factory->get_variation_attributes();
	$quantity          = 1;
	$user_id           = $user->user_id;
	
	$order = wc_create_order();
	update_post_meta($order->id, '_billing_first_name', $user_first_name);
	update_post_meta($order->id, '_billing_last_name', $user_last_name);
	update_post_meta($order->id, '_billing_email', $billing_email);
	update_post_meta($order->id, '_billing_state', $billing_state);
	update_post_meta($order->id, '_billing_postcode', $billing_post_code);
	update_post_meta($order->id, '_shipping_first_name', $user_first_name);
	update_post_meta($order->id, '_shipping_last_name', $user_last_name);
	
	$product_to_add = get_product($product_id);
	$price = $variation_factory->get_price();
	$price_params = array(
		'variation' => $variation_obj,
		'totals' => array(
			'subtotal'     => $price*$quantity,
			'total'        => $price * $quantity,
			'subtotal_tax' => 0,
			'tax'          => 0
		)
	);

	$order->add_product(wc_get_product($product_id), $quantity, $price_params);
	$order->set_address($address, 'billing');
	$order->set_address($address, 'shipping');
	$order->set_total($price * $quantity , 'order_discount');
	$order->set_payment_method($this);
	$order->calculate_totals();
	update_post_meta($order->id, '_customer_user', $user_id);

	wc_add_subscription($order, $product_id, $price_params, $variation_id);
}

/**
 * -------------------
 * WC Add Subscription
 * -------------------
 * Add subscription to woocommerce programatically
 */
function wc_add_subscription($order, $product_id, $price_args, $variation_id)
{
	$quantity   = 1;
	$start_date = $order->order_date;
	$period     = WC_Subscriptions_Product::get_period($variation_id);
	$interval   = WC_Subscriptions_Product::get_interval($variation_id);

	$sub_args = array(
		'order_id'         => $order->id,
		'billing_period'   => $period,
		'billing_interval' => $interval,
		'start_date'       => $start_date
	);

	$subscription = wcs_create_subscription($sub_args);
	$subscription->add_product(wc_get_product($product_id), $quantity, $price_args);
	$subscription->calculate_totals();

	WC_Subscriptions_Manager::activate_subscriptions_for_order($order);
}


/**
 * --------------------------------------
 * WC Add Subscription - Business Product
 * --------------------------------------
 * Add subscription to business product(id: 48)
 */
function wc_add_subscription_bs($user_email, $product_id)
{
	$user     = get_user_by('email', $user_email);
	$quantity = 1;
	$period   = WC_Subscriptions_Product::get_period($product_id);
	$interval = WC_Subscriptions_Product::get_interval($product_id);

	$date['start_date']   = date('Y-m-d H:i:s', strtotime('2017/6/1'));
	$date['next_payment'] = date('Y-m-d H:i:s', strtotime('2017/9/1'));

	$sub_args = array(
		'status'           => 'active',
		'customer_id'      => $user->id,
		'start_date'       => $date['start_date'],
		'billing_period'   => $period,
		'billing_interval' => $interval,
	);

	$subscription = wcs_create_subscription($sub_args);
	$subscription->update_dates(array('schedule_next_payment' => $date['next_payment']));
	$subscription->add_product(wc_get_product($product_id), $quantity, $price_args);
	$subscription->calculate_totals();
}

function wc_parse_subscription()
{
	ini_set('max_execution_time', 0);
	$users = get_users(array('role__not_in' => 'administrator'));
	foreach($users as $user){
		wc_add_subscription_bs($user->user_email, 48);
	}
}
