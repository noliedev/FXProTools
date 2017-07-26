<?php 

/*
 * --------------------------------------------------------------------
 * Migration database version 1.0
 * --------------------------------------------------------------------
*/
 class Migration_database_version_1_0 {
 		private $tbl_prefix 			= '';
		private $charset_collate 	= '';

		public function __construct (){
			global $wpdb;
			$this->tbl_prefix 		 = $wpdb->prefix;
			$this->charset_collate = $wpdb->get_charset_collate();
		}
 		//update the database
	 	public function migration_upgrade() {
	 		$this->afl_variables();
	 		$this->afl_user_downlines();
	 		$this->afl_user_genealogy();
	 		$this->afl_user_transactions();
	 		$this->afl_business_transactions();
	 		$this->afl_business_funds();
	 		$this->afl_user_transactions_overview();


	 	}
	 	//downgrade the database version
	 	public function migration_downgrade() {
	 		// echo 'down';
	 	}
	/* 
	 * ----------------------------------------------------------------------------------------------------------- 
	 * Variables Table
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_variables(){
			$table_name = $this->tbl_prefix . 'afl_variable';

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
	/* 
	 * ----------------------------------------------------------------------------------------------------------- 
	 * User downlines
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_downlines (){
			$table_name = $this->tbl_prefix . 'afl_user_downlines';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_downline_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Registered user',
					  `downline_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Down-line user',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level of down-line user',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created date',
					  `status` tinyint(4) NOT NULL COMMENT 'Status',
					  `position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
					  `relative_position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
					  `member_rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Rank',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `enrolment_package_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_day` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_month` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_year` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_week` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date in format',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `ini_payment` int(10) unsigned DEFAULT '0' COMMENT 'Initial Payments',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='Stores the user down-line information';";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			global $wpdb;

			//indexes
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							ADD PRIMARY KEY (`afl_user_downline_id`);' );

			//AUTO_INCREMENT
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							MODIFY `afl_user_downline_id` int(11) NOT NULL AUTO_INCREMENT;' );

		}
	/* 
	 * ----------------------------------------------------------------------------------------------------------- 
	 * user Genealogy
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_genealogy(){
			$table_name = $this->tbl_prefix . 'afl_user_genealogy';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_genealogy_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Registered user',
					  `referrer_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Referrer user',
					  `parent_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Parent user',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
					  `left_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Left Child',
					  `middle_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Middle Child',
					  `right_child` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Right Child',
					  `status` tinyint(4) NOT NULL COMMENT 'Status',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `modified` int(10) unsigned DEFAULT '0' COMMENT 'Modified',
					  `member_rank` int(10) unsigned DEFAULT '0' COMMENT 'Rank',
					  `position` varchar(100) DEFAULT NULL COMMENT 'Genealogy position',
					  `relative_position` varchar(100) DEFAULT NULL COMMENT 'Genealogy relative position',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `amount_paid` int(10) unsigned NOT NULL DEFAULT '0',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `expiry_date` int(10) unsigned DEFAULT '0',
					  `expiry_date_2` int(10) unsigned NOT NULL DEFAULT '0',
					  `enrolment_package_id` int(10) unsigned NOT NULL DEFAULT '0',
					  `joined_day` int(10) unsigned DEFAULT '0',
					  `joined_month` int(10) unsigned DEFAULT '0',
					  `joined_year` int(10) unsigned DEFAULT '0',
					  `joined_week` int(10) unsigned DEFAULT '0',
					  `joined_date` varchar(100) DEFAULT NULL COMMENT 'Joined Date',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `extra_info` varchar(300) DEFAULT NULL COMMENT 'Extra Info',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Stores the user genealogy information';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
			global $wpdb;

			//indexes
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  						ADD PRIMARY KEY (`afl_user_genealogy_id`);' );

			//AUTO_INCREMENT
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							MODIFY `afl_user_genealogy_id` int(11) NOT NULL AUTO_INCREMENT;' );
		}
	/* 
	 * ----------------------------------------------------------------------------------------------------------- 
	 * User Transactions
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_transactions(){
			$table_name = $this->tbl_prefix . 'afl_user_transactions';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_transactions_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
					  `rejoined_phase` int(10) unsigned DEFAULT '0' COMMENT 'Rejoined Phase',
					  `associated_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Associated user id',
					  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Level',
					  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
					  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
					  `notes` varchar(350) DEFAULT NULL COMMENT 'Notes',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `additional_notes` varchar(350) DEFAULT NULL COMMENT 'Additional Notes',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Order ID',
					  `transaction_day` int(10) unsigned DEFAULT '0' COMMENT 'Transaction day 1-31',
					  `transaction_month` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Month 1-12',
					  `transaction_year` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Year',
					  `transaction_week` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Week',
					  `transaction_date` varchar(100) DEFAULT NULL COMMENT 'Transaction Date',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `int_payout` int(11) DEFAULT '0' COMMENT 'Payment Initiated',
					  `hidden_transaction` int(11) DEFAULT '0' COMMENT 'Hidden Transactions',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name',
					  `payout_id` int(10) unsigned DEFAULT '0' COMMENT 'Order ID',
					  `withdrawal_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Withdrawal Date'
					) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='Stores the user transactions';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
			global $wpdb;
			//indexes
			$wpdb->query('ALTER TABLE `'.$table_name.'`
  							ADD PRIMARY KEY (`afl_user_transactions_id`);');
			
			//AUTO_INCREMENT
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							MODIFY `afl_user_transactions_id` int(11) NOT NULL AUTO_INCREMENT;' );
		}
	/* 
	 * ----------------------------------------------------------------------------------------------------------- 
	 * Afl business transactions
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_business_transactions () {
			$table_name = $this->tbl_prefix . 'afl_business_transactions';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_business_transactions_id` int(11) NOT NULL,
					  `associated_user_id` int(10) unsigned DEFAULT '0' COMMENT 'Associated user id',
					  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Member',
					  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
					  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
					  `notes` varchar(350) DEFAULT NULL COMMENT 'Notes',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `additional_notes` varchar(350) DEFAULT NULL COMMENT 'Additional Notes',
					  `order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Order ID',
					  `transaction_day` int(10) unsigned DEFAULT '0' COMMENT 'Transaction day 1-31',
					  `transaction_month` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Month 1-12',
					  `transaction_year` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Year',
					  `transaction_week` int(10) unsigned DEFAULT '0' COMMENT 'Transaction Week',
					  `transaction_date` varchar(100) DEFAULT NULL COMMENT 'Transaction Date',
					  `deleted` int(10) unsigned DEFAULT '0' COMMENT 'Deleted Status',
					  `int_payout` int(11) DEFAULT '0' COMMENT 'Payment Initiated',
					  `hidden_transaction` int(11) DEFAULT '0' COMMENT 'Hidden Transactions',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='Stores the business transactions';";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
			global $wpdb;

			//indexes
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							ADD PRIMARY KEY (`afl_business_transactions_id`);' );

			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							MODIFY `afl_business_transactions_id` int(11) NOT NULL AUTO_INCREMENT' );

		}
	/* 
	 * ----------------------------------------------------------------------------------------------------------- 
	 * Business Funds 
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_business_funds () {
			$table_name = $this->tbl_prefix . 'afl_business_funds';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_business_fund_id` int(11) NOT NULL,
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` bigint(20) NOT NULL DEFAULT '0',
					  `modified` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'modified',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Stores the user transactions overview';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
			global $wpdb;
			//indexes
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							ADD PRIMARY KEY (`afl_business_fund_id`);' );
			//AUTO_INCREMENT
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							MODIFY `afl_business_fund_id` int(11) NOT NULL AUTO_INCREMENT;' );
		}
	/* 
	 * ----------------------------------------------------------------------------------------------------------- 
	 * User transactions Overview 
	 * -----------------------------------------------------------------------------------------------------------
	*/
		private function afl_user_transactions_overview () {
			$table_name = $this->tbl_prefix . 'afl_user_transactions_overview';
			$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
					  `afl_user_transactions_overview_id` int(11) NOT NULL,
					  `uid` int(10) unsigned NOT NULL COMMENT 'Member',
					  `credit_status` tinyint(4) NOT NULL COMMENT 'Credit Status',
					  `amount_paid` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `currency_code` varchar(100) DEFAULT NULL COMMENT 'Currency Code',
					  `balance` decimal(50,25) NOT NULL DEFAULT '0.0000000000000000000000000',
					  `category` varchar(100) DEFAULT NULL COMMENT 'Payment Source',
					  `created` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Created',
					  `merchant_id` varchar(250) DEFAULT 'default' COMMENT 'Merchant Id',
					  `extra_params` varchar(250) DEFAULT '' COMMENT 'Extra Params',
					  `project_name` varchar(250) DEFAULT 'default' COMMENT 'Project name'
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the user transactions overview';";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
			global $wpdb;
			//indexes
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							ADD PRIMARY KEY (`afl_user_transactions_overview_id`);' );
			//indexes
			$wpdb->query( 'ALTER TABLE `'.$table_name.'`
  							MODIFY `afl_user_transactions_overview_id` int(11) NOT NULL AUTO_INCREMENT;' );
		}

 }