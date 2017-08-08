<?php 
/*
 * -------------------------------------------------------------------
 * Compensation plan menu callbacks for admin menus
 * -------------------------------------------------------------------
*/
function afl_admin_compensation_plan_configuration() {
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
		afl_admin_compensation_plan_config_tabs();
	afl_content_wrapper_end();
	
}
/*
 * ------------------------------------------------------------------
 * Tabs
 * ------------------------------------------------------------------
*/
	function afl_admin_compensation_plan_config_tabs () {
		$matrix_active = $basic_active = '';
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'basic';  
		switch ($active_tab) {
			case 'basic':
					$basic_active  = 'active';
			break;
			case 'matrix-compensation':
					$matrix_active  = 'active';
			break;
		}
		  //here render the tabs
		  echo '<ul class="tabs--primary nav nav-tabs">';
		  
		  echo '<li class="'.$basic_active.'">
		            	<a href="?page=affiliate-eps-compensation-plan-configurations&tab=basic" >Basic configuration</a>  
		          </li>';

		  echo '<li class="'.$matrix_active.'">
		            	<a href="?page=affiliate-eps-compensation-plan-configurations&tab=matrix-compensation" >Matrix Compensation</a>  
		          </li>';

		  echo '</ul>';

		  switch ($active_tab) {
		  	case 'basic':
		  		afl_admin_compensation_plan_config_();
		  	break;
		  	case 'matrix-compensation':
		  		afl_admin_matrix_compensation_config_();
		  	break;
		  }

	}
/*
 * -------------------------------------------------------------------
 * Admin compensation plan configuration form
 * -------------------------------------------------------------------
*/
function afl_admin_compensation_plan_config_(){
	 if ( isset($_POST['submit']) ) {
	 	$validation = afl_admin_compensation_plan_form_validation($_POST);
	 	if (!empty($validation)) {
	 		afl_admin_compensation_plan_form_submit($_POST);
	 	}
	 }


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

	$table['#header'] 		= array('Plan Configuration');

	/*--------------------- Rows ---------------------*/

	$rows[0]['label_2'] = array(
		'#type' => 'label',
		'#title'=> 'Width of matrix',
 	);
	$rows[0]['matrix_plan_width'] = array(
		'#type' 					=> 'select',
		'#attributes'			=>array('form-select','select'),
		'#options' 				=> afl_get_levels(),
		'#default_value' 	=> afl_variable_get('matrix_plan_width',3),
 	);
 	$rows[1]['label_3'] = array(
		'#type' => 'label',
		'#title'=> 'Height of matrix',
 	);
	$rows[1]['matrix_plan_height'] = array(
		'#type' 					=> 'select',
		
		'#options' 				=> afl_get_levels(),
		'#default_value' 	=> afl_variable_get('matrix_plan_height',3),
 	);
	$rows[2]['label_1'] = array(
		'#type' => 'label',
		'#title'=> 'Total number of ranks',
	);
	$rows[2]['number_of_ranks'] = array(
		'#type' 					=> 'select',
		'#attributes'			=>array('form-select','select'),
		'#options' 				=> afl_get_levels(),
		'#default_value' 	=> afl_variable_get('number_of_ranks',1),
 	);

	/*---- Holding tank exoiry days*/
 	$rows[3]['label_1'] = array(
		'#type' => 'label',
		'#title'=> 'Holding tank holding days',
	);
	$rows[3]['holding_tank_holding_days'] = array(
		'#type' 					=> 'text',
		'#default_value' 	=> afl_variable_get('holding_tank_holding_days',7),
 	);


 	$table['#rows'] = $rows;


	$render_table  = '';
	$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-afl-compensation-paln'));
	$render_table .= afl_render_table($table);
	$render_table .= afl_input_button('submit', 'Save configuration', '',array('class'=>'btn btn-default btn-primary'));

	$render_table .= afl_form_close();

	echo $render_table;
}

/* 
 * ----------------------------------------------------------------------------
 * Form Validation
 * ----------------------------------------------------------------------------
*/
function afl_admin_compensation_plan_form_validation($POST){
	global $reg_errors;
	$reg_errors = new WP_Error;
	$flag 			= 1;
	// $values = $POST['var'];
	if ( is_wp_error( $reg_errors ) ) {
    foreach ( $reg_errors->get_error_messages() as $error ) {
				$flag = 0;
    		echo wp_set_message($error, 'danger');
    }
	}
	return $flag;
}

