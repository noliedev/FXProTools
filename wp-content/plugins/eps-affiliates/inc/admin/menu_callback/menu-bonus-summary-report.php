<?php

function afl_bonus_summary_report () {
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
		_afl_bonus_summary_report();
	afl_content_wrapper_end();
}


function _afl_bonus_summary_report () {
	afl_get_template('plan/matrix/bonus-summary-widgets-template.php');
	_bonus_nd_incentives_table();
}

function _bonus_nd_incentives_table () {
		$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_get_bonus_nd_incentives());
		$config['base_url'] 	= '?page=affiliate-eps-business-profit';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _get_bonus_nd_incentives($index, $config['per_page']);

		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		$table['#title'] 			= 'Bonus/Incentives';
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
			__('Title of Bonus'),
			__('Rank Name'),
			__('Details'),
		);
		$rows = array();
		
		$max_rank = afl_variable_get('number_of_ranks');
		if ( $max_rank ) {
			for ( $i = 1 ; $i <= $max_rank; $i++ ) {
				if ( afl_variable_get('rank_'.$i.'_incentives','')) {
					$rows[$i]['markup_incentive'] = array(
						'#type' =>'markup',
						'#markup'=> afl_variable_get('rank_'.$i.'_incentives','')
					);

					$rows[$i]['markup_rank_name'] = array(
						'#type' =>'markup',
						'#markup'=> afl_variable_get('rank_'.$i.'_name','')
					);

					$rows[$i]['markup_details'] = array(
						'#type' =>'markup',
						'#markup'=> afl_variable_get('rank_'.$i.'_name','')
					);
				}
			}
		}


		$table['#rows'] = $rows;

	

		echo apply_filters('afl_render_table',$table);
}

function _get_bonus_nd_incentives ( $index = 0, $limit = '' ) {

}