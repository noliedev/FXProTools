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
		  echo '</ul>';

		  switch ($active_tab) {
		  	case 'system_variables':
		  		afl_system_variables_form();
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

	 	$form['member_status'] = array(
	 		'#type' 					=> 'text-area',
	 		'#title' 					=> 'Member status',
	 		'#default_value' 	=> afl_variable_get('member_status', ''),
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