/* 
 * ----------------------------------------------------------------------------
 * Form Submit action
 * ----------------------------------------------------------------------------
*/
function afl_admin_compensation_plan_form_submit($POST){
	foreach ($POST as $key => $value) {
				afl_variable_set($key, maybe_serialize($value));
			}
	echo wp_set_message(__('Configuration has been saved successfully.'), 'success');

	}


/*
 * ----------------------------------------------------------------------------
 * Matrix compensation plan cofig
 * ----------------------------------------------------------------------------
*/
 function afl_admin_matrix_compensation_config_ () {
 	if ( isset($_POST['submit'])) {
 		$validation = afl_admin_matrix_compensation_config_validation($_POST);

 		if (empty($validation)) {
 			afl_admin_matrix_compensation_config_submit($_POST);
 		}
 	}
 	$form = array();
	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	$form['fieldset_1'] = array(
 		'#type' => 'fieldset',
 		'#title' =>'Basic configuration',
 	);

 	$form['fieldset_1']['matrix_compensation_period_maximum'] = array(
 		'#title' 	=> 'Compensation Period',
 		'#type'  	=> 'text',
 		'#name'		=> 'matrix-compensation-period-maximum',
 		'#required' => TRUE,
 		'#default_value'=> afl_variable_get('matrix_compensation_period_maximum',''),

 	);

 	$form['fieldset_1']['matrix_compensation_given_day'] = array(
 		'#title' 	=> 'Compensation Given Day',
 		'#type'  	=> 'text',
 		'#name'		=> 'matrix-compensation-given-day',
 		'#required' => TRUE,
 		'#default_value'=> afl_variable_get('matrix_compensation_given_day',''),

 	);

 	$form['fieldset_2'] = array(
 		'#type' => 'fieldset',
 		'#title' =>'Each Month Compensation',
 	);

 	$form['fieldset_2']['month_1_matrix_compensation'] = array(
 		'#title' 	=> 'First Month Compensation',
 		'#type'  	=> 'text',
 		'#default_value'=> afl_variable_get('month_1_matrix_compensation',''),
 		'#required' => TRUE,

  );
  $form['fieldset_2']['month_2_matrix_compensation'] = array(
 		'#title' 	=> 'Second Month Compensation',
 		'#type'  	=> 'text',
 		'#default_value'=> afl_variable_get('month_2_matrix_compensation',''),
 		'#required' => TRUE,

  );
  $form['fieldset_2']['month_3_matrix_compensation'] = array(
 		'#title' 	=> 'Third Month Compensation',
 		'#type'  	=> 'text',
 		'#default_value'=> afl_variable_get('month_3_matrix_compensation',''),
 		'#required' => TRUE,

  );

 	$form['submit'] = array(
 		'#type' => 'submit',
 		'#value' => 'Save configuration'
 	);
 	echo afl_render_form($form);
 
 }

 /*
 	* ----------------------------------------------------------------------------
	* Validating the input fields
 	* ----------------------------------------------------------------------------
 */
 	function afl_admin_matrix_compensation_config_validation ($form_state = array()) {
 		$rules = array();
		//create rules
		$rules[] = array(
	 		'value'=> $form_state['matrix_compensation_period_maximum'],
	 		'name' =>'Matrix compensation period',
	 		'field' =>'matrix_compensation_period_maximum',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['month_1_matrix_compensation'],
	 		'name' =>'First month matrix compensation',
	 		'field' =>'month_1_matrix_compensation',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['month_2_matrix_compensation'],
	 		'name' =>'Second month matrix compensation',
	 		'field' =>'month_2_matrix_compensation',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);

	 	$rules[] = array(
	 		'value'=> $form_state['month_3_matrix_compensation'],
	 		'name' =>'Third month matrix compensation',
	 		'field' =>'month_3_matrix_compensation',
	 		'rules' => array(
	 			'rule_required',
	 			'rule_is_numeric'
	 		)
	 	);

	 	$resp  = set_form_validation_rule($rules);
	 	return $resp;
 	}

/*
 * ----------------------------------------------------------------------------
 * Submit hook
 * ----------------------------------------------------------------------------
*/
 function afl_admin_matrix_compensation_config_submit ($form_state = array()) {
 	unset($form_state['submit']);
 	foreach ($form_state as $key => $value) {
 		afl_variable_set($key,$form_state[$key]);
 	}

 	echo wp_set_message('Configuration has been saved successfully', 'success');
 }