<?php
 function afl_admin_advanced_queue_conf () {
 	do_action('eps_affiliate_page_header');
 	do_action('afl_content_wrapper_begin');
 		afl_admin_advanced_queue_conf_form();
 	do_action('afl_content_wrapper_end');
 }

function afl_admin_advanced_queue_conf_form () {
	$post = array();
	if (!empty( $_POST['submit'] )) {
		unset($_POST['submit']);
		if (afl_admin_advanced_queue_conf_form_validation()) {
			afl_admin_advanced_queue_conf_form_submit($_POST);
		}
	 $post = $_POST;
	}

	$form = array();
 	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	$form['queue_max_re_process'] = array(
 		'#title' => 'Failed Queue re-processing times',
 		'#type' => 'text',
 		'#name' => 're process queue',
 		'#attributes' => array(
 			'class' => array(
 			)
 		),
 		'#default_value' => isset($post['queue_max_re_process']) ? $post['queue_max_re_process'] : (afl_variable_get('queue_max_re_process')),
 		
 	);
 	
 	$form['submit'] = array(
 		'#title' => 'Submit',
 		'#type' => 'submit',
 		'#value' => 'Submit',
 		'#attributes' => array(
 			'class' => array(
 				'btn','btn-primary'
 			)
 		),
 	);

 	echo afl_render_form($form);

}

function afl_admin_advanced_queue_conf_form_validation () {
	return true;
}

function afl_admin_advanced_queue_conf_form_submit ($form_state = array()) {
	foreach ($form_state as $key => $value) {
		afl_variable_set($key,$value);
	}
	wp_set_message('Configuration has been saved successfully','success');
}