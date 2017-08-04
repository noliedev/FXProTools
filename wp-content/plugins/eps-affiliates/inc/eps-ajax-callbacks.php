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
  if (eps_is_admin()) {
    $uid = afl_root_user();
  }
 	$input_valu = $_POST;
 	if(!empty($input_valu['order'][0]['column']) && !empty($fields[$input_valu['order'][0]['column']])){
     $filter['order'][$fields[$input_valu['order'][0]['column']]] = !empty($input_valu['order'][0]['dir']) ? $input_valu['order'][0]['dir'] : 'ASC';
  }
  if(!empty($input_valu['search']['value'])) {
     $filter['search_valu'] = $input_valu['search']['value'];
  }
  // pr($filter['search_valu']);
  $filter['start'] 		= !empty($input_valu['start']) 	? $input_valu['start'] 	: 0;
  $filter['length'] 	= !empty($input_valu['length']) ? $input_valu['length'] : 50;

  $filter['fields'] = array(
  _table_name('afl_user_downlines') => array('level'),
  _table_name('afl_user_genealogy') => array('member_rank', 'relative_position','created'),
  _table_name('users') => array('display_name', 'ID')
 );
  $result_count = afl_get_user_downlines($uid,array(),TRUE);
  $filter_count = afl_get_user_downlines($uid,$filter,TRUE);
  // pr($result_count);
  // pr($filter_count);
  $output = [
     "draw"             => $input_valu['draw'],
     "recordsTotal"     => $result_count,
     "recordsFiltered"  => $result_count,
     "data"             => [],
   ];


   $downlines_data = afl_get_user_downlines($uid,$filter, false);

   foreach ($downlines_data as $key => $value) {
   	$output['data'][] = [
   		$value->ID,
      $value->display_name,
   		$value->level,
      $value->relative_position,
   		render_rank($value->member_rank),
   		date('Y-m-d',$value->created)
   	];
   }
   echo json_encode($output);
 	die();
 }

/*
 * -------------------------------------------------------------------------
 * Expand Genealogy tree
 * -------------------------------------------------------------------------
*/
 function afl_expand_user_genealogy_tree () {
  afl_get_template('plan/matrix/genealogy-tree-expanded.php');
 }
 /*
  * ------------------------------------------------------------------------
  * Get available spaces under a user
  * ------------------------------------------------------------------------
 */
  function afl_get_available_free_space_callback() {
    // $_POST['sponsor'] = 10;
    // $_POST['uid'] = 1;
    // $_POST['parent'] = 'business.admin+(159)';

    if (!empty($_POST['sponsor']) && !empty($_POST['uid']) && $_POST['parent']) {
      $parent    = extract_sponsor_id($_POST['parent']);
      if ($parent) {
        $tree_width = afl_variable_get('matrix_plan_width',3);
        $positions  = array();

        for ($i = 1; $i <= $tree_width  ; $i++) {
          $positions[] = $i;
        }
        //get the filled positions of the selected parent
        $query = array();
        $query['#select'] = 'wp_afl_user_downlines';
        $query['#where']  = array(
          '`wp_afl_user_downlines`.`uid`='.$parent
        );
        $query['#fields'] = array(
          'wp_afl_user_downlines'=>  array(
                  'relative_position'
                )
        );
        $relative_positions_res = db_select($query, 'get_results');

        $relative_positions     = array();

        if ($relative_positions_res ){
          foreach ($relative_positions_res as $key => $value) {
            $relative_positions[] = $value->relative_position;
          }
        }

        $free_positions  =  array_merge(array_diff($relative_positions, $positions), array_diff($positions, $relative_positions));
        $html = '';
        $extra  = '<div class="form-item clearfix form-type-checkbox"> Available free space positions </div> ';
        for ($i = 1; $i <= $tree_width ; $i++){
          if (in_array($i, $free_positions)) {
            $html .= '<div class="form-item clearfix form-type-checkbox col-md-2">';
            $html .= '<label class="i-checks">';
            $html .='<input type="radio" name="free_space" id ="'.$i.'" class="form-checkbox checkbox form-control ">';
            $html .='<i></i>';
            $html .='</label>';
            $html .='<label class="option" for="'.$i.'">'.$i.'</label>';
            $html .='</div>';

          }
        }
        if (empty($html)) {
          echo 'There is free space available under this user.';
        } else {
         echo $extra.$html;
        }
         die();
      } else {
        echo 'Invalid parent choosen';
        die();
      }
    }
  }
