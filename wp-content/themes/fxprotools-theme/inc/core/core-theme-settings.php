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
			add_filter( 'rwmb_meta_boxes',  array($this,'initialize_meta_boxes') );
			add_action( 'init', array($this,'course_category_rewrite') );
			add_action( 'template_redirect',  array($this,'course_category_template') );
			add_filter( 'query_vars',  array($this,'course_category_vars') );
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products' );

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
			// Styles - Core
			wp_enqueue_style('style-bootstrap', get_template_directory_uri().'/vendors/bootstrap-3.3.7/css/bootstrap.min.css', $theme_version);
			wp_enqueue_style('style-fontawesome', get_template_directory_uri().'/vendors/font-awesome-4.7.0/css/font-awesome.min.css', $theme_version);
			// Styles - Custom
			wp_enqueue_style('theme-style', get_template_directory_uri().'/assets/css/theme.css', $theme_version);
			// Scripts - Core
			wp_enqueue_script('script-jquery', get_stylesheet_directory_uri().'/vendors/jquery-3.2.1/jquery-3.2.1.min.js', $theme_version);
			wp_enqueue_script('script-bootstrap', get_stylesheet_directory_uri().'/vendors/bootstrap-3.3.7/js/bootstrap.min.js', $theme_version);
			// Scripts - Custom
			// Include custom scripts here
		}

		public function initialize_meta_boxes( $meta_boxes ) {
			$prefix = '';

			$meta_boxes[] = array(
				'id' => 'course_custom_fields',
				'title' => esc_html__( 'Course Custom Fields', 'fxprotools' ),
				'post_types' => array( 'sfwd-courses' ),
				'context' => 'normal',
				'priority' => 'high',
				'autosave' => false,
				'fields' => array(
					array(
						'id' => $prefix . 'short_description',
						'type' => 'textarea',
						'name' => esc_html__( 'Short Description', 'fxprotools' ),
					),
					array(
						'id' => $prefix . 'subtitle',
						'type' => 'text',
						'name' => esc_html__( 'Subtitle', 'fxprotools' ),
					),
				),
			);

			$meta_boxes[] = array(
				'id' => 'product_custom_fields',
				'title' => esc_html__( 'Product Custom Fields', 'fxprotools' ),
				'post_types' => array( 'product' ),
				'context' => 'normal',
				'priority' => 'high',
				'autosave' => false,
				'fields' => array(
					array(
						'id' => $prefix . 'subtitle',
						'type' => 'text',
						'name' => esc_html__( 'Short Description', 'fxprotools' ),
					),
				),
			);

			return $meta_boxes;
		}

		public function course_category_rewrite(){
			add_rewrite_rule('course-category/([^/]*)/?','index.php?category_slug=$matches[1]&course_category=1','top');
		}

		public function course_category_vars( $vars ) {
		    $vars[] = 'course_category';
		    $vars[] = 'category_slug';
		    return $vars;
		}

		public function course_category_template(){
		    if ( get_query_var( 'category_slug' ) ) {
		        add_filter( 'template_include', function() {
		            return get_template_directory() . '/sfwd-course-category.php';
		        });
		    }
		}



	}

}

return new ThemeSettings();