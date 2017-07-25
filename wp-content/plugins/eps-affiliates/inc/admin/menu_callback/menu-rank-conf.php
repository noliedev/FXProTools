<?php 
/*
 * -------------------------------------------------------------------
 * Compensation plan menu callbacks for admin menus
 * -------------------------------------------------------------------
*/
function afl_admin_rank_configuration() {  
	echo afl_eps_page_header();

	if ( isset($_POST['submit']) ) {  
	 $validation = afl_admin_rank_configuration_form_validation($_POST);
	 	if (!empty($validation)) {
	 		afl_admin_rank_configuration_form_submit($_POST);
	 	}
	}
	$max_rank = afl_variable_get('number_of_ranks');
	// pr($max_rank,1);
	$html 	=''; 
	$html  .= ' <ul class="nav nav-pills">';
  for ($i=1; $i <=$max_rank ; $i++) { 
  	$html .=  '<li><a data-toggle="tab" href="#rank_'.$i.'">Rank'.$i.'</a></li>';
  } 
  $html 	.='</ul>';
  $html 	.='<div class="tab-content">';
  for ($i=1; $i <=$max_rank ; $i++) { 
 		 $html 	.='<div role="tabpanel" class="tab-pane"  id="rank_'.$i.'">';
 		 $html .= rank_conf_form($i);
 		 $html		.= '</div>';
	}
 	$html .=	'</div>';
   // echo($html);
   // echo $html1;

	$table 								= array();
	$table['#name'] 			= '';
	$table['#title'] 			= '';
	$table['#prefix'] 		= '';
	$table['#suffix'] 		= '';
	$table['#attributes'] = array(
					'class'=> array(
										'table',
										'table-responsive',
										// 'table-bordered'
									)
					);
	$table['#header'] 		= array('Rank configuration');
	$rows[0]['label_0'] = array(
		'#type' => 'label',
		'#title'=> 'Rank expiry',
	);
	$rows[0]['rank_expiry'] = array(
		'#type' 				=> 'select',
		'#options'			=>afl_get_periods(),	
		'#default_value' 	=> afl_variable_get('rank_expiry'),
 	);
 	$rows[1]['label_1'] = array(
		'#type' => 'label',
		'#title'=> 'Rank Updation',
	);
	$rows[1]['rank_updation_period'] = array(
		'#type' 				=> 'select',
		'#options'			=>afl_get_periods(),	
		'#default_value' 	=> afl_variable_get('rank_updation_period','rank1'),
 	);
	$rows[7]['label_7'] = array(
		'#type' => 'label',
		'#title'=> $html,
	);
	$rows[7]['rank_'] = array(
		'#type' => 'label',
		'#title'=> '',
	);
 	$table['#rows'] = $rows;
	$render_table  = '';
	$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-rank-config'));
	$render_table .= afl_render_table($table);
	$render_table .= afl_input_button('submit', 'Save configuration', '',array('class'=>'btn btn-default btn-primary'));
	$render_table .= afl_form_close();
	echo $render_table;

 } 

