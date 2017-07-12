<?php
/**
 * ---------------------------
 * Fxprotools - Admin Settings
 * ---------------------------
 * Fxprotools admin related settings
 */

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('CoreAdmin')){

	class CoreAdmin {
		
		public function __construct()
		{
			add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
			add_action('login_enqueue_scripts',  array($this, 'enqueue_admin_styles'));
			add_filter('login_headerurl', array($this, 'login_logo_link'));
			add_action('admin_menu',  array($this, 'remove_admin_menus'), 99);
			add_action('admin_init', array($this, 'remove_dashboard_meta'));
			
			// add_filter('login_redirect', array($this, 'dashboard_redirect'));
		}

		// Storefront Styles - Admin
		public function enqueue_admin_styles()
		{
			global $storefront_version;
			wp_enqueue_style(
				'storefront-admin-style',
				get_stylesheet_directory_uri().'/style-admin.css',
				array(),
				$storefront_version
			);
		}

		// Change link of logo in login(Default is wordpress link)
		public function login_logo_link()
		{
			return get_bloginfo('url');
		}

		// Remove Admin Menus
		public function remove_admin_menus()
		{
			remove_menu_page('index.php');                  // Dashboard
			remove_menu_page('jetpack');                    // Jetpack
			remove_menu_page('edit.php');                   // Posts
			remove_menu_page('upload.php');                 // Media
			// remove_menu_page('edit.php?post_type=page');    // Pages
			remove_menu_page('edit-comments.php');          // Comments
			// remove_menu_page('themes.php');                 // Appearance
			// remove_menu_page('plugins.php');                // Plugins
			// remove_menu_page('users.php');                  // Users
			// remove_menu_page('tools.php');                  // Tools
			// remove_menu_page('options-general.php');        // Settings
		}

		// Remove Dashboard Widgets
		public function remove_dashboard_meta()
		{
			if (!current_user_can( 'manage_options')){
				remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
				remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
				remove_meta_box('dashboard_primary', 'dashboard', 'normal');
				remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
				remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
				remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
				remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
				remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
				remove_meta_box('dashboard_activity', 'dashboard', 'normal');
			}
		}

		// Make Affiliate Overview as dashboard
		public function dashboard_redirect($url)
		{
			$redirect = 'wp-admin/admin.php?page=affiliate-wp';
			$roles = array('administrator');
			if(isset($user->roles) && is_array($user->roles)){
				if (in_array($roles, $user->roles)){
					return $redirect;
				} else {
					return home_url();
				}
			} else {
				return $redirect;
			}
		}

	}

}

return new CoreAdmin();