<?php 
/*
 * ----------------------------------------------------------
 * Affiliates registraion related methods
 * ----------------------------------------------------------
*/
class Eps_affiliates_registration {

	public function afl_join_member ($post_data = array()) {
	
		$plan_width = afl_variable_get('matrix_plan_width',3);
		if (!empty($post_data)) {
			//insert to the geanealogy 
			if (!empty($post_data['uid'] && !empty($post_data['sponsor_uid']))) {
				//first check the downlines count of sponsor and find out which level insert
				global $wpdb;
				$table_name = $wpdb->prefix . 'afl_user_genealogy';
				$sponsor = $post_data['sponsor_uid'];
				// if tables exists
				if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
					//First insert to downlines table
				 	// get user id counts in each level of the sponsor
					// also the count of the uids must lessthan or equals maximum of level users
					$query 			= 'SELECT count(`downline_user_id`) as count, `level` FROM `wp_afl_user_downlines` WHERE `uid`= %d GROUP BY `level` HAVING count(`downline_user_id`) < POWER(3,`level`)';
				  $row 				= $wpdb->get_row($wpdb->prepare($query,$sponsor));
				  
				  $max_query 	= 'SELECT MAX(`level`) FROM `wp_afl_user_downlines` WHERE `uid`= %d';
				  $max_level	= $wpdb->get_var($wpdb->prepare($max_query,$sponsor));
				  
				  
				  /**
 					 * ----------------------------------------------------------------------------
 					 * Here get the maximum level.
 					 * If the maximum level get from the database is empty, then the
 					 		level is 1
 					 * if the row get from the database is empty but maximum level available,which 
 					 *  means all the levels are filled and need to add new level, thus level will set
 					 *  maximum level + 1
 					 * else if unfilled row get and maximum level get thus level will be the 
 					 *  maximum level
 					 * ----------------------------------------------------------------------------
				  */
				  if (empty($max_level)){
				  	$level 		= 1;
				  	$relative_position = 1;
				  } else if(empty($row) && !empty($max_level)) {
				  	$level 		= $max_level + 1 ;
				  } else {
				  	$level 		= $max_level;
				  }
				  /**
				   * -----------------------------------------------------------------------------
				   * Here finds the $level, relative position and parent
				   * -----------------------------------------------------------------------------
				   * Condition 1 : 
				   *          if no row find from the table and the maximum level is empty, means 
				   *          need to insert first data.Thus set @var $level and @var $position
				   *					is 1 and @var $parent bust be the sponsor
				   * Condition 2 : 
				   *					 if the row is empty but the maximum level exist, means all the 
				   *           levels are filled and need to start filling with new level
				   *					 Thus @var $level set maximum level + 1 and @var relative position 
				   *					 set to 1 (starting from first)
				   * Else 
				   * 					 we get some unfilled detail.
				   *					- get all the maximum levelth users relative position of the sponsor
				   *					- create a relative positions array amd sort this ascending order
				   *					- after the sorting, get the middle relative position from the array
				   *						| if the count is even two middle number will get,
				   * 						| the next relative position is taken first middle + 1
				   *						| if the count if odd only one relative position get
				   *						|	this will be taken as the middle value
				   *					-	then need to find out the relative position of newly added member
				   *					- and it will be find by an equation
				   *					-
				   *					- @var $middle_relative_position  if the last added position
				   *					-
				   *					- pow($plan_width, $level) - ($middle_relative_position - 1 );
				   *					-
				   *					- @var Level : 2
				   *					- @var plan_width : 3
				   *					- pow(3,2) - (3 - 1) = 7
				   *					-	last added in the 3rd position then next add to 7 
				   *					-
				   *					- Another example
				   *					-	
				   *					-	
				   *					- @var Level : 2
				   *					- @var plan_width : 3
				   *					- @var last inserted in : 4
				   *					-
				   *					- Next relative position to add : 
				   *					- pow(3,2) - (4 - 1) = 6
				   * -----------------------------------------------------------------------------
				  */
				 	if (empty($row) && empty($max_level)) {
				 		$level 		= 1;
				 		$position = 1;
			 			$parent		=  $post_data['sponsor_uid'];
				 	} else if(empty($row) && !empty($max_level)){
				 		$level 						 = $max_level + 1;
				  	$relative_position = 1;

				  	$parent_query = 'SELECT `downline_user_id` FROM `wp_afl_user_downlines` WHERE `uid`= %d AND `level`= %d' ;
				  	$parent_uid		= $wpdb->get_var($wpdb->prepare($parent_query,$sponsor,$level));
				  	$parent 			=	$parent_uid;

				 	} else {

				  	$relative_positions_q = 'SELECT `relative_position` FROM `wp_afl_user_downlines` WHERE `uid`= %d AND `level`= %d' ;
				  	$relative_positions 	= $wpdb->get_results($wpdb->prepare($relative_positions_q,$sponsor,$level));

				  	$positions_array = array();
				  	foreach ($relative_positions as $key => $value) {
				  		$positions_array[] = $value->relative_position;
				  	}
				  	sort($positions_array);
				  	

				  	if(count($positions_array)%2 === 0){
						    $var = (count($positions_array)-1)/2;

						    // $middle_relative_position = $positions_array[$var];
						    // $middle_relative_position = $positions_array[$var+1] - 1;
						    $next_relative_position   = $positions_array[$var] + 1;
						}else{
						    $var = count($positions_array)/2;
								$middle_relative_position = $positions_array[$var];
								$next_relative_position 	= pow($plan_width, $level) - ($middle_relative_position - 1 );
						}

				  	
				  	$relative_position = $next_relative_position;
				  	

				 	}
				 	$afl_date_splits = afl_date_splits(afl_date());

				 	//insert to the downlines
					$downline_table = $wpdb->prefix . 'afl_user_downlines';
					if($wpdb->get_var("SHOW TABLES LIKE '$downline_table'") == $downline_table) {
						$downline_ins_data['uid'] 							= $sponsor;
					 	$downline_ins_data['downline_user_id'] 	= $post_data['uid'];
					 	$downline_ins_data['level'] 						= $level;
					 	$downline_ins_data['status'] 						=	1;
					 	$downline_ins_data['position'] 					=	1;
					 	$downline_ins_data['relative_position']	=	$relative_position;
					 	$downline_ins_data['created'] 					= afl_date();
					 	$downline_ins_data['member_rank'] 			= 0;
					 	$downline_ins_data['joined_day'] 				= $afl_date_splits['d'];
					 	$downline_ins_data['joined_month'] 			= $afl_date_splits['m'];
					 	$downline_ins_data['joined_year'] 			= $afl_date_splits['y'];
					 	$downline_ins_data['joined_week'] 			= $afl_date_splits['w'];
					 	$downline_ins_data['joined_date'] 			= afl_date_combined($afl_date_splits);
					 	
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
				 		//after this need to insert the upline users downline
				 		$uplines = afl_get_upline_uids($sponsor);
				 		foreach ($uplines as $upline_uid) {
				 			$level = $level + 1;
				 			$downline_ins_data['uid'] 							= $upline_uid;
						 	$downline_ins_data['downline_user_id'] 	= $post_data['uid'];
						 	$downline_ins_data['level'] 						= $level;
						 	$downline_ins_data['status'] 						=	1;
						 	$downline_ins_data['position'] 					=	1;
						 	$downline_ins_data['relative_position']	=	$relative_position;
						 	$downline_ins_data['created'] 					= afl_date();
						 	$downline_ins_data['member_rank'] 			= 0;
						 	$downline_ins_data['joined_day'] 				= $afl_date_splits['d'];
						 	$downline_ins_data['joined_month'] 			= $afl_date_splits['m'];
						 	$downline_ins_data['joined_year'] 			= $afl_date_splits['y'];
						 	$downline_ins_data['joined_week'] 			= $afl_date_splits['w'];
						 	$downline_ins_data['joined_date'] 			= afl_date_combined($afl_date_splits);

				 			$downline_ins_id = $wpdb->insert($downline_table, $downline_ins_data, $data_format);

				 		}
					}

					//get parent position 
					$parent = $this->afl_get_relative_parent($relative_position,$level,$sponsor);
				 	//insert the genealogy details 
				 	
				 	$ins_data = array();
				 	$ins_data['uid'] 								= $post_data['uid'];
				 	$ins_data['referrer_uid'] 			= $post_data['sponsor_uid'];
				 	$ins_data['parent_uid'] 				= $parent;
				 	$ins_data['level'] 							= $level;
				 	$ins_data['relative_position']	= $relative_position;
				 	$ins_data['status'] 						= 1;
				 	$ins_data['created'] 						= afl_date();
				 	$ins_data['modified'] 					= afl_date();
				 	$ins_data['joined_day'] 				= $afl_date_splits['d'];
				 	$ins_data['joined_month'] 			= $afl_date_splits['m'];
				 	$ins_data['joined_year'] 				= $afl_date_splits['y'];
				 	$ins_data['joined_week'] 				= $afl_date_splits['w'];
				 	$ins_data['joined_date'] 				= afl_date_combined($afl_date_splits);
				 	
				 	$ins_id = $wpdb->insert($table_name, $ins_data);
				 		// pr($parent);
				  	// pr('Parent : --------------- '. $parent);
				 		// pr('Level : --------------- '. $level);
				  	// pr('Relative Position : --------------- '. $relative_position);
				  	// pr('uid : --------------- '. $post_data['uid']);
				}
			}
		}
	}
	/*
	 * -----------------------------------------------------------------
	 * Here get the relative position upline user position
	 * -----------------------------------------------------------------
	*/
	public function afl_get_relative_parent ($relative_position = '', $level = '', $sponsor = '') {
		global $wpdb;
		//level not 1,
		//if level 1 the parent but be he
		if ($level > 1 ) {
			if (!empty($relative_position)) {
				$plan_width 			= afl_variable_get('matrix_plan_width',3);
				$parent_position 	= '';

				$parent_relative_pos = $relative_position / $plan_width;
				if (is_float($parent_relative_pos)) {
					$parent_position =  intval($parent_relative_pos)+1;
				} else
				 $parent_position  =  $parent_relative_pos;
			}
			

			if (!empty($parent_position)) {
				//get the sposnors's `$parent_position` positions th user id
				$parent_query = 'SELECT `uid` FROM `wp_afl_user_genealogy` WHERE `referrer_uid`= %d AND `level`= %d AND `relative_position` = %d' ;
				
				$parent_uid		= $wpdb->get_var($wpdb->prepare($parent_query,$sponsor,($level - 1),$parent_position));
				if ($parent_uid) {
					return $parent_uid;
				} else {
					return false;
				}
			}
		} else {
			return $sponsor;
		}
		
	}


}
