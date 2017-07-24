<?php 
function afl_downline_members() { 
	$uid = get_current_user_id();

	if (isset($_GET['uid'])) {
		$uid = $_GET['uid'];
	}

	//get user downlines details based on the uid
	$data = afl_get_user_downlines($uid);

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

<table id="example" class="table table-striped table-bordered dt-responsive nowrap custom-data-tables" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Userid</th>
                <th>User name</th>
                <th>Relative Position</th>
                <th>Level</th>
                <th>Created on</th>
            </tr>
        </thead>
    </table>
<?php 
afl_content_wrapper_end();
}