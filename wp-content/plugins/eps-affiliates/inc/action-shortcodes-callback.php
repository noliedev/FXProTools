<?php
/*
 * -----------------------------------------------------------------
 * Get the sponsor info
 * -----------------------------------------------------------------
*/
	function afl_sponsor_info() {
		afl_get_template('shortcode-templates/sponsor-info-template.php');
	}
/*
 * -----------------------------------------------------------------
 * Get the team info
 * -----------------------------------------------------------------
*/
	function afl_team_info() {
		afl_get_template('shortcode-templates/team-info-template.php');
	}
/*
 * -----------------------------------------------------------------
 * Network holding tank
 * -----------------------------------------------------------------
*/
	function afl_network_holding_tank_shortcode() {
		afl_get_template('plan/matrix/holding-tank.php');
	}