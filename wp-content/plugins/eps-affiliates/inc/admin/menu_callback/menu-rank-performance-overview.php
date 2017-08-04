<?php

function afl_rank_performance_overview () {
	echo afl_eps_page_header();
	
	echo afl_content_wrapper_begin();
		afl_renk_performance_overview_template();
	echo afl_content_wrapper_end();
}

function afl_renk_performance_overview_template () {
	$uid = afl_current_uid();

	$table = array();
	$table['#name'] 			= '';
	$table['#title'] 			= '';
	$table['#prefix'] 		= '';
	$table['#suffix'] 		= '';
	$table['#attributes'] = array(
					'class' => array(
							'table',
							'table-bordered',
							'my-table-center',

						)
					);

	$table['#header'] = array(
		__('Rank Name','affiliates-eps'), 
		__('PV','affiliates-eps'), 
		__('GV','affiliates-eps'), 
		__('Distributors','affiliates-eps'),
		__('Qualifications','affiliates-eps')
	);
	$rows = array();
	
 	$max_rank = afl_variable_get('number_of_ranks');
 	for ($i = 1; $i <= $max_rank; $i++) {
 		
 		/* ------ Rank name --------------------------------------------------------------------*/
	 		$rows[$i]['label_1'] = array(
				'#type' => 'label',
				'#title'=> afl_variable_get('rank_'.$i.'_name', 'Rank '.$i),

			);
 		/* ------ Rank name --------------------------------------------------------------------*/
 		
 		
 		/* ------ User PV ----------------------------------------------------------------------*/
			$required = afl_variable_get('rank_'.$i.'_pv',0);
			$earned 	= _get_user_pv($uid); 

			$markup = '';
			$markup .= '<label class="label text-info m-l-xs">Required : '.$required.'</label><br>';
			$markup .= '<label class="label text-info m-l-xs">Earned : '.$earned.'</label><br>';
			if ($required <= $earned) {
				$condition = '<span class="text-center"><i class="text-center fa fa-thumbs-up text-success m-b-xs"></i></span>';
			} else {
				$condition = '<span class="text-center"><i class="text-center fa fa-thumbs-down text-danger m-b-xs"></i></span>';
			}

			$rows[$i]['markup_1'] = array(
				'#type' => 'markup',
				'#markup'=> $markup.$condition,
			);
 		
 		/* ------ User PV ----------------------------------------------------------------------*/


 		/* ------ User GV ----------------------------------------------------------------------*/
			$required = afl_variable_get('rank_'.$i.'_gv',0);
			$earned 	= _get_user_gv($uid); 

			$markup = '';
			$markup .= '<label class="label text-info m-l-xs">Required : '.$required.'</label><br>';
			$markup .= '<label class="label text-info m-l-xs">Earned : '.$earned.'</label><br>';
			if ($required <= $earned) {
				$condition = '<span class="text-center"><i class="text-center fa fa-thumbs-up text-success m-b-xs"></i></span>';
			} else {
				$condition = '<span class="text-center"><i class="text-center fa fa-thumbs-down text-danger m-b-xs"></i></span>';
			}

			$rows[$i]['markup_2'] = array(
				'#type' => 'markup',
				'#markup'=> $markup.$condition,
			);
 		/* ------ User GV ----------------------------------------------------------------------*/

 		/* ------ No.of distributors -----------------------------------------------------------*/
			$required = afl_variable_get('rank_'.$i.'_no_of_distributors',0);
			$earned 	= _get_user_distributor_count($uid); 

			$markup = '';
			$markup .= '<label class="label text-info m-l-xs">Required : '.$required.'</label><br>';
			$markup .= '<label class="label text-info m-l-xs">Earned : '.$earned.'</label><br>';
			if ($required <= $earned) {
				$condition = '<span class="text-center"><i class="text-center fa fa-thumbs-up text-success m-b-xs"></i></span>';
			} else {
				$condition = '<span class="text-center"><i class="text-center fa fa-thumbs-down text-danger m-b-xs"></i></span>';
			}

			$rows[$i]['markup_3'] = array(
				'#type' => 'markup',
				'#markup'=> $markup.$condition,
			);
 		/* ------ No.of distributors -----------------------------------------------------------*/

 		/* ------ Rank qualifications ----------------------------------------------------------*/
 		$markup = '';
 		$below_rank = $i - 1;
		  if ($below_rank > 0 ) {
		  	for ($j = 1; $j <= $below_rank ; $j++) { 
		  		$required = afl_variable_get('rank_'.$i.'_rank_'.$j.'_required_count',0);
		  		$in_each 	= afl_variable_get('rank_'.$i.'_rank_'.$j.'_required_each_leg',0);
		  		$in_each_t= '';
		  		if ( $in_each ){
		  			$in_each_t = '- in each leg';
		  		} else {
		  			$in_leg_count = afl_variable_get('rank_'.$i.'_rank_'.$j.'_required_in_legs');
		  			if ( $in_leg_count ) {
		  				$in_each_t = '- in '.$in_leg_count.' leg';
		  			}
		  		}
		  		if ($required) 
		  			$markup .= $required .' '.  afl_variable_get('rank_'.$j.'_name', 'Rank '.$j).' '.$in_each_t;
		  	}
		  }
		 $rows[$i]['markup_4'] = array(
				'#type' => 'markup',
				'#markup'=> $markup,
			);
 		/* ------ Rank qualifications ----------------------------------------------------------*/

 	}
	$table['#rows'] = $rows;
	echo afl_render_table($table);
}