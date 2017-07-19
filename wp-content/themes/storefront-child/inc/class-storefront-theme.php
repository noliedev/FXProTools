<?php
/**
 * --------------
 * Theme Settings
 * --------------
 * Theme related settings
 */

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('StoreFrontTheme')){

	class StoreFrontTheme {
		
		// Initialize function(s)
		public function __construct()
		{
			add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
			add_action('wp_enqueue_scripts', array($this, 'enqueue_child_styles'), 99);
		}

		// Storefront Styles - Parent
		public function enqueue_styles()
		{
			global $storefront_version;
			wp_enqueue_style(
				'storefront-style',
				get_template_directory_uri().'/style.css',
				$storefront_version
			);
		}

		// Storefront Styles - Child
		public function enqueue_child_styles()
		{
			global $storefront_version, $storefront_child_version;
			wp_enqueue_style(
				'storefront-child-style',
				get_template_directory_uri().'/style.css',
				array('storefront-style'),
				$storefront_version
			);
		}

	}

}

return new StoreFrontTheme();