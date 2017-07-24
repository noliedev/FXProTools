<?php 
/*
 * -------------------------------------------------------------------------
 * Includes All the menus of admin
 * -------------------------------------------------------------------------
 *
*/
	class Eps_Affiliates_Admin_Menu {
		/* -------------------------------------------------------------------------
		 * Construct Admin Menus
		 * -------------------------------------------------------------------------
		*/
			public function __construct() {

				add_action( 'admin_menu', array( $this , 'afl_network_menus' ) );
				add_action( 'admin_menu', array( $this , 'afl_ewallet_menus') );
				add_action( 'admin_menu', array( $this , 'afl_epin_menus') );
				add_action( 'admin_menu', array( $this , 'afl_business_menus') );
				add_action( 'admin_menu', array( $this , 'afl_manage_members_menus') );
				add_action( 'admin_menu', array( $this , 'afl_promotion_tools_menus') );
				add_action( 'admin_menu', array( $this , 'afl_payout_menus') );
				add_action( 'admin_menu', array( $this , 'afl_general_help_menus') );

				add_action( 'admin_menu', array( $this , 'afl_system_settings') );




			}
		/* -------------------------------------------------------------------------
		 *  All system Configuration
		 * -------------------------------------------------------------------------
		*/
			public function afl_system_settings (){
				$menu = array();
				$menu['system_settings'] = array(
					'#page_title'			=> __( 'Eps Affiliates System Settings', 'System Settings' ), 
					'#menu_title' 		=> __( 'Eps Affiliates System Settings', 'System Settings' ), 
					'#access_callback'=> 'system_settings', 
					'#menu_slug' 			=> 'system-configurations', 
					'#page_callback' 	=> 'afl_admin_advanced_configuration', 
				);
				//advanced configurations
				$menu['advanced_config'] = array(
					'#parent'					=> 'system-configurations',
					'#page_title'			=> __( 'Advanced Configurations', 'eps-affiliates' ), 
					'#menu_title' 		=> __( 'Advanced Configurations', 'eps-affiliates' ), 
					'#access_callback'=> 'system_settings', 
					'#menu_slug' 			=> 'system-configurations', 
					'#page_callback' 	=> 'afl_admin_advanced_configuration', 
				);
				//compensation plan
				$menu['compensation_plan'] = array(
					'#parent'					=> 'system-configurations',
					'#page_title'			=> __( 'Compensation Plan Configurations', 'eps-affiliates' ),
					'#menu_title' 		=> __( 'Compensation Plan Configurations', 'eps-affiliates' ),
					'#access_callback'=> 'compensation_plan_configurations', 
					'#menu_slug' 			=> 'compensation-plan-configurations', 
					'#page_callback' 	=> 'afl_admin_compensation_plan_configuration', 
				);
				//rank configuration
				$menu['rank_configurations'] = array(
					'#parent'					=> 'system-configurations',
					'#page_title'			=> __( 'Rank Configurations', 'eps-affiliates' ),
					'#menu_title' 		=> __( 'Rank Configurations', 'eps-affiliates' ),
					'#access_callback'=> 'system_rank_configurations', 
					'#menu_slug' 			=> 'rank-configurations', 
					'#page_callback' 	=> 'afl_admin_rank_configuration', 
				);
				//role permission set
				$menu['role_permissions'] = array(
					'#parent'					=> 'system-configurations',
					'#page_title'			=> __( 'Roles permission Settings', 'Roles permission Settings' ), 
					'#menu_title' 		=> __( 'Roles permission Settings', 'Roles permission Settings' ), 
					'#access_callback'=> 'afl_roles_configuration', 
					'#menu_slug' 			=> 'role-config-settings', 
					'#page_callback' 	=> 'afl_roles_config_settings', 
				);
				//system business members manage
				$menu['business_system_members'] = array(
					'#parent'					=> 'system-configurations',
					'#page_title'			=> __( 'Business System Members', 'Business System Members' ),
					'#menu_title' 		=> __( 'Business System Members', 'Business System Members' ),
					'#access_callback'=> 'business_system_members', 
					'#menu_slug' 			=> 'business-system-members', 
					'#page_callback' 	=> 'afl_add_edit_business_system_members', 
				);
				afl_system_admin_menu($menu);

			}
		
		/*
		 * -------------------------------------------------------------------------
		 * Network menus callback
		 * -------------------------------------------------------------------------
		 *
		 * -------------------------------------------------------------------------
		*/
			public function afl_network_menus(){
				$menu = array();
				$menu['network'] = array(
					'#page_title'			=> __( 'Network', 'network' ), 
					'#menu_title' 		=> __( 'Network', 'network' ), 
					'#access_callback'=> 'afl_add_new_member', 
					'#menu_slug' 			=> 'user-network', 
					'#page_callback' 	=> 'afl_add_new_member', 
					'#weight'					=> 2
				);
				$menu['add_new_member'] = array(
					'#parent'					=> 'user-network',
					'#page_title'			=> __( 'Add new member', 'Add new Member' ), 
					'#menu_title' 		=> __( 'Add new member', 'Add new Member' ), 
					'#access_callback'=> 'afl_add_new_member', 
					'#menu_slug' 			=> 'user-network', 
					'#page_callback' 	=> 'afl_add_new_member', 
				);
				$menu['network_explorer'] = array(
					'#parent'					=> 'user-network',
					'#page_title'			=> __( 'Network Explorer', 'Network Explorer' ),    
					'#menu_title' 		=> __( 'Network Explorer', 'Network Explorer' ),    
					'#access_callback'=> 'afl_network_view', 
					'#menu_slug' 			=> 'network-explorer', 
					'#page_callback' 	=> 'afl_network_explorer', 
				);

				$menu['network_downlines'] = array(
					'#parent'					=> 'user-network',
					'#page_title'			=> __( 'Downline-members', 'Downline-members' ),    
					'#menu_title' 		=> __( 'Downline-members', 'Downline-members' ),    
					'#access_callback'=> 'afl_network_view', 
					'#menu_slug' 			=> 'downline-members', 
					'#page_callback' 	=> 'afl_downline_members', 
				);
				$menu['network_genealogy'] = array(
					'#parent'					=> 'user-network',
					'#page_title'			=> __( 'Genealogy-tree', 'Genealogy-tree' ),    
					'#menu_title' 		=> __( 'Genealogy-tree', 'Genealogy-tree' ),    
					'#access_callback'=> 'afl_network_view', 
					'#menu_slug' 			=> 'genealogy-tree', 
					'#page_callback' 	=> 'afl_genealogy_tree', 
				);
				afl_system_admin_menu($menu);

			}


		/*
		 * -------------------------------------------------------------------------
		 * E-wallet menus
		 * ------------- ------------------------------------------------------------
		*/
			public function afl_ewallet_menus(){
				$menu = array();
				$menu['e_wallet'] = array(
					'#page_title'			=> __( 'E-wallet', 'e_wallet' ), 
					'#menu_title' 		=> __( 'E-wallet', 'e_wallet' ), 
					'#access_callback'=> 'ewallet', 
					'#menu_slug' 			=> 'e-wallet', 
					'#page_callback' 	=> 'afl_ewallet_transactions', 
					'#weight'					=> 3
				);
				$menu['e_wallet_sub'] = array(
					'#parent'					=> 'e-wallet', 
					'#page_title'			=> __( 'E-wallet', 'e_wallet' ), 
					'#menu_title' 		=> __( 'E-wallet', 'e_wallet' ), 
					'#access_callback'=> 'ewallet', 
					'#menu_slug' 			=> 'e-wallet', 
					'#page_callback' 	=> 'afl_ewallet_transactions', 
				);
				$menu['e_wallet_summary'] = array(
					'#parent'					=> 'e-wallet', 
					'#page_title'			=> __( 'E-wallet Summary', 'E-wallet Summary' ),    
					'#menu_title' 		=> __( 'E-wallet Summary', 'E-wallet Summary' ),    
					'#access_callback'=> 'ewallet', 
					'#menu_slug' 			=> 'e-wallet-summary', 
					'#page_callback' 	=> 'afl_ewallet_summary', 
				);
				$menu['e_wallet_all_trans'] = array(
					'#parent'					=> 'e-wallet', 
					'#page_title'			=> __( 'All Transactions', 'All Transactions' ),    
					'#menu_title' 		=> __( 'All Transactions', 'All Transactions' ),    
					'#access_callback'=> 'ewallet', 
					'#menu_slug' 			=> 'ewallet-all-transactions', 
					'#page_callback' 	=> 'afl_ewallet_all_transactions', 
				);
				$menu['e_wallet_inc_report'] = array(
					'#parent'					=> 'e-wallet', 
					'#page_title'			=> __( 'Income Report', 'Income Report' ),    
					'#menu_title' 		=> __( 'Income Report', 'Income Report' ),    
					'#access_callback'=> 'ewallet', 
					'#menu_slug' 			=> 'ewallet-income-report', 
					'#page_callback' 	=> 'afl_ewallet_income_report', 
				);
				$menu['e_wallet_withdrawal'] = array(
					'#parent'					=> 'e-wallet', 
					'#page_title'			=> __( 'Withdrawal & Expense Report', 'Withdrawal & Expense Report' ),    
					'#menu_title' 		=> __( 'Withdrawal & Expense Report', 'Withdrawal & Expense Report' ),    
					'#access_callback'=> 'ewallet', 
					'#menu_slug' 			=> 'ewallet-withdraw-report', 
					'#page_callback' 	=> 'afl_ewallet_withdrawal_report', 
				);
				$menu['e_wallet_w_fund'] = array(
					'#parent'					=> 'e-wallet', 
					'#page_title'			=> __( 'Withdraw Fund', 'Withdraw Fund' ),    
					'#menu_title' 		=> __( 'Withdraw Fund', 'Withdraw Fund' ),    
					'#access_callback'=> 'ewallet', 
					'#menu_slug' 			=> 'ewallet-withdraw-fund', 
					'#page_callback' 	=> 'afl_ewallet_withdraw_fund', 
				);
				$menu['e_wallet_pending_withdrawal'] = array(
					'#parent'					=> 'e-wallet', 
					'#page_title'			=> __( 'Pending Withdrawal Request', 'Pending Withdrawal Request' ),    
					'#menu_title' 		=> __( 'Pending Withdrawal Request', 'Pending Withdrawal Request' ),    
					'#access_callback'=> 'ewallet', 
					'#menu_slug' 			=> 'ewallet-pending-withdrawal', 
					'#page_callback' 	=> 'afl_ewallet_pending_withdrawals', 
				);

				afl_system_admin_menu($menu);
			}
		/*
		 * -------------------------------------------------------------------------
		 * E-pin menus
		 * ------------- ------------------------------------------------------------
		*/
			public function afl_epin_menus() {
				$menu = array();
				$menu['e_pin'] = array(
					'#page_title'			=> __( 'E-pin', 'e_pin' ),
					'#menu_title' 		=> __( 'E-pin', 'e_pin' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin', 
					'#page_callback' 	=> 'afl_epins', 
					'#weight'					=> 4
				);
				$menu['e_pin_sub'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'E-pin', 'e_pin' ),
					'#menu_title' 		=> __( 'E-pin', 'e_pin' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin', 
					'#page_callback' 	=> 'afl_epins', 
				);
				$menu['e_pin_generate'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'Generate E-pin', 'generate-e-pin' ),
					'#menu_title' 		=> __( 'Generate E-pin', 'generate-e-pin' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin-history', 
					'#page_callback' 	=> 'afl_epin_generate', 
				);
				$menu['e_pin_history'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'E-pin History', 'e-pin-history' ),
					'#menu_title' 		=> __( 'E-pin History', 'e-pin-history' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin-history', 
					'#page_callback' 	=> 'afl_epin_history', 
				);
				$menu['e_pin_config'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'E-pin Configurations', 'e-pin-configs' ),
					'#menu_title' 		=> __( 'E-pin Configurations', 'e-pin-configs' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin-configs', 
					'#page_callback' 	=> 'afl_epin_configurations', 
				);
				$menu['e_pin_delete'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'Delete E-pin', 'e-pin-delete' ),
					'#menu_title' 		=> __( 'Delete E-pin', 'e-pin-delete' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin-delete', 
					'#page_callback' 	=> 'afl_epin_delete', 
				);
				$menu['e_pin_refund'] = array(
					'#parent'					=> 'e-pin',
					'#page_title'			=> __( 'Refund from E-pin', 'e-pin-refund' ),
					'#menu_title' 		=> __( 'Refund from E-pin', 'e-pin-refund' ),
					'#access_callback'=> 'epin', 
					'#menu_slug' 			=> 'e-pin-refund', 
					'#page_callback' 	=> 'afl_epin_refund', 
				);
				afl_system_admin_menu($menu);
			}
		/*
		 * --------------------------------------------------------------------------
		 * Business menus
		 * --------------------------------------------------------------------------
		*/
			public function afl_business_menus(){
				$menu = array();
				$menu['business'] = array(
					'#page_title'			=> __( 'Business', 'Business' ),
					'#menu_title' 		=> __( 'Business ', 'Business' ),
					'#access_callback'=> 'business_transactions', 
					'#menu_slug' 			=> 'business', 
					'#page_callback' 	=> 'afl_business_summary', 
					'#weight'					=>	5.1
				);
				$menu['b_summary'] = array(
					'#parent'					=> 'business',
					'#page_title'			=> __( 'Business', 'Business' ),
					'#menu_title' 		=> __( 'Business Summary', 'Business Summary' ),
					'#access_callback'=> 'business_transactions', 
					'#menu_slug' 			=> 'business', 
					'#page_callback' 	=> 'afl_business_summary',
				);
				$menu['b_income'] = array(
					'#parent'					=> 'business',
					'#page_title'			=> __( 'Income history', 'Income history' ),
					'#menu_title' 		=> __( 'Income history', 'Income history' ),
					'#access_callback'=> 'business_transactions', 
					'#menu_slug' 			=> 'business-income-history', 
					'#page_callback' 	=> 'afl_business_income_history',
				);
				$menu['b_expence'] = array(
					'#parent'					=> 'business',
					'#page_title'			=> __( 'Expense Report', 'Expense Report' ),
					'#menu_title' 		=> __( 'Expense Report', 'Expense Report' ),
					'#access_callback'=> 'business_transactions', 
					'#menu_slug' 			=> 'business-expense-report', 
					'#page_callback' 	=> 'afl_business_expense_history',
				);
				$menu['b_transactions'] = array(
					'#parent'					=> 'business',
					'#page_title'			=> __( 'Business Transactions', 'Business Transactions' ),
					'#menu_title' 		=> __( 'Business Transactions', 'Business Transactions' ),
					'#access_callback'=> 'business_transactions', 
					'#menu_slug' 			=> 'business-expense-report', 
					'#page_callback' 	=> 'afl_business_transactions',
				);
				afl_system_admin_menu($menu);
			}
		/* 
		 * --------------------------------------------------------------------------
		 * Manage members
		 * --------------------------------------------------------------------------
		*/
		 public function afl_manage_members_menus(){
		 	$menu = array();
			$menu['manage_members'] = array(
				'#page_title'			=> __( 'Manage Members', 'Manage Members' ),
				'#menu_title' 		=> __( 'Manage Members ', 'Manage Members' ),
				'#access_callback'=> 'manage_members', 
				'#menu_slug' 			=> 'manage-members', 
				'#page_callback' 	=> 'afl_members_manage', 
				'#weight'					=>	5.2
			);
			$menu['members_manage'] = array(
				'#parent'					=> 'manage-members',
				'#page_title'			=> __( 'Manage Members', 'Manage Members' ),
				'#menu_title' 		=> __( 'Manage Members ', 'Manage Members' ),
				'#access_callback'=> 'manage_members', 
				'#menu_slug' 			=> 'manage-members', 
				'#page_callback' 	=> 'afl_members_manage', 
			);
			$menu['members_blocked'] = array(
				'#parent'					=> 'manage-members',
				'#page_title'			=> __( 'Blocked Members', 'Blocked Members' ),
				'#menu_title' 		=> __( 'Blocked Members ', 'Blocked Members' ),
				'#access_callback'=> 'manage_members', 
				'#menu_slug' 			=> 'blocked-members', 
				'#page_callback' 	=> 'afl_members_blocked', 
			);
			$menu['members_find'] = array(
				'#parent'					=> 'manage-members',
				'#page_title'			=> __( 'Find Members', 'Find Members' ),
				'#menu_title' 		=> __( 'Find Members ', 'Find Members' ),
				'#access_callback'=> 'manage_members', 
				'#menu_slug' 			=> 'find-members', 
				'#page_callback' 	=> 'afl_members_find', 
			);
			afl_system_admin_menu($menu);

		 }
		/*
		 * --------------------------------------------------------------------------
		 * Promotional Tools
		 * --------------------------------------------------------------------------
		*/
			public function afl_promotion_tools_menus() {
				$menu = array();
				$menu['promotional_tools'] = array(
					'#page_title'			=> __( 'Promotional Tools', 'Promotional Tools' ),
					'#menu_title' 		=> __( 'Promotional Tools ', 'Promotional Tools' ),
					'#access_callback'=> 'promotional_tools', 
					'#menu_slug' 			=> 'promotional-tools', 
					'#page_callback' 	=> 'afl_referal_link', 
					'#weight'					=>	6
				);
				$menu['referal_link'] = array(
					'#parent'					=> 'promotional-tools',
					'#page_title'			=> __( 'Referal Link', 'Referal Link' ),
					'#menu_title' 		=> __( 'Referal Link', 'Referal Link' ),
					'#access_callback'=> 'promotional_tools', 
					'#menu_slug' 			=> 'promotional-tools', 
					'#page_callback' 	=> 'afl_referal_link', 
				);
				afl_system_admin_menu($menu);
			}
		/*
		 * --------------------------------------------------------------------------
		 * Payout 
		 * --------------------------------------------------------------------------
		*/
			public function afl_payout_menus() {
				$menu = array();
				$menu['payout'] = array(
					'#page_title'			=> __( 'Payout', 'Payout' ),
					'#menu_title' 		=> __( 'Payout', 'Payout' ),
					'#access_callback'=> 'payout', 
					'#menu_slug' 			=> 'payout', 
					'#page_callback' 	=> 'afl_payout_processing', 
					'#weight'					=>	6
				);
				$menu['payout_processing'] = array(
					'#parent'					=> 'payout',
					'#page_title'			=> __( 'Withdrawal request processing', 'Withdrawal request processing' ),
					'#menu_title' 		=> __( 'Withdrawal request processing', 'Withdrawal request processing' ),
					'#access_callback'=> 'payout', 
					'#menu_slug' 			=> 'payout', 
					'#page_callback' 	=> 'afl_payout_processing', 
				);
				$menu['payout_reports'] = array(
					'#parent'					=> 'payout',
					'#page_title'			=> __( 'Payout reports', 'Payout reports' ),
					'#menu_title' 		=> __( 'Payout reports', 'Payout reports' ),
					'#access_callback'=> 'payout', 
					'#menu_slug' 			=> 'payout-reports', 
					'#page_callback' 	=> 'afl_payout_reports', 
				);
				$menu['payout_remittance'] = array(
					'#parent'					=> 'payout',
					'#page_title'			=> __( 'Payout in Remittance', 'Payout in Remittance' ),
					'#menu_title' 		=> __( 'Payout in Remittance', 'Payout in Remittance' ),
					'#access_callback'=> 'payout', 
					'#menu_slug' 			=> 'payout-in-remittance', 
					'#page_callback' 	=> 'afl_payout_in_remittance', 
				);
				afl_system_admin_menu($menu);
			}
		/*
		 * --------------------------------------------------------------------------
		 * General Helps
		 * --------------------------------------------------------------------------
		*/
			public function afl_general_help_menus() {
				$menu = array();
				$menu['general_help'] = array(
					'#page_title'			=> __( 'General Help', 'General Help' ),
					'#menu_title' 		=> __( 'General Help', 'General Help' ),
					'#access_callback'=> 'general_help', 
					'#menu_slug' 			=> 'general-help', 
					'#page_callback' 	=> 'afl_general_help', 
					'#weight'					=>	6
				);
			}

	}


$eps_afl_admin_menu = new Eps_Affiliates_Admin_Menu;

