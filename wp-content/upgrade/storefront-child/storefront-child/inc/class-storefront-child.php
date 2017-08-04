<?php
if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('StoreFrontChild')){

	class StoreFrontChild {
		
		// Setup Class
		public function __construct(){
			add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
			add_action('wp_enqueue_scripts', array($this, 'enqueue_child_styles'), 99);
		}

		// Storefront Styles - Parent
		public function enqueue_styles(){
			global $storefront_version;
			wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css', $storefront_version);
		}

		// Storefront Styles - Child
		public function enqueue_child_styles() {
			global $storefront_version, $storefront_child_version;
			wp_style_add_data('storefront-child-style', 'rtl', 'replace');
		}

	}

}

return new StoreFrontChild();