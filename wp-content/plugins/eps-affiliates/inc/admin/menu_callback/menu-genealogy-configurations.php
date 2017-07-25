
<?php 

function afl_system_genealogy_configurations () {
	echo afl_eps_page_header();
	

	$post = array();
	if (isset($_POST['submit']) ){
		$rules = array();
		//create rules
		$rules[] = array(
	 		'value'=>$_POST['root_user'],
	 		'name' =>'root user',
	 		'rules' => array(
	 			'rule_required',
	 		)
	 	);
	 	//validating fields with rules
	 	$resp  = set_form_validation_rule($rules);
	 	if (!empty($resp)) {
	 		echo $resp;
	 		$post = $_POST;
	 	} else {
	 		//set the root user and reset genealogy
	 		afl_genealogy_configuration_form_submit($_POST);
	 		echo wp_set_message('Configuration has been saved successfully', 'success');
	 	}
	}

	afl_genealogy_configuration_form($post);
}

/*
 * --------------------------------------------------------
 * Genealogy configurations form callback
 * --------------------------------------------------------
*/
function afl_genealogy_configuration_form ($post) {
	afl_content_wrapper_begin();
	$form = array();
	$form['#action'] = $_SERVER['REQUEST_URI'];
 	$form['#method'] = 'post';
 	$form['#prefix'] ='<div class="form-group row">';
 	$form['#suffix'] ='</div>';

 	$form['root_user'] = array(
 		'#title' 	=> 'Root user',
 		'#type'  	=> 'auto_complete',
 		'#name'		=> 'root-user',
 		'#auto_complete_path' => 'users_auto_complete',
 		'#default_value' => isset($post['root_user']) ? $post['root_user'] : (!empty(afl_variable_get('root_user')) ? afl_variable_get('root_user') : '') ,
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);

 	$form['clear_data'] = array(
 		'#title' 	=> 'Clear test data',
 		'#type'  	=> 'checkbox',
 		'#name'		=> 'clear-test-data',
 		'#default_value' => isset($post['clear_data']) ? $post['clear_data'] : '',
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);

 	$form['remove_user'] = array(
 		'#title' 	=> 'Remove system user',
 		'#type'  	=> 'checkbox',
 		'#name'		=> 'remove_system_user',
 		'#default_value' => isset($post['remove_user']) ? $post['remove_user'] : '',
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
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
 		'#prefix'=>'<div class="form-group row">',
 		'#suffix' =>'</div>'
 	);
 	echo afl_render_form($form);

	afl_content_wrapper_end();
}
/*
 * --------------------------------------------------------
 * Genealogy configurations form submit callback
 * --------------------------------------------------------
*/
function afl_genealogy_configuration_form_submit ($form_state){
	if (!empty($form_state['root_user']) ){
		afl_variable_set('root_user', $form_state['root_user']);
	}

	if (isset($form_state['clear_data']) ){
		afl_system_reset();
	}
}
/*
 * --------------------------------------------------------
 * Reset Genealogy
 * --------------------------------------------------------
*/
function afl_system_reset () {
	//change all the users has afl_member role
	 $args = array(
      'role' => 'afl_member',
    );
   $users = get_users($args);
   //remove all the role
   foreach ($users as $key => $value) {
		$theUser = new WP_User($value->ID);
		$theUser->remove_role( 'afl_member' );
   }

}
