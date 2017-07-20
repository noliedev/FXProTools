<?php 
/*
 * -------------------------------------------------------------------
 * Compensation plan menu callbacks for admin menus
 * -------------------------------------------------------------------
*/
function afl_admin_compensation_plan_configuration() {
	afl_admin_compensation_plan_config_();
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
	$values = $POST['var'];
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

