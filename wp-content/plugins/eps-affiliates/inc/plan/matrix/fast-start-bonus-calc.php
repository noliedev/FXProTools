<?php 
/**
 * -----------------------------------------------------------------
 * Here calculate the fast start bonus
 * 
 * Condition 1 :  Enable fast start bonus
 * Condition 2 :  Not paid before
 * Condition 3 :  Set the bonus amount
 * Condition 4 :  Required Condition meets
 * -----------------------------------------------------------------
*/
	function calculate_distributor_fast_start_bonus ($uid = '', $assc_uid = '') {
		if ( afl_variable_get('enable_fast_start_bonus')) {
			$bonus_amount = afl_variable_get('fast_start_bonus_amount', 0);
			if ( !_check_fsb_already_paid( $uid, $assc_uid ) ) {
				if ( $bonus_amount ) {
					$condition_meets = TRUE;
					if ( $condition_meets ) {
						 $transaction = array();
					   $transaction['uid'] 								= $uid;
					   $transaction['associated_user_id'] = $assc_uid;
					   $transaction['payout_id'] 					= 0;
					   $transaction['level']							= 0;
					   $transaction['currency_code'] 			= afl_currency();
					   $transaction['order_id'] 					= 0;
					   $transaction['int_payout'] 				= 0;
					   $transaction['hidden_transaction'] = 0;
					   $transaction['credit_status'] 			= 1;
					   $transaction['amount_paid'] 				= afl_commerce_amount($bonus_amount);
					   $transaction['category'] 					= 'FAST START BONUS';
					   $transaction['notes'] 							= 'fast start bonus amount received';

    				afl_member_transaction($transaction, TRUE);
					}
				}
			}
		}
	}
/*
 * ----------------------------------------------------------------
 * Check the user already get the bonus for a specific member
 * ----------------------------------------------------------------
*/
 function _check_fsb_already_paid($uid = '', $assc_uid = ''){
	$query = array();
	$query['#select']  =_table_name('afl_user_transactions');
	$query['#where']   = array(
		'hidden_transaction = 0',
		'deleted = 0',
		'uid='.$uid,
		'associated_user_id='.$assc_uid
	);
	$result = db_select($query, 'get_results');

	if (count($result)) {
		return true;
	} else {
		return false;
	}
 }
