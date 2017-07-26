<?php 
function afl_genealogy_tree() {
	echo afl_eps_page_header();
	
	// define( 'EPSAFFILIATE_PLUGIN_PLAN', plugin_dir_url('eps-affiliates/inc/plan'));

	
	wp_enqueue_style( 'plan-heirarchy', EPSAFFILIATE_PLUGIN_PLAN.'/matrix/css/heirarchy/css/hierarchy-view.css');
	wp_enqueue_style( 'plan-style', EPSAFFILIATE_PLUGIN_PLAN.'matrix/css/heirarchy/css/main.css');
	// wp_enqueue_style( 'plan-style', EPSAFFILIATE_PLUGIN_PLAN.'matrix/css/tree-new/style.css');
	$path = EPSAFFILIATE_PLUGIN_PLAN.'matrix/';
	afl_get_template('plan/matrix/genealogy-tree-all.php');
}
