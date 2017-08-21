<?php
/*
 * -----------------------------------------------------------------
 * Here comes the shorcodes for our functionalities
 * -----------------------------------------------------------------
*/
 
/* ----- Network Menus -------------*/
add_shortcode( 'afl_eps_genealogy_tree', 'afl_genealogy_tree_callback'  );
add_shortcode( 'afl_eps_downlines'		 , 'afl_downline_members_callback');
add_shortcode( 'afl_eps_holding_tank'	 , 'afl_network_holding_tank');