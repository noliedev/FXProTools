<?php 

function afl_ewallet_withdraw_fund(){
		echo afl_eps_page_header();

	global $wpdb;
	$uid 					 = get_current_user_id();
	$payment_method = $wpdb->get_row("SELECT * FROM wp_afl_user_payment_methods WHERE (uid = '$uid' AND status= '". 1 ."')");
	if(!$payment_method || !$payment_method->completed){
			echo wp_set_message('Please set your payment method details first before proceeding withdrawal ', 'warning');
			/*
				goto set payment method forms
			*/
	}
	else{
		$password = $wpdb->get_row("SELECT * FROM wp_afl_transaction_authorization WHERE (uid = '$uid' )");
  	if(!$password){
  		echo wp_set_message('Please create a transaction password before proceeding', 'warning');
  		/*
				goto set payment password set forms
			*/
  	}
  	$afl_available_payment_method_array = list_extract_allowed_values(afl_variable_get('payment_methods'),'list_text',FALSE);
 		$form = array();
 		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

  	$form['withdrwal_amount']= array(
    	'#type' 				=> 'text',
    	'#default_value'=> afl_variable_get('withdrwal_amount', ''),
    	'#title' 				=> 'Amount to be withdraw ($)',
	 		'#prefix'				=> '<div class="form-group row">',
	 		'#suffix' 			=> '</div>'
  	);
  	$form['password']= array(
    	'#type' 				=> 'password',
    	'#title' 				=> 'Transaction Password',
    	'#default_value'=> afl_variable_get('password', ''),
	 		'#prefix'				=> '<div class="form-group row">',
	 		'#suffix' 			=> '</div>'
  	);
  	$form['my_payment_methods']= array(
    '#type' 					=> 'radio',
    '#title' 					=> 'Select Payment Methods',
    '#name'  					=> 'payment_method',
    '#options' 				=> list_extract_allowed_values(afl_variable_get('payout_methods'),'list_text',FALSE),
    '#prefix'					=> '<div class="form-group row">',
	 	'#suffix' 				=> '</div>'
  );
		$path="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
  	 $form['forgot_password'] = array(
      '#type' 				=> 'markup',
      '#markup' 			=> '<div class ="forget-transaction-password"><a href ="'.$path.'" class="btn btn-primary"> Forgot Password</a></div>',
       '#prefix'			=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
    );
  
  	$payout_methods = list_extract_allowed_values(afl_variable_get('payout_methods'),'list_text',FALSE);
  	
  	

  	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Save configuration'
	 	);
	 	echo afl_render_form($form);
		  
		$method ='method_bank';
		$method = 'method_paypal';  
  	$balance = $wpdb->get_var("SELECT  SUM(`wp_afl_user_transactions`.`balance`) as balance FROM `wp_afl_user_transactions` WHERE `uid` = $uid AND `deleted` = 0 AND `int_payout` = 0 AND `int_return` = 0");	
  	$balance = (!empty($balance) ?$balance  : 0);
  
		$processed_for_payments = $wpdb->get_var("SELECT  SUM(`wp_afl_user_transactions`.`balance`) as balance FROM `wp_afl_user_transactions` WHERE `uid` = $uid AND `deleted` = 0 AND `int_payout` >= 1 ");	
		$processed_for_payments = (!empty($processed_for_payments) ?$processed_for_payments  : 0);

  	$processing_charge = afl_variable_get('payout_charges_'.$method, -100);
  	$withdrawal_max_value = /*afl_commerce_amount*/( afl_variable_get('withdrawal_max_value', -100));
  	$withdrawal_min_value = /*afl_commerce_amount*/( afl_variable_get('withdrawal_min_value',-100) );

  	$eligible_amount = afl_get_max_withrawal_amount($processing_charge ,$withdrawal_max_value, $withdrawal_min_value, $balance);

  	
  
  

	$table 								= array();
	$table['#name'] 			= '';
	$table['#title'] 			= '';
	$table['#prefix'] 		= '';
	$table['#suffix'] 		= '';
	$table['#attributes'] = array(
						'class'=> array(
								'table',
								'table-responsive'
						)
					);

	$table['#header'] 		= array('Particulars ','Amount');
	// $table['#header'][] 		= array('Particulars & Amount');
	$rows[0][] = array(
		'#type' => 'label',
		'#title'=> 'Eligible amount for withdrawal',
 	);
	$rows[0][] = array(
		'#type' => 'label',
		'#title'=> afl_format_payment_amount($balance, FALSE)
 	);
	$rows[1][] = array(
		'#type' => 'label',
		'#title'=> 'E-wallet amount already in payout process',
 	);
	$rows[1][] = array(
		'#type' => 'label',
		'#title'=> afl_format_payment_amount($processed_for_payments, FALSE),	
 	);
 	$rows[2][] = array(
		'#type' => 'label',
		'#title'=> 'Preferred Payment Method',
 	);
	$rows[2][] = array(
		'#type' => 'label',
		'#title'=> $method,	
 	);

 	$rows[3][] = array(
		'#type' => 'label',
		'#title'=> 'Processing Charges -'.$method,
 	);
	$rows[3][] = array(
		'#type' => 'label',
		'#title'=> $processing_charge,	
 	);
	$rows[4][] = array(
		'#type' => 'label',
		'#title'=> 'Minimum withdrawal Amount',
 	);
	$rows[4][] = array(
		'#type' => 'label',
		'#title'=> $withdrawal_min_value,	
 	);
 	$rows[5][] = array(
		'#type' => 'label',
		'#title'=> 'Maximum withdrawal Percentage / Amount',
 	);
	$rows[5][] = array(
		'#type' => 'label',
		'#title'=> $withdrawal_max_value,	
 	);
 		$rows[6][] = array(
		'#type' => 'label',
		'#title'=> 'Available Maximum Withdrawal Amount(Inc. processing charge)',
 	);
	$rows[6][] = array(
		'#type' => 'label',
		'#title'=> afl_format_payment_amount($eligible_amount,FALSE),	
 	);
	$table['#rows'] = $rows;

	$render_table  = '';
	$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-afl-compensation-paln'));
	$render_table .= afl_render_table($table);

	$render_table .= afl_form_close();

	echo $render_table;

	}
	
}

function afl_get_max_withrawal_amount($commission ,$withdrawal_max_value, $withdrawal_min, $balance){
  $chrg = explode('%', $commission); 
  $max_amount = explode('%', $withdrawal_max_value);
  $balance = afl_format_payment_amount($balance, FALSE); 
  if (strpos($withdrawal_max_value, '%')) {
     /*// $net_amount = ((($balance * $max_amount[0]) / 100) * 100) / (100 + $chrg[0]);
     $net_amount = afl_commission($withdrawal_max_value, $balance);*/
  	$net_amount = -100;

  } 
  else {
  	$bal = $balance-$chrg[0];
    if ($bal >= $withdrawal_max_value) {
      $net_amount = $withdrawal_max_value;
    } else {
      $net_amount = $bal;
    }
    $net_amount = afl_commerce_amount($net_amount);
  }
  if ($bal <= $withdrawal_min || ((!strpos($commission, '%')) && $bal <= $chrg[0])) {
    $net_amount = 0;
  }
  if ($net_amount < 0) {
    $net_amount = 0;
  }
  return $net_amount;
}




