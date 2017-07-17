<?php 
/*
 * ----------------------------------------------------------------
 * Create tables if it doesnot exists
 * ----------------------------------------------------------------
 *
*/
class Eps_affiliates_tables {
	
	/*
 	 * ----------------------------------------------------------------
 	 * Set table prefix
 	 * ----------------------------------------------------------------
	*/
		private $tbl_prefix 			= '';
		private $charset_collate 	= '';
	/*
 	 * ----------------------------------------------------------------
	 * Constructor
 	 * ----------------------------------------------------------------
	*/
		public function __construct(){
			global $wpdb;
			$tbl_prefix 		 = $wpdb->prefix;
			$charset_collate = $wpdb->get_charset_collate();
			$this->afl_variables();
		}

	/*
 	 * ----------------------------------------------------------------
	 * Variables Table
 	 * ----------------------------------------------------------------
	*/
	function afl_variables(){
		$table_name = $tbl_prefix . 'afl_variable';

		$sql = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
				  `name` varchar(250) NOT NULL DEFAULT 'default' COMMENT 'The name of the variable.',
				  `merchant_id` varchar(250) NOT NULL DEFAULT 'default' COMMENT 'Merchant Id',
				  `extra_params` varchar(250) NOT NULL DEFAULT '' COMMENT 'Extra Params',
				  `project_name` varchar(250) NOT NULL DEFAULT 'default' COMMENT 'Project name',
				  `value` longblob NOT NULL COMMENT 'The value of the variable.'
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Variable table';";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	/**
 	 * ----------------------------------------------------------------
	 *  User details table
 	 * ----------------------------------------------------------------
	*/
}


$afl_tables = new Eps_affiliates_tables();