/*
 * -------------------------------------------------------------------
 * Compensation plan menu callbacks for each rank tab menu
 * -------------------------------------------------------------------
*/
function  rank_conf_form($i){

	$table 								= array();
	$table['#name'] 			= '';
	$table['#title'] 			= '';
	$table['#prefix'] 		= '';
	$table['#suffix'] 		= '';
	$table['#attributes'] = array(
					'class'=> array(
										'table',
										'table-responsive',
										'table-bordered',
									)
					);

	$table['#header'] 		= array('Rank configuration'.$i);
	$rows[1]['label_'.$i] = array(
		'#type' => 'label',
		'#title'=> 'Rank '. $i .' name',
	);
	$rows[1]['rank_'.$i.'_name'] = array(
		'#type' 					=> 'text',
		'#default_value' 	=> isset($_POST['rank_'.$i.'_name']) ? $_POST['rank_'.$i.'_name'] : afl_variable_get('rank_'.$i.'_name','Rank '.$i),
 	);

	$rows[2]['label_'.$i] = array(
			'#type' => 'label',
			'#title'=> 'Total Personal Volume Rank',
	);
	$rows[2]['personal_volume_rank_'.$i] = array(
			'#type' 					=> 'text',
			'#default_value' 	=>isset($_POST['personal_volume_rank_'.$i]) ? $_POST['personal_volume_rank_'.$i] :  afl_variable_get('personal_volume_rank_'.$i,-100),
	);

	$rows[3]['label_'.$i] = array(
			'#type' => 'label',
			'#title'=> 'Total Group Volume Rank',
		);
	$rows[3]['group_volume_rank_'.$i] = array(
			'#type' 					=> 'text',
			'#default_value' 	=> isset($_POST['group_volume_rank_'.$i]) ? $_POST['group_volume_rank_'.$i]:   afl_variable_get('group_volume_rank_'.$i,-100),
	);

	$rows[4]['label_'.$i] = array(
			'#type' => 'label',
			'#title'=> 'No of distributors Rank',
		);
	$rows[4]['no_of_distributors_rank_'.$i] = array(
			'#type' 					=> 'text',
			'#default_value' 	=> isset($_POST['no_of_distributors_rank_'.$i]) ? $_POST['no_of_distributors_rank_'.$i] :   afl_variable_get('no_of_distributors_rank_'.$i,-100),
			'#required' =>TRUE,
	);
	$rows[5]['label_'.$i] = array(
			'#type' => 'label',
			'#title'=> 'Incentives',
		);
	$rows[5]['incentives_rank_'.$i] = array(
			'#type' 					=> 'text',
			'#default_value' 	=> isset($_POST['incentives_rank_'.$i]) ? $_POST['incentives_rank_'.$i] :    afl_variable_get('incentives_rank_'.$i,-100),
	);

	$rows[6]['label_'.$i] = array(
			'#type' => 'label',
			'#title'=> 'Colour',
		);
	$rows[6]['colour_rank_'.$i] = array(
			'#type' 					=> 'text',
			'#default_value' 	=> isset($_POST['colour_rank_'.$i] ) ? $_POST['colour_rank_'.$i]  :          afl_variable_get('colour_rank_'.$i,-100),
	);

	$rows[7]['label_'.$i] = array(
			'#type' => 'label',
			'#title'=> 'Rank Qualification',
	);

	$rows[8]['label'.$i] = array(
		'#type' => 'label',
		'#title'=> 'Downline Rank',
	);
	$rows[8]['label_'.$i] = array(
		'#type' => 'label',
		'#title'=> 'Number Of Rank Holders',
	);
	$rows[8]['label__'.$i] = array(
		'#type' => 'label',
		'#title'=> 'Select Option',
	);
	
	$rows[9]['rank_quali_downline_rank_name_rank_'.$i] = array(
		'#type' 				=> 'select',
		'#options'			=>afl_get_rank_names($i),	
		'#default_value' 	=> isset($_POST['rank_quali_downline_rank_name_rank_'.$i]) ? $_POST['rank_quali_downline_rank_name_rank_'.$i] :    afl_variable_get('rank_quali_downline_rank_name_rank_'.$i,-100),
 	);
 	$rows[9]['rank_quali_number_of_rank_holders_rank_'.$i] = array(
		'#type' 				=> 'text',
		'#default_value' 	=> isset($_POST['rank_quali_number_of_rank_holders_rank_'.$i]) ? $_POST['rank_quali_number_of_rank_holders_rank_'.$i] :    afl_variable_get('rank_quali_number_of_rank_holders_rank_'.$i,-100),
 	);
 	$rows[9]['rank_quali_conditon_rank_'.$i] = array(
		'#type' 				=> 'select',
		'#options'			=> array('combined'=>'Combined', 'each_leg'=>'Seperate Legs'),
		'#default_value' 	=> isset($_POST['rank_quali_conditon_rank_'.$i]) ? $_POST['rank_quali_conditon_rank_'.$i] :    afl_variable_get('rank_quali_conditon_rank_'.$i,-100),
 	);

	
	$table['#rows'] = $rows;
	$render_table  = '';
	// $render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-afl-advcd-config'));
	$render_table .= afl_render_table($table);
	  // $render_table .= afl_input_button('submit', 'Save configuration', '',array('class'=>'btn btn-default btn-primary'));

	$render_table .= afl_form_close();
return $render_table;
	

}
function afl_admin_rank_configuration_form_validation($post){
	global $reg_errors;
	$reg_errors = new WP_Error;
	$flag 			= 1;
	$posative_int = array(
		'personal_volume_rank_',
		 'group_volume_rank_',
		 'no_of_distributors_rank_',
		 'rank_quali_number_of_rank_holders_rank_',
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

function afl_admin_rank_configuration_form_submit($post){
	foreach ($post as $key => $value) {
				afl_variable_set($key, maybe_serialize($value));
			}
	echo wp_set_message(__('Configuration has been saved successfully.'), 'success');
}