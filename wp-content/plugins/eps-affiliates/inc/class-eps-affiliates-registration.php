<?php 
/*
 * ----------------------------------------------------------
 * Affiliates registraion related methods
 * ----------------------------------------------------------
*/
class Eps_affiliates_registration {

	public function afl_join_member ($post_data = array()) {
		if (!empty($post_data)) {
			//insert to the geanalogy 
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
					$query 		= 'SELECT count(`downline_user_id`), `level` FROM `wp_afl_user_downlines` WHERE `uid`= %d GROUP BY `level` HAVING count(`downline_user_id`) <= POWER(3,`level`)';
				  $row 			= $wpdb->get_row($wpdb->prepare($query,$sponsor));
				 	// if the row is empty, then insert into first level
				 	if (empty($row)) {
				 		$level 		= 1;
				 		$position = 1;
			 			$parent		=  $post_data['sponsor_uid'];
				 	} else {

				 	}
				 	$afl_date_splits = afl_date_splits(afl_date());

				 	//insert to the downlines
					$downline_table = $wpdb->prefix . 'afl_user_downlines';
					if($wpdb->get_var("SHOW TABLES LIKE '$downline_table'") == $downline_table) {
						$downline_ins_data['uid'] 							= $sponsor;
					 	$downline_ins_data['downline_user_id'] 	= $post_data['sponsor_uid'];
					 	$downline_ins_data['level'] 						= $level;
					 	$downline_ins_data['status'] 						=	1;
					 	$downline_ins_data['position'] 					=	1;
					 	$downline_ins_data['relative_position']	=	1;
					 	$downline_ins_data['created'] 					= afl_date();
					 	$downline_ins_data['member_rank'] 			= 0;
					 	$downline_ins_data['joined_day'] 				= $afl_date_splits['d'];
					 	$downline_ins_data['joined_month'] 			= $afl_date_splits['m'];
					 	$downline_ins_data['joined_year'] 			= $afl_date_splits['y'];
					 	$downline_ins_data['joined_week'] 			= $afl_date_splits['w'];
					 	$downline_ins_data['joined_date'] 			= afl_date_combined($afl_date_splits);

				 		$downline_ins_id = $wpdb->insert($downline_table, $downline_ins_data);

					}
				 	//insert the genealogy details 
				 	
				 	$ins_data = array();
				 	$ins_data['uid'] 					= $post_data['uid'];
				 	$ins_data['referrer_uid'] = $post_data['sponsor_uid'];
				 	$ins_data['parent_uid'] 	= $parent;
				 	$ins_data['level'] 				= $level;
				 	$ins_data['status'] 			= 1;
				 	$ins_data['created'] 			= afl_date();
				 	$ins_data['modified'] 		= afl_date();
				 	$ins_data['joined_day'] 	= $afl_date_splits['d'];
				 	$ins_data['joined_month'] = $afl_date_splits['m'];
				 	$ins_data['joined_year'] 	= $afl_date_splits['y'];
				 	$ins_data['joined_week'] 	= $afl_date_splits['w'];
				 	$ins_data['joined_date'] 	= afl_date_combined($afl_date_splits);

				 	$ins_id = $wpdb->insert($table_name, $ins_data);
				 	
				}
			}
		}
	}
}
