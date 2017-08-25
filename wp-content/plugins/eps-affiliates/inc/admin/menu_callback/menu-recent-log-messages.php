<?php
	function afl_admin_recent_log_messages () {
		do_action('eps_affiliate_page_header');
		do_action('afl_content_wrapper_begin');
		afl_admin_recent_log_messages_filter_form();
			afl_admin_recent_log_messages_table();
		do_action('afl_content_wrapper_end');
	}

	function afl_admin_recent_log_messages_filter_form () {
		$form = array();
 		$form['#method'] = 'GET';
		$form['#action'] = $_SERVER['REQUEST_URI'];
		$form['#prefix'] ='<div class="form-group row">';
	 	$form['#suffix'] ='</div>';

	 	//payment source
 		$form['page'] = array(
	 		'#type' => 'hidden',
	 		'#default_value' => 'affiliate-eps-recent-log-messages'
	 	);
	 	$form['severity'] = array(
	 		'#type' 					=> 'select',
	 		'#title' 					=> 'Severity',
	 		'#options'				=> array(
	 										'none'=>'None',
	 										LOGS_CRITICAL =>'Critical',
	 										LOGS_ERROR =>'Error',
	 										LOGS_WARNING =>'Warning',
	 										LOGS_NOTICE =>'Notice',
	 										LOGS_INFO =>'Info',
	 										LOGS_DEBUG =>'Debug'
	 		),
	 		'#prefix'					=> '<div class="form-group row col-md-3">',
	 		'#suffix' 				=> '</div>',
	 		'#default_value'  => !empty($_GET['severity']) ? $_GET['severity']:'none'

	 	);
	 	$form['type'] = array(
	 		'#type' 					=> 'select',
	 		'#title' 					=> 'Type',
	 		'#options'				=> _get_log_types(),
	 		'#prefix'					=> '<div class="form-group row col-md-3">',
	 		'#suffix' 				=> '</div>',
	 		'#default_value'  => !empty($_GET['severity']) ? $_GET['severity']:''

	 	);
	 	$form['submit'] = array(
	 		'#type' => 'submit',
	 		'#value' =>'Filter',
	 		'#prefix'					=> '<div class="form-group row col-md-12">',
	 		'#suffix' 				=> '</div>',

	 	);
	 	echo afl_render_form($form);
	 

	}

	function afl_admin_recent_log_messages_table () {
		$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_get_logs());
		$config['base_url'] 	= '?page=affiliate-eps-recent-log-messages';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _get_logs($index, $config['per_page']);

		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
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
			__('#'),
			__('Status'),
			__('Type'),
			__('Date'),
			__('Message'),
			__('Variables')		
		);
		$rows = array();
		foreach ($data as $key => $value) {
			$markup = '';
			if ( $value->severity == LOGS_WARNING) {
				$markup = '<i class="fa fa-exclamation-triangle " style="color:yellow;"></i>';
			}

			if ( $value->severity == LOGS_CRITICAL) {
				$markup = '<i class="fa fa-exclamation" ></i>';
			}

			if ( $value->severity == LOGS_ERROR) {
				$markup = '<i class="fa fa-times"></i>';
			}

			if ( $value->severity == LOGS_NOTICE) {
				$markup = '<i class="fa fa-check"></i>';
			}

			if ( $value->severity == LOGS_INFO) {
				$markup = '<i class="fa fa-info-circle"></i>';
			}

			if ( $value->severity == LOGS_DEBUG) {
				$markup = '';
			}
				$rows[$key]['markup_000'] = array(
				'#type' =>'markup',
				'#markup'=> ($index * 1) + ($key + 1)
			);
			$rows[$key]['markup_00'] = array(	
				'#type' =>'markup',
				'#markup'=> $markup
			);

			$rows[$key]['markup_0'] = array(
				'#type' =>'markup',
				'#markup'=> $value->type
			);
			$rows[$key]['markup_1'] = array(
				'#type' =>'markup',
				'#markup'=> date('Y-m-d H:i:s',$value->timestamp)
			);

			$rows[$key]['markup_2'] = array(
				'#type' =>'markup',
				'#markup'=> $value->message
			);

			$rows[$key]['markup_3'] = array(
				'#type' =>'markup',
				'#markup'=> !empty((maybe_unserialize($value->variables))) ?
										json_encode(maybe_unserialize($value->variables)) : ''
			);
		}
		$table['#rows'] = $rows;

	

		echo apply_filters('afl_render_table',$table);
	}

	function _get_logs ($index = 0, $limit = '') {
		$query = array();
		$query['#select'] = _table_name('afl_log_messages');

		if (!empty($limit) ) {
			$query['#limit'] = $index.','.$limit;
		}
		
		if ( !empty($_GET['severity']) && $_GET['severity'] !='none') {
			$query['#where'][] = 'severity='.$_GET['severity'];
		}
		if ( !empty($_GET['type']) && $_GET['type'] !='none') {
			$query['#where'][] = 'type="'.$_GET['type'].'"';
		}
		$resp = db_select($query, 'get_results');

			return $resp;
	}

	function _get_log_types(){
		$query['#select'] = _table_name('afl_log_messages');
		$query['#fields'] = array(
			 _table_name('afl_log_messages') => array('type')
		);
		$query['#group_by'] = array(
			'type'
		);
		$resp = db_select($query, 'get_results');
		$ret = array();
		$ret['none'] = 'None';
		foreach ($resp as $key => $value) {
			$ret[$value->type] = $value->type;
		}
		return $ret;
	}
