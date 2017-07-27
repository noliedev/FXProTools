<?php 
/*
 * ---------------------------------------------------
 * Include all the db operation function 
 * ---------------------------------------------------
*/
 class Eps_db {

 	/*
 	 * ---------------------------------------------------
 	 * Get the system members 
 	 * ---------------------------------------------------
 	*/
 		public function get_members ($args = array(), $count = false) {

 			global $wpdb;

 			$defaults = array(
				'number'       => 20,
				'offset'       => 0,
				'exclude'      => array(),
				'user_id'      => 0,
				'affiliate_id' => 0,
				'status'       => '',
				'order'        => 'DESC',
				'orderby'      => 'affiliate_id',
				'fields'       => '',
			);
 			$args = wp_parse_args( $args, $defaults );

			$query = array();
			$query['#select'] = 'wp_afl_user_genealogy';

			$query['#join'] 	= array(
				'wp_users' => array(
					'#condition' => '`wp_users`.`ID`=`wp_afl_user_genealogy`.`uid`'
				)
			);

			if (! empty( $args['user_id'] )) {
				$query['#where'] = array(
					'`wp_afl_user_genealogy`.`uid`='.$uid.''
				);
			}
			
			$result = db_select($query, 'get_results');
			
			if ($count) {
				$response  = count($result);
			} else {
				$response = $result;
			}

		 return apply_filters('affiliate_eps_members_list', $result);
 		}
 }