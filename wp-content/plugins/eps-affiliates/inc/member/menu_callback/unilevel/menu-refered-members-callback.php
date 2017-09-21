<?php
function afl_unilevel_refered_members () {
	echo afl_eps_page_header();
	afl_content_wrapper_begin();
		afl_unilevel_refered_members_callback();
	afl_content_wrapper_end();
}

function afl_unilevel_refered_members_callback () {
	$uid = get_uid();

	if (isset($_GET['uid'])) {
		$uid = $_GET['uid'];
	}

		//get user downlines details based on the uid
		// $data = afl_unilevel_get_user_downlines($uid);
	
		$pagination = new CI_Pagination;

		$config['total_rows'] =  (afl_unilevel_get_user_refered_downlines($uid,array(),TRUE));
		$config['base_url'] 	= '?page=affiliate-eps-unilevel-downline-members';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$filter = array(
			'start' => $index,
			'length' =>$config['per_page']
		);
		// $filter['fields'] = array(
		//   _table_name('afl_unilevel_user_downlines') => array('level'),
		//   _table_name('afl_unilevel_user_genealogy') => array('member_rank', 'relative_position','created'),
		//   _table_name('users') => array('display_name', 'ID')
		//  );
		$data  = afl_unilevel_get_user_refered_downlines($uid,$filter);
		// pr($data);
		
		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		// $table['#title'] 			= 'Business Profit Overview(Monthly)';
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
			__('#'),
			__('Userid'),
			__('Username'),
			__('Level'),		
			__('Relative Position'),		
			__('Rank'),		
			__('Created On'),		
		);
		$rows = array();

		foreach ($data as $key => $value) {
			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> $value->ID
			);
			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->display_name
			);
			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> $value->level
			);
			$rows[$key]['markup_4'] = array(
				'#type' =>'markup',
				'#markup'=> $value->relative_position
			);
			$rows[$key]['markup_5'] = array(
				'#type' =>'markup',
				'#markup'=> render_rank($value->member_rank)
			);
			$rows[$key]['markup_6'] = array(
				'#type' =>'markup',
				'#markup'=> $value->created
			);
		}
	
		$table['#rows'] = $rows;

		echo apply_filters('afl_render_table',$table);
		
}