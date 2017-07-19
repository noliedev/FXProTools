<?php
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

function wp_parse_user($username, $password, $email)
{
	if (!username_exists($username)  && !email_exists($email)){
		$user_data = array(
			'user_login'    => $username,
			'user_pass'     => $password,
			'user_nicename' => $username,
			'user_email'    => $email,
			'role'          => 'subscriber'
		);
		$user_id = wp_insert_user($user_data);
		$status = ( !is_wp_error($user_id) ? 'success' : 'failed' );
		// Added automatically to wocommerce subscriptions
		if($status == 'success') {
			wc_add_subscription_bs($email, 48);
		}
	} else {
		$status = 'failed';
	}
	return $status;
}
