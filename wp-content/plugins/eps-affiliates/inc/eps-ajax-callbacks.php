<?php 
/*
 * ------------------------------------------------
 * get users name and id
 * ------------------------------------------------
*/
function users_auto_complete_callback($search_key = '') {
	if (isset($_POST['search_key'])) {
		$search_key = $_POST['search_key'];
	}
	$data 		= afl_get_users();
	$response = array();

	global $wpdb;
	$querystr = " SELECT * from `wp_users` WHERE `display_name` LIKE '%".$search_key."%' ;";
	$result = $wpdb->get_results($querystr) or die(mysql_error());

	foreach ($result as $key => $value) {
		$response[] = array('name'=> ($value->user_login.' ('.$value->ID.')'));
	}
	echo json_encode($response);
	die();
}
/*
 * ------------------------------------------------
 * User downlines
 * ------------------------------------------------
*/
 function afl_user_downlines_data_table_callback () {
  $uid 					 = get_current_user_id();

 	$input_valu = $_POST;
 	if(!empty($input_valu['order'][0]['column']) && !empty($fields[$input_valu['order'][0]['column']])){
     $filter['order'][$fields[$input_valu['order'][0]['column']]] = !empty($input_valu['order'][0]['dir']) ? $input_valu['order'][0]['dir'] : 'ASC';
  }
  if(!empty($input_valu['search']['value'])) {
     $filter['search_valu'] = $input_valu['search']['value'];
  }
  // pr($filter['search_valu']);
  $filter['start'] 		= !empty($input_valu['start']) 	? $input_valu['start'] 	: 0;
  $filter['length'] 	= !empty($input_valu['length']) ? $input_valu['length'] : 5;

  $result_count = afl_get_user_downlines($uid,array(),TRUE); 
  $filter_count = afl_get_user_downlines($uid,$filter,TRUE); 
  // pr($result_count);
  // pr($filter_count);
 	$output = [
     "draw" 						=> $input_valu['draw'],
     "recordsTotal" 		=> $result_count,
     "recordsFiltered" 	=> $filter_count,
     "data" 						=> [],
   ];

   $downlines_data = afl_get_user_downlines($uid,$filter);

   foreach ($downlines_data as $key => $value) {
   	$output['data'][] = [
   		$value->ID,
   		$value->display_name,
   		$value->level,
   		date('Y-m-d',$value->created)
   	];
   }
   echo json_encode($output);
 	die();
 }