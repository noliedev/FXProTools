<?php 
function afl_admin_variable_configurations (){
	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
		afl_admin_config_variable_form();
	echo afl_content_wrapper_end();
}

/*
 * ------------------------------------------------------------
 * Variable config form
 * ------------------------------------------------------------
*/
 function afl_admin_config_variable_form () {
 	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'system_variables';  

		  //here render the tabs
		  echo '<ul class="tabs--primary nav nav-tabs">';
		  echo '<li class="'.(($active_tab == 'system_variables') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-variable-configurations&tab=system_variables" >System Variables</a>  
		          </li>';
      echo '<li class="'.(($active_tab == 'payment_methods') ? 'active' : '').'">
        					<a href="?page=affiliate-eps-variable-configurations&tab=payment_methods" >Payment Methods</a>  
      			</li>';

      echo '<li class="'.(($active_tab == 'widget_var') ? 'active' : '').'">
        					<a href="?page=affiliate-eps-variable-configurations&tab=widget_var" >Widgets Settings</a>  
      			</li>';

		  echo '</ul>';

		  switch ($active_tab) {
		  	case 'system_variables':
		  		afl_system_variables_form();
	  		break;
	  		case 'payment_methods':
	  			afl_payment_methods_form();
	  		break;
	  		case 'widget_var':
	  			afl_widget_settings_form();
	  		break;
		  }
 }
/*
 * ------------------------------------------------------------
 * System variables form
 * ------------------------------------------------------------
*/
 function afl_system_variables_form () {
 		if (isset($_POST['submit'])) {
 			$variables = $_POST;
 			unset($variables['submit']);
 			afl_system_variables_form_submit($variables);
 		}

 		$form = array();
 		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	 	//payment source
	 	$form['afl_payment_sources'] = array(
	 		'#type' 					=> 'text-area',
	 		'#title' 					=> 'Payment Sources',
	 		'#default_value' 	=> afl_variable_get('afl_payment_sources', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>',

	 	);
	 	//Member status
	 	$form['member_status'] = array(
	 		'#type' 					=> 'text-area',
	 		'#title' 					=> 'Member status',
	 		'#default_value' 	=> afl_variable_get('member_status', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);

	 	//compensation periods
	 	$form['periods'] = array(
	 		'#type' 					=> 'text-area',
	 		'#title' 					=> 'Periods',
	 		'#default_value' 	=> afl_variable_get('periods', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	//credit status
	 	$form['afl_var_credit_status'] = array(
	 		'#type' 					=> 'text-area',
	 		'#title' 					=> 'Credit status',
	 		'#default_value' 	=> afl_variable_get('afl_var_credit_status', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Save configuration'
	 	);
	 	echo afl_render_form($form);
 }

function afl_system_variables_form_submit ($form_state) {
	foreach ($form_state as $key => $value) {
		afl_variable_set($key, maybe_serialize($value));
	}
	echo wp_set_message('Configuration has been saved successfully', 'success');
}
/*
 * ------------------------------------------------------------
 * Payment methods  variable form
 * ------------------------------------------------------------
*/
function afl_payment_methods_form(){
	if (isset($_POST['submit'])) {
 			$variables = $_POST;
 			unset($variables['submit']);
 			if (afl_payment_methods_form_validation($variables)) {
 				afl_payment_methods_form_submit($variables);
 			}
 		}

 		$form = array();
 		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	 	$form['payment_methods'] = array(
	 		'#type' 					=> 'text-area',
	 		'#required'				=> TRUE,
	 		'#title' 					=> 'Payment Methods',
	 		'#description' 		=> 'Format : &lt;value&gt;|&lt;name&gt;  Example : 1|Highest',
	 		'#default_value' 	=> afl_variable_get('payment_methods', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);

	 	$form['payout_methods'] = array(
	 		'#type' 					=> 'text-area',
	 		'#title' 					=> 'Payout Methods',
	 		'#required'				=> TRUE,
	 		'#description' 		=> 'Format : &lt;value&gt;|&lt;name&gt;  Example : 1|Highest',
	 		'#default_value' 	=> afl_variable_get('payout_methods', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['payout_status'] = array(
	 		'#type' 					=> 'text-area',
	 		'#required'				=> TRUE,
	 		'#title' 					=> 'Payout/Request Status',
	 		'#description' 		=> 'Format : &lt;value&gt;|&lt;name&gt;  Example : 1|Highest',
	 		'#default_value' 	=> afl_variable_get('payout_status', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['paid_status'] = array(
	 		'#type' 					=> 'text-area',
	 		'#title' 					=> 'Paid Status',
	 		'#required'				=> TRUE,
	 		'#description' 		=> 'Format : &lt;value&gt;|&lt;name&gt;  Example : 1|Highest',
	 		'#default_value' 	=> afl_variable_get('paid_status', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['payout_type'] = array(
	 		'#type' 					=> 'text-area',
	 		'#title' 					=> 'Payout Type',
	 		'#required'				=> TRUE,
	 		'#description' 		=> 'Format : &lt;value&gt;|&lt;name&gt;  Example : 1|Highest',
	 		'#default_value' 	=> afl_variable_get('payout_type', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['payout_categories'] = array(
	 		'#type' 					=> 'text-area',
	 		'#title' 					=> 'Payout Categories',
	 		'#required'				=> TRUE,
	 		'#description' 		=> 'Format : &lt;value&gt;|&lt;name&gt;  Example : 1|Highest',
	 		'#default_value' 	=> afl_variable_get('payout_categories', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>'
	 	);
	 	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Save configuration'
	 	);
	 	echo afl_render_form($form);
}
function afl_payment_methods_form_validation ($form_state = array()) {
	$rules = array();

	foreach ($form_state as $key => $value) {
		$rules[] = array(
		 	'value'=> $value,
	 		'name' => $key,
	 		'field'=> $key,
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	}

	return set_form_validation_rule($rules);
}

function afl_payment_methods_form_submit($form_state){
foreach ($form_state as $key => $value) {
		afl_variable_set($key, maybe_serialize($value));
	}
	echo wp_set_message('Configuration has been saved successfully', 'success');
}


/*
 * ---------------------------------------------------------------------
 * Widgets variables
 * ---------------------------------------------------------------------
*/
 function afl_widget_settings_form (){
 		if (isset($_POST['submit'])) {
 			$variables = $_POST;
 			unset($variables['submit']);
 			$validation = afl_widget_settings_form_validation($variables);
 			if ( $validation ) {
 				afl_widget_settings_form_submit($variables);
 			}
 		}

 		$form = array();
 		$form['#method'] = 'post';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	 	$form['afl_widgets_ewallet_visible_valu'] = array(
	 		'#type' 					=> 'select',
	 		'#options' 				=> array(
	 			'd' => 'Today',
	 			'm' =>'This Month',
	 			'y' => 'This Year',
	 			't'	=> 'Overall'
	 		),
	 		'#title' 					=> 'E-Wallet visible type (Grid Panel) ',
	 		'#default_value' 	=> afl_variable_get('afl_widgets_ewallet_visible_valu', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>',
	 		'#required' 			=> TRUE
	 	);

	 	$form['afl_widgets_total_debit_visible_valu'] = array(
	 		'#type' 					=> 'select',
	 		'#options' 				=> array(
	 			'd' => 'Today',
	 			'm' =>'This Month',
	 			'y' => 'This Year',
	 			't'	=> 'Overall'
	 		),
	 		'#title' 					=> 'Expense visible type (Grid Panel) ',
	 		'#default_value' 	=> afl_variable_get('afl_widgets_total_debit_visible_valu', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>',
	 		'#required' 			=> TRUE

	 	);

	 	$form['afl_widgets_total_credit_visible_valu'] = array(
	 		'#type' 					=> 'select',
	 		'#options' 				=> array(
	 			'd' => 'Today',
	 			'm' =>'This Month',
	 			'y' => 'This Year',
	 			't'	=> 'Overall'
	 		),
	 		'#title' 					=> 'Income visible type (Grid Panel) ',
	 		'#default_value' 	=> afl_variable_get('afl_widgets_total_credit_visible_valu', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>',
	 		'#required' 			=> TRUE

	 	);

	 	$form['afl_widgets_downline_members_visible_valu'] = array(
	 		'#type' 					=> 'select',
	 		'#options' 				=> array(
	 			'd' => 'Today',
	 			'm' =>'This Month',
	 			'y' => 'This Year',
	 			't'	=> 'Overall'
	 		),
	 		'#title' 					=> 'Downline members visible type (Grid Panel) ',
	 		'#default_value' 	=> afl_variable_get('afl_widgets_downline_members_visible_valu', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>',
	 		'#required' 			=> TRUE

	 	);

	 	$form['afl_widgets_transaction_c_visible_valu'] = array(
	 		'#type' 					=> 'select',
	 		'#options' 				=> array(
	 			'd' => 'Daily',
	 			'm' =>'Monthly',
	 			'y' => 'Yearly',
	 		),
	 		'#title' 					=> 'Transaction visible type (Chart)  ',
	 		'#default_value' 	=> afl_variable_get('afl_widgets_transaction_c_visible_valu', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>',
	 		'#required' 			=> TRUE

	 	);
	 	$options = array();
	 	for ( $i = 5;$i<=50; $i=$i+5) {
	 		$options[$i] = $i;
	 	}
	 	$form['afl_widgets_transaction_c_max_list'] = array(
	 		'#type' 					=> 'select',
	 		'#options' 				=> $options,
	 		'#title' 					=> 'Maximum number of transaction list',
	 		'#default_value' 	=> afl_variable_get('afl_widgets_transaction_c_max_list', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>',
	 		'#required' 			=> TRUE

	 	);

	 	$form['afl_widgets_downline_members_c_visible_valu'] = array(
	 		'#type' 					=> 'select',
	 		'#options' 				=> array(
	 			'd' => 'Daily',
	 			'm' =>'Monthly',
	 			'y' => 'Yearly',
	 		),
	 		'#title' 					=> 'Downline members visible type (Chart)',
	 		'#default_value' 	=> afl_variable_get('afl_widgets_downline_members_c_visible_valu', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>',
	 		'#required' 			=> TRUE

	 	);

	 	$form['afl_widgets_downline_members_c_max_list'] = array(
	 		'#type' 					=> 'select',
	 		'#options' 				=> $options,
	 		'#title' 					=> 'Maximum number of downline members list ',
	 		'#default_value' 	=> afl_variable_get('afl_widgets_downline_members_c_max_list', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>',
	 		'#required' 			=> TRUE

	 	);

	 	$form['recent_transaction_chart_colors'] = array(
	 		'#type' 					=> 'text',
	 		'#title' 					=> 'Recent transaction chart element color',
	 		'#default_value' 	=> afl_variable_get('recent_transaction_chart_colors', ''),
	 		'#prefix'					=> '<div class="form-group row">',
	 		'#suffix' 				=> '</div>',
	 		'#required' 			=> TRUE

	 	);

	 	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value'=>' Save configuration'
	 	);
	 	echo afl_render_form($form);

 }

 function afl_widget_settings_form_validation ($form_state = array()) {
 		$rules = array();


 		foreach ($form_state as $key => $value) {
	 		$rules[] = array(
		 		'value'	=> $value,
		 		'name' 	=> str_replace('_', ' ', $key),
		 		'field' => $key,
		 		'rules' => array(
		 			'rule_required',
		 		)
		 	);
 		}

 		$resp  = set_form_validation_rule($rules);
	 	return $resp;
 }

function afl_widget_settings_form_submit ($form_state = array()) {
	foreach ($form_state as $key => $value) {
		afl_variable_set($key, maybe_serialize($value));
	}
	echo wp_set_message('Configuration has been saved successfully', 'success');
}