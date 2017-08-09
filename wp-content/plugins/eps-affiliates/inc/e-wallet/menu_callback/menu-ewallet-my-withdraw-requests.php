<?php

function afl_ewallet_my_withdraw_requests(){
	echo afl_eps_page_header();
	echo afl_content_wrapper_begin();
		afl_user_withdrawal_request();
	echo afl_content_wrapper_end();
}

/*
 * ------------------------------------------------------------
 * Variable config form
 * ------------------------------------------------------------
*/
 function afl_user_withdrawal_request () {
 	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'active_requestes';  

		  //here render the tabs
		  echo '<ul class="tabs--primary nav nav-tabs">';
		  echo '<li class="'.(($active_tab == 'active_requestes') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-ewallet-my-withdrawal&tab=active_requestes" >Active withdrawal Requests</a>  
		          </li>';
		           echo '<li class="'.(($active_tab == 'processed_requests') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-ewallet-my-withdrawal&tab=processed_requests" >Processed Requests</a>  
		          </li>';
		           echo '<li class="'.(($active_tab == 'rejected_requests') ? 'active' : '').'">
		            	<a href="?page=affiliate-eps-ewallet-my-withdrawal&tab=rejected_requests" >Rejected Requests</a>  
		          </li>';
		  echo '</ul>';

		  switch ($active_tab) {
		  	case 'active_requestes':
					afl_withdrawal_requests_active();
	  		break;
	  		case 'processed_requests':
	  			afl_withdrawal_requests_active();
	  		break;
	  		case 'rejected_requests':
	  			afl_withdrawal_requests_active();
	  		break;
		  }
 }

function afl_withdrawal_requests_active(){
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'no tab';
	pr($active_tab);

	$uid = get_current_user_id();

	if (isset($_GET['uid'])) {
		$uid = $_GET['uid'];
	}
	//get user downlines details based on the uid

	afl_content_wrapper_begin();

	wp_register_script( 'jquery-data-table',  EPSAFFILIATE_PLUGIN_ASSETS.'plugins/dataTables/js/jquery.dataTables.min.js');
	wp_enqueue_script( 'jquery-data-table' );

	wp_register_script( 'jquery-data-bootstrap-table',  EPSAFFILIATE_PLUGIN_ASSETS.'plugins/dataTables/js/dataTables.bootstrap.min.js');
	wp_enqueue_script( 'jquery-data-bootstrap-table' );

	wp_enqueue_style( 'plan-develoepr', EPSAFFILIATE_PLUGIN_ASSETS.'plugins/dataTables/css/dataTables.bootstrap.min.css');

	// wp_enqueue_scripts( 'jquery-data-table', EPSAFFILIATE_PLUGIN_ASSETS.'js/dataTables.bootstrap.min.js');
	// wp_enqueue_scripts( 'jquery-data-table', EPSAFFILIATE_PLUGIN_ASSETS.'js/jquery.dataTables.min.js');

?>
<div class="data-filters"></div>

<table id="afl-ewallet-summary-table" class="table table-striped table-bordered dt-responsive nowrap custom-withdraw-requsts-active" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Member</th>
                <th>Requested Amount</th>
                <th>Charges</th>
                <th>Requested Date</th>
                <th>Notes</th>
                <th>Preferred Method</th>
                <th>Amount Paid</th>
                <th>Paid Date</th>
                <th>Paid Status</th>
            </tr>
        </thead>
    </table>
	<?php 
		afl_content_wrapper_end();
}	
