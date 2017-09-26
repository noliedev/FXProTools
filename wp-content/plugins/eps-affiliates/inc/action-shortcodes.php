<?php
/*
 * -----------------------------------------------------------------
 * Here comes the shorcodes for our functionalities
 * -----------------------------------------------------------------
*/
 
/* ----- Network Menus: matrix -------------*/
//genealogy tree
add_shortcode('afl_eps_matrix_genealogy_tree', 
							'afl_genealogy_tree_callback' );
//downline members
add_shortcode('afl_eps_matrix_downlines', 
							'afl_downline_members_callback');
//refered members
add_shortcode('afl_eps_matrix_reffered_downlines', 
							'afl_refered_members_callback');
//holding tank
add_shortcode('afl_eps_matrix_holding_tank', 
							'afl_network_holding_tank_shortcode');
//holding tank toggle placement
add_shortcode('afl_eps_matrix_holding_tank_genealogy_toggle_placement', 
							'afl_holding_tank_genealogy_toggle_placement_form');

/* ----- Network Menus: unilevel -------------*/
//genealogy tree
add_shortcode('afl_eps_unlilevel_genealogy_tree', 
							'afl_unilevel_genealogy_tree_callback' );
//downline members
add_shortcode('afl_eps_unlilevel_downlines', 
							'afl_unilevel_downline_members_callback');
//refered members
add_shortcode('afl_eps_unlilevel_reffered_downlines', 
							'afl_unilevel_refered_members_callback');
//holding tank
add_shortcode('afl_eps_unlilevel_holding_tank', 
							'afl_unilevel_network_holding_tank_callback');



//ewallet
add_shortcode('afl_ewallet_summary',
						  'afl_ewallet_summary_callback');
//sponsro info
add_shortcode('afl_sponsor_info', 
						  'afl_sponsor_info');
//team info
add_shortcode('afl_team_info', 
							'afl_team_info');
//genealogy info
add_shortcode('afl_genealogy_info', 
							'afl_genealogy_info');
//business profit report
add_shortcode('afl_business_profit_report_shortcode',
							'afl_system_business_profit_report_');