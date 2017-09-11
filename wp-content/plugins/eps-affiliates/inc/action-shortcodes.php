<?php
/*
 * -----------------------------------------------------------------
 * Here comes the shorcodes for our functionalities
 * -----------------------------------------------------------------
*/
 
/* ----- Network Menus -------------*/
//genealogy tree
add_shortcode( 'afl_eps_genealogy_tree', 'afl_genealogy_tree_callback'  );

//downline members
add_shortcode( 'afl_eps_downlines'		 , 'afl_downline_members_callback');

//holding tank
add_shortcode( 'afl_eps_holding_tank'	 , 'afl_network_holding_tank_shortcode');

//ewallet
add_shortcode('afl_ewallet_summary'		 , 'afl_ewallet_summary_callback');

//sponsro info
add_shortcode('afl_sponsor_info'			 , 'afl_sponsor_info');

//team info
add_shortcode('afl_team_info'					 , 'afl_team_info');

//business profit report
add_shortcode('afl_business_profit_report_shortcode' , 'afl_system_business_profit_report_');