<?php 
/*
 * ------------------------------------------------
 * Admin payout configuration menu call back
 * ------------------------------------------------
*/
function afl_admin_payout_configuration(){
		echo afl_eps_page_header();
	$post = array();
	if (isset($_POST['submit']) ){
			$post = $_POST;
			$validation = afl_payout_configuration_form_validation($_POST);
		if (!empty($validation)) {
	 		afl_payout_configuration_form_submit($_POST);
	 	}
	}

	afl_payout_configuration_form($post);
}
/*
 * ------------------------------------------------
 * Admin payout configuration form
 * ------------------------------------------------
*/
function afl_payout_configuration_form($post){

	$form = array();
	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';
	$color_hr = afl_variable_get('mlm_hr_color', '#7266ba');

 	$form['afl_system_payout_frequency'] = array(
 		'#title' 	=> 'Payout *',
 		'#type'  	=> 'select',
 		'#options' => afl_get_periods(),
 		'#default_value' => isset($post['afl_system_payout_frequency']) ? $post['afl_system_payout_frequency'] : (!empty(afl_variable_get('afl_system_payout_frequency')) ? afl_variable_get('afl_system_payout_frequency') : '') ,
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
 	$form['field'] = array(
 		'#type' => 'fieldset',
 		'#title'=>'Payout Configurations',
 	);
	$form['field']['payout_min_value'] = array(
		'#type' 				=> 'text',
		'#addon'				=>	'$',
		'#title'				=> 'Minimum payout amount *',
		'#default_value' 	=>  isset($post['payout_min_value']) ? $post['payout_min_value'] : (!empty(afl_variable_get('payout_min_value')) ? afl_variable_get('payout_min_value') : '-1') ,
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['field']['payout_charges_method_bank'] = array(
		'#type' 				=> 'text',
		'#title'				=> 'Payout charges - Bank Account *',
		'#default_value' 	=> isset($post['payout_charges_method_bank']) ? $post['payout_charges_method_bank'] : (!empty(afl_variable_get('payout_charges_method_bank')) ? afl_variable_get('payout_charges_method_bank') : '') ,
		'#prefix'=>'<div class="form-group row">',
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['field']['payout_conf'] = array(
   '#type' => 'markup',
   '#markup' => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
 );

	$form['field_1'] = array(
 		'#type' => 'fieldset',
 		'#title'=>'Withdrawal Settings',
 	);
	$form['field_1']['withdrawal_min_value'] = array(
		'#type' 				=> 'text',
		'#title'				=> 'Minimum withdrawal amount *',
		'#default_value' 	=> isset($post['withdrawal_min_value']) ? $post['withdrawal_min_value'] : (!empty(afl_variable_get('withdrawal_min_value')) ? afl_variable_get('withdrawal_min_value') : '') ,
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['field_1']['withdrawal_max_value'] = array(
		'#type' 				=> 'text',
		'#title'				=> 'Maximum withdrawal amount *',
		'#default_value' 	=> isset($post['withdrawal_max_value']) ? $post['withdrawal_max_value'] : (!empty(afl_variable_get('withdrawal_max_value')) ? afl_variable_get('withdrawal_max_value') : '') ,
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['field_1']['withdrawal_conf'] = array(
   '#type' => 'markup',
   '#markup' => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
 );

	$form['field_2'] = array(
 		'#type' => 'fieldset',
 		'#title'=>'Withdrawal Settings',
 	);
	$form['field_2']['etransfer_min_value'] = array(
		'#type' 				=> 'text',
		'#title'				=> ' Minimum amount to initiate e-wallet transfer *',
		'#default_value' 	=> isset($post['etransfer_min_value']) ? $post['etransfer_min_value'] : (!empty(afl_variable_get('etransfer_min_value')) ? afl_variable_get('etransfer_min_value') : '') ,
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['field_2']['etransfer_max_value'] = array(
		'#type' 				=> 'text',
		'#title'				=> 'Maximum amount can be transferred from e-wallet *',
		'#default_value' 	=>isset($post['etransfer_max_value']) ? $post['etransfer_max_value'] : (!empty(afl_variable_get('etransfer_max_value')) ? afl_variable_get('etransfer_max_value') : '') ,
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['field_2']['etransfer_charges'] = array(
		'#type' 				=> 'text',
		'#title'				=> 'E-wallet Transfer charges *',
		'#default_value' 	=> isset($post['etransfer_charges']) ? $post['etransfer_charges'] : (!empty(afl_variable_get('etransfer_charges')) ? afl_variable_get('etransfer_charges') : '') ,
		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
	);
	$form['field_2']['withdrawal_conf'] = array(
   '#type' => 'markup',
   '#markup' => '<hr style="border:2px solid '.$color_hr.'; color:'.$color_hr.'; margin:60px 0px 60px 0px">',
 );
	$form['submit'] = array(
 		'#title' => 'Submit',
 		'#type' => 'submit',
 		'#value' => 'Save Configuration',
 		'#attributes' => array(
 			'class' => array(
 				'btn','btn-primary'
 			)
 		),
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);

	echo afl_render_form($form);

}

/*
 * ------------------------------------------------
 * Admin payout configuration form validation
 * ------------------------------------------------
*/
function afl_payout_configuration_form_validation($post){
	global $reg_errors;
	$reg_errors = new WP_Error;
	$flag 			= 1;
	$posative_int = array(
		'payout_min_value',
		 'payout_charges_method_bank',
		 'withdrawal_min_value',
		 'withdrawal_max_value',
		 'etransfer_min_value',
		 'etransfer_max_value',
		 'etransfer_charges'
		);
	
	foreach ($post as $key => $value) {
		if (empty($value)) { 
			$msg = str_replace('_', ' ', $key);
	    $reg_errors->add($key, '"'.$msg.'" 	is required.');
		}	
		$posative = preg_replace('/[0-9]+/', '', $key);
		// pr($posative);
		if (in_array($posative, $posative_int)){  
  		if (!is_numeric($value) || $value < 0 ) {  
  			$msg = str_replace('_', ' ', $key);
	    		$reg_errors->add($key, '"'.$msg.'" 	is not correct value.');
			}
  	}
	}
	if ( is_wp_error( $reg_errors ) ) {
    foreach ( $reg_errors->get_error_messages() as $error ) {
				$flag = 0;
    		echo wp_set_message($error, 'danger');
    }
	}
	return $flag;
}

/*
 * ------------------------------------------------
 * Admin payout configuration form submit
 * ------------------------------------------------
*/
function afl_payout_configuration_form_submit($post){
	foreach ($post as $key => $value) {
				afl_variable_set($key, maybe_serialize($value));
			}
	echo wp_set_message(__('Configuration has been saved successfully.'), 'success');

		
}