<?php
/**
 * ---------------------------------
 * Storefront Child - Admin Settings
 * ---------------------------------
 * Storefront child admin related settings
 */

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('StoreFrontAdmin')){

	class StoreFrontAdmin {
		
		// Initialize function(s)
		public function __construct()
		{
			add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
			add_action('login_enqueue_scripts',  array($this, 'enqueue_admin_styles'));
			add_filter('login_headerurl', array($this, 'login_logo_link'));
			add_action('login_enqueue_scripts',  array($this, 'remove_admin_menus'));
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
			// remove_menu_page('index.php');                  // Dashboard
			// remove_menu_page('jetpack');                    // Jetpack
			// remove_menu_page('edit.php');                   // Posts
			// remove_menu_page('upload.php');                 // Media
			// remove_menu_page('edit.php?post_type=page');    // Pages
			// remove_menu_page('edit-comments.php');          // Comments
			// remove_menu_page('themes.php');                 // Appearance
			// remove_menu_page('plugins.php');                // Plugins
			// remove_menu_page('users.php');                  // Users
			// remove_menu_page('tools.php');                  // Tools
			// remove_menu_page('options-general.php');        // Settings
		}

	}

}

return new StoreFrontAdmin();