/*
 * -----------------------------------------------------------------------------
 * Place a user from the tank to the tree
 * -----------------------------------------------------------------------------
*/
 function afl_place_user_from_tank_callback () {
  global $wpdb;
  $response = array();

  if (!empty($_POST['user_id']) && !empty($_POST['sponsor']) && !empty($_POST['parent']) && !empty($_POST['position'])) {
      $parent    = extract_sponsor_id($_POST['parent']);
      $sponsor  = $_POST['sponsor'];
      $uid      = $_POST['user_id'];
      $position = $_POST['position'];

      //add the role afl_member to the user if he has no role
      if (!has_role($uid, 'afl_member')){
        $theUser = new WP_User($uid);
        $theUser->add_role( 'afl_member' );
      }


    //insert user to genealogy
      $afl_date_splits = afl_date_splits(afl_date());

      $genealogy_table = $wpdb->prefix . 'afl_user_genealogy';
      $ins_data = array();
      $ins_data['uid']                = $uid;
      $ins_data['referrer_uid']       = $sponsor;
      $ins_data['parent_uid']         = $parent;
      $ins_data['level']              = 1;
      $ins_data['relative_position']  = $position;
      $ins_data['status']             = 1;
      $ins_data['created']            = afl_date();
      $ins_data['modified']           = afl_date();
      $ins_data['joined_day']         = $afl_date_splits['d'];
      $ins_data['joined_month']       = $afl_date_splits['m'];
      $ins_data['joined_year']        = $afl_date_splits['y'];
      $ins_data['joined_week']        = $afl_date_splits['w'];
      $ins_data['joined_date']        = afl_date_combined($afl_date_splits);


      $ins_id = $wpdb->insert($genealogy_table, $ins_data);

      //insert the user to the downlines

      $downline_table = $wpdb->prefix . 'afl_user_downlines';
      $downline_ins_data['uid']               = $parent;
      $downline_ins_data['downline_user_id']  = $uid;
      $downline_ins_data['level']             = 1;
      $downline_ins_data['status']            = 1;
      $downline_ins_data['position']          = 1;
      $downline_ins_data['relative_position'] = $position;
      $downline_ins_data['created']           = afl_date();
      $downline_ins_data['member_rank']       = 0;
      $downline_ins_data['joined_day']        = $afl_date_splits['d'];
      $downline_ins_data['joined_month']      = $afl_date_splits['m'];
      $downline_ins_data['joined_year']       = $afl_date_splits['y'];
      $downline_ins_data['joined_week']       = $afl_date_splits['w'];
      $downline_ins_data['joined_date']       = afl_date_combined($afl_date_splits);

      $data_format = array(
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s'
      );

      $downline_ins_id = $wpdb->insert($downline_table, $downline_ins_data, $data_format);
      //insert as the downlines of the uplines
      $uplines  = afl_get_upline_uids($parent);
      $sp_level = 1;

      foreach ($uplines as $upline_uid) {
        $sp_level = $sp_level + 1;
        $downline_ins_data['uid']               = $upline_uid;
        $downline_ins_data['downline_user_id']  = $uid;
        $downline_ins_data['level']             = $sp_level;
        $downline_ins_data['status']            = 1;
        $downline_ins_data['position']          = 1;
        $downline_ins_data['relative_position'] = $position;
        $downline_ins_data['created']           = afl_date();
        $downline_ins_data['member_rank']       = 0;
        $downline_ins_data['joined_day']        = $afl_date_splits['d'];
        $downline_ins_data['joined_month']      = $afl_date_splits['m'];
        $downline_ins_data['joined_year']       = $afl_date_splits['y'];
        $downline_ins_data['joined_week']       = $afl_date_splits['w'];
        $downline_ins_data['joined_date']       = afl_date_combined($afl_date_splits);

        $data_format = array(
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s'
        );
        $downline_ins_id = $wpdb->insert($downline_table, $downline_ins_data, $data_format);

      }

      //remove user from tank
      $wpdb->delete('wp_afl_user_holding_tank', array('uid'=>$uid));

      $response['status'] = 1;
      $response['msg']    = 'Member has been placed successfully';
      echo json_encode($response);
      die();
  } else {
      $response['status'] = 0;
      $response['msg']    = 'Unexpected error occured';
      echo json_encode($response);
      die();
  }

 }

// pr(afl_genealogy_node(163),1);
