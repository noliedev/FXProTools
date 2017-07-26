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

if(!class_exists('ThemeSettings')){

	class ThemeSettings {
		
		public function __construct()
		{
			add_action('wp_enqueue_scripts', array($this, 'enqueue_theme_assets'));
		}

		// Theme assets
		public function enqueue_theme_assets()
		{
			global $theme_version;
			// Disable loading of jquery on wordpress core
			if(!is_admin()){				
				wp_deregister_script('jquery'); 
				wp_deregister_script('wp-embed');
			}
			wp_enqueue_style('theme-style', get_template_directory_uri().'/style.css', $theme_version);
		}

	}

}

return new ThemeSettings();