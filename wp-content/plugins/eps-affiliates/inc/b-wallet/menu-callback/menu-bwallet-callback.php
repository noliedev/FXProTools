<?php 

/*
 * ------------------------------------------------
 * Business trsnsation summary
 * ------------------------------------------------
*/
function afl_business_summary(){
	echo afl_eps_page_header();

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

<table id="afl-ewallet-summary-table" class="table table-striped table-bordered dt-responsive nowrap custom-business-summary-table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Amount</th>
            </tr>
        </thead>
    </table>
	<?php 
		afl_content_wrapper_end();
}

function afl_business_transactions(){
	echo afl_eps_page_header();
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

<table id="afl-ewallet-transaction-table" class="table table-striped table-bordered dt-responsive nowrap custom-business-all-trans-table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Associated Member</th>
                <th>Amount</th>
                <th>Credit Status</th>
                <th>Date</th>
                <th>Additional Notes</th>
            </tr>
        </thead>
    </table>
	<?php 
		afl_content_wrapper_end();
}

function afl_business_income_history(){
	echo afl_eps_page_header();
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

<table id="afl-ewallet-transaction-table" class="table table-striped table-bordered dt-responsive nowrap custom-business-income-history-table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Associated Member</th>
                <th>Amount</th>
                <th>Credit Status</th>
                <th>Date</th>
                <th>Additional Notes</th>
            </tr>
        </thead>
    </table>
	<?php 
		afl_content_wrapper_end();

}

function afl_business_expense_history(){
	echo afl_eps_page_header();
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

<table id="afl-ewallet-transaction-table" class="table table-striped table-bordered dt-responsive nowrap custom-business-expense-history-table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Associated Member</th>
                <th>Amount</th>
                <th>Credit Status</th>
                <th>Date</th>
                <th>Additional Notes</th>
            </tr>
        </thead>
    </table>
	<?php 
		afl_content_wrapper_end();

}
