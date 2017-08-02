<?php 
function afl_user_payment_methods(){
	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
		afl_user_payment_method_form();
	echo afl_content_wrapper_end();
}

function afl_user_payment_method_form(){
		global $wpdb;
		$uid 					 = get_current_user_id();
		$table = $wpdb->prefix. 'afl_user_payment_methods';

		$payment_methods = list_extract_allowed_values(afl_variable_get('payment_methods'),'list_text',FALSE);
		$default_method = $wpdb->get_row("SELECT * FROM $table WHERE (uid = '$uid' AND status= '". 1 ."')");
		
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
		$i = 0;
		foreach ($payment_methods as $key => $value) { 
			$rows[$i]['label_'.$key] = array(
				'#type' => 'label',
				'#title'=> $value,
			);
			$rows[$i]['ststus_'.$key] = array(
				'#type' 		=> 'checkbox',
				'#attributes' => array('switch' => 'switch', 'class'=> array('single-switch-checkbox') ),


			);
			$rows[$i]['conf_'.$key] = array(
			'#type' => 'markup',
			'#markup' => '<a href=""><span class="btn btn-rounded btn-sm btn-icon btn-default"><i class="fa fa-cog"></i></span></a>',
		);
			$i++;

		}


		$table['#rows'] = $rows;
		$table['#header'] 		= array('Payment Methods','Status','Configuration');
		$render_table  = '';

		$render_table .= afl_form_open($_SERVER['REQUEST_URI'],'POST', array('id'=>'form-afl-ewallet-withdraw-fund'));
		$render_table .= afl_render_table($table);
			$render_table .= afl_input_button('submit', 'Save configuration', '',array('class'=>'btn btn-default btn-primary'));
		$render_table .= afl_form_close();

		// pr($render_table,1);
		echo $render_table;


}