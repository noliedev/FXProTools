<?php 
/**
 * -----------------------------------------
 * Creates all the dashbord widgets
 * -----------------------------------------
 * @author < pratheesh@epixelsolutions.com >
 *
*/

class Eps_affiliates_dashboard_widget {
	public function __construct() {
		wp_add_dashboard_widget(
	     'afl_member_downlines_widget',         // Widget slug.
	     'Member Downlines',         // Title.
	     array($this,'afl_member_downlines_widget_callback') // Display function.
    );	
	}

	public function afl_member_downlines_widget_callback () {
		echo "Hello World, I'm a great Dashboard Widget";
	}
}