<?php
/*
 * ------------------------------------------------------------
 * Install functions and Features 
 * ------------------------------------------------------------
*/
function eps_affiliates_install() {
	//create basic roles 
	create_eps_roles();

	// Create affiliate caps
	$roles = new Eps_affiliates_Capabilities;
	$roles->add_caps();

	//install tables
	$afl_tables = new Eps_affiliates_tables;


	//create page 
	if ( ! get_option( 'eps_afl_is_installed' ) ) {
		$affiliate_area = wp_insert_post(
			array(
				'post_title'     => __( 'EPS-Affiliates', 'eps-affiliates' ),
				'post_content'   => '[eps_affiliates]',
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id(),
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);
	}
	//set the variable for install
	update_option( 'eps_afl_is_installed', '1' );
	//set the variable for page id
	update_option( 'eps_affiliate_page', $affiliate_area );

	$Eps_affiliate_install  = new stdClass();
}
/*
 * ------------------------------------------------------------
 * Check the plugin installed or not
 * ------------------------------------------------------------
*/
	function eps_affiliate_check_if_installed() {
		// this is mainly for network activated installs
		if( ! get_option( 'eps_afl_is_installed' ) ) {
			eps_affiliates_install();
		}
	}
/*
 * ------------------------------------------------------------
 * Create basic eps roles
 * ------------------------------------------------------------
*/
 	function create_eps_roles() {
	  add_role( 'afl_member', 
	  					'AFL Member', 
	  					array( 'read' => true, 'level_0' => true,'level_1' => true,'eps_system_member'=>true) 
	  );
	  add_role( 'business_admin', 
	  					'Business Admin', 
	  					array( 'read' => true, 'level_0' => true,'level_1' => true,'eps_system_member'=>true) 
	  );
	  add_role( 'business_director', 
	  					'Business Director', 
	  					array('read' => true, 'level_0' => true,'level_1' => true,'eps_system_member'=>true) 
	  );
	}
