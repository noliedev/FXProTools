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
				add_action( 'admin_menu', array( $this, 'advanced_config_menus' ) );
				add_action( 'admin_menu', array( $this, 'afl_network_menus' ) );
				add_action( 'admin_menu', array( $this , 'compensation_config_menus') );
			}
		/* -------------------------------------------------------------------------
		 * Admin Advanced Configuration menus callback
		 * -------------------------------------------------------------------------
		*/	
			public function advanced_config_menus() {
				add_menu_page( __( 'Advanced Configurations', 'eps-affiliates' ), 
											 __( 'Advanced Configurations', 'eps-affiliates' ), 
											 'advanced_configurations', 'advanced-configurations', 'afl_admin_advanced_configuration' 
				);
			}
		/*
		 * -------------------------------------------------------------------------
		 * Network menus callback
		 * -------------------------------------------------------------------------
		 * add_menu_page( 
		 				string $page_title, string $menu_title, 
		 				string $capability, string $menu_slug, 
		 				callable $function = '', string $icon_url = '', 
		 				int $position = null 
		 	)
		 * add_submenu_page( 
		 					string $parent_slug, string $page_title, 
		 					string $menu_title, string $capability, 
		 					string $menu_slug, callable $function = '' 
		 	)
		 * -------------------------------------------------------------------------
		*/
			public function afl_network_menus(){
				add_menu_page( __( 'Network', 'network' ), 
											 __( 'Network', 'network' ), 
											 'AFL_network_view', 'add-new-member', 'afl_add_new_member' 
				);
				$add_new_member 	= add_submenu_page( 
														'add-new-member', 
														__( 'Add new Member', 'Add new Member' ),   
														__( 'Add new Member', 'Add new Member' ), 
														'AFL_add_new_member', 'add-new-member','afl_add_new_member' 
													);

				$explorer   = add_submenu_page( 
												'add-new-member', 
												__( 'Network Explorer', 'Network Explorer' ),    
												__( 'Network Explorer', 'Network Explorer' ), 
												'AFL_network_view','network-explorer', 'afl_network_explorer' 
											);
				$explorer   = add_submenu_page( 
											'add-new-member', 
											__( 'Downline-members', 'Downline-members' ),    
											__( 'Downline-members', 'Downline-members' ), 
											'AFL_network_view','downline-members', 'afl_downline_members' 
										);
				$genealogy   = add_submenu_page( 
												'add-new-member', 
												__( 'Genealogy-tree', 'Genealogy-tree' ),    
												__( 'Genealogy-tree', 'Genealogy-tree' ), 
												'AFL_network_view', 'genealogy-tree', 'afl_genealogy_tree' 
										);
				$uplines   = add_submenu_page( 
											'add-new-member', 
											__( 'Uplines Tree', 'Uplines Tree' ),    
											__( 'Uplines Tree', 'Uplines Tree' ), 
											'AFL_network_view',	'uplines-tree', 'afl_genealogy_uplines_tree' 
									);
				// $date_settings = add_submenu_page( 
				// 								'eps-affiliates', 
				// 								__( 'System Date Settings', 'eps-affiliates' ),    
				// 								__( 'System Date Settings', 'eps-affiliates' ),
				// 								'advanced_configurations',  'eps-advcd-conf', 'afl_admin_advanced_configuration' 
				// 								);

			}
			public function compensation_config_menus() {
				
				add_menu_page( __( 'Compensation Plan Configurations', 'eps-affiliates' ), 
											 __( 'Compensation Plan', 'eps-affiliates' ), 
											 'compensation_plan_configurations', 
											 'compensation-plan-configurations', 'afl_admin_compensation_plan_configuration' 
				);
			}
	}
$eps_afl_admin_menu = new Eps_Affiliates_Admin_Menu;
