<?php
/**
 * --------------
 * Admin Settings
 * --------------
 * Admin related settings
 */

if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('AdminSettings')){

	class AdminSettings {
		
		public function __construct()
		{
			add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
			add_action('login_enqueue_scripts',  array($this, 'enqueue_admin_assets'));
			add_filter('login_headerurl', array($this, 'login_logo_link'));
			add_action('admin_menu',  array($this, 'remove_admin_menus'), 99);
			add_action('admin_init', array($this, 'remove_dashboard_meta'));
			add_action('wp_ajax_nopriv_lms_lesson_complete', array($this, 'lms_lesson_complete') );
			add_action('wp_ajax_lms_lesson_complete', array($this, 'lms_lesson_complete') );
			add_filter('rwmb_meta_boxes',  array($this, 'initialize_meta_boxes'));
			add_action('init', array($this, 'register_funnel_post_type'));
			add_action('init', array($this, 'register_webinar_post_type'));
			// add_action('admin_menu', array($this, 'page_anet'));
		}

		// Admin assets
		public function enqueue_admin_assets()
		{
			global $theme_version;
			// wp_enqueue_style('admin-style', get_stylesheet_directory_uri().'/assets/css/admin.css', $theme_version);
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
			remove_menu_page('tools.php');                  // Tools
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

		// Custom page - Authorize.net CIM and Subscriptions Manager
		// public function page_anet()
		// {
		// 	$page_settings = add_menu_page(
		// 		'ANET - CISM',
		// 		'ANET - CISM',
		// 		'manage_options',
		// 		'anet-management',
		// 		'page_content',
		// 		'dashicons-exerpt-view',
		// 		9
		// 	);
		// 	add_action('load-' . $page_settings, 'page_assets');

		// 	function page_assets(){	
		// 		// CSS
		// 		wp_enqueue_style('css-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), '', 'all');
		// 		wp_enqueue_style('css-datatable', 'https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.css', array(), '', 'all');
		// 		wp_enqueue_style('css-custom', get_stylesheet_directory_uri() . '/assets/css/admin-anet.css', array(), '', 'all');
		// 		// JS
		// 		wp_enqueue_script('js-jquery', 'https://code.jquery.com/jquery-2.2.4.min.js', FALSE, '', TRUE);
		// 		wp_enqueue_script('js-chosen', 'https://cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js', FALSE, '', TRUE);
		// 		wp_enqueue_script('js-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', FALSE, '', TRUE);
		// 		wp_enqueue_script('js-datatable', 'https://cdn.datatables.net/v/bs/dt-1.10.13/datatables.min.js', FALSE, '', TRUE);
		// 		wp_enqueue_script('js-admin', get_stylesheet_directory_uri() . '/assets/js/admin-anet.js', FALSE, '', TRUE);
				
		// 		// Declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		// 		wp_localize_script('js-admin', 'wpAjax', array(
		// 			'ajaxUrl'   => admin_url('admin-ajax.php'),
		// 			'ajaxNonce' => wp_create_nonce('wp_nonce')
		// 		));
					
		// 	}

		// 	function page_content(){
		// 		get_template_part('inc/templates/template-admin-anet');
		// 	}
		// }

		public function lms_lesson_complete() {
			$user_id = get_current_user_id();
			$lesson_id = $_POST['lesson_id'];

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
				echo learndash_is_lesson_complete( $user_id , $lesson_id );
			}
			die();
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
						'placeholder' => 'Short Description',
						'name' => esc_html__( 'Short Description', 'fxprotools' ),
					),
				),
			);

			$meta_boxes[] = array(
				'id' => 'capture_page_fields',
				'title' => esc_html__( 'Capture Page Fields', 'fxprotools' ),
				'post_types' => array( 'fx_funnel' ),
				'context' => 'advanced',
				'priority' => 'high',
				'autosave' => false,
				'fields' => array(
					array(
						'id' => $prefix . 'capture_page_title',
						'type' => 'text',
						'name' => esc_html__( 'Capture Page Title', 'fxprotools' ),
						'size' => 80,
					),
					array(
						'id' => $prefix . 'capture_sub_title',
						'type' => 'text',
						'name' => esc_html__( 'Capture Sub Title', 'fxprotools' ),
						'size' => 80,
					),
					array(
						'id' => $prefix . 'capture_page_url',
						'type' => 'text',
						'name' => esc_html__( 'Funnel URL', 'fxprotools' ),
						'size' => 80,
					),
					array(
						'id' => $prefix . 'capture_page_thumbnail',
						'type' => 'image_advanced',
						'name' => esc_html__( 'Cature Page Thumbnail', 'fxprotools' ),
						'force_delete' => false,
						'max_file_uploads' => '1',
					),
				),
			);

			$meta_boxes[] = array(
				'id' => 'landing_page_fields',
				'title' => esc_html__( 'Landing Page Fields', 'fxprotools' ),
				'post_types' => array( 'fx_funnel' ),
				'context' => 'advanced',
				'priority' => 'high',
				'autosave' => false,
				'fields' => array(
					array(
						'id' => $prefix . 'landing_page_title',
						'type' => 'text',
						'name' => esc_html__( 'Landing Page Title', 'fxprotools' ),
						'size' => 80,
					),
					array(
						'id' => $prefix . 'landing_sub_title',
						'type' => 'text',
						'name' => esc_html__( 'Landing Page Sub Title', 'fxprotools' ),
						'size' => 80,
					),
					array(
						'id' => $prefix . 'landing_page_url',
						'type' => 'text',
						'name' => esc_html__( 'Funnel URL', 'fxprotools' ),
						'size' => 80,
					),
					array(
						'id' => $prefix . 'landing_page_thumbnail',
						'type' => 'image_advanced',
						'name' => esc_html__( 'Landing Page Thumbnail', 'fxprotools' ),
						'force_delete' => false,
						'max_file_uploads' => '1',
					),
				),
			);

			$meta_boxes[] = array(
				'id' => 'webinar_custom_fields',
				'title' => esc_html__( 'Webinar Custom Fields', 'fxprotools' ),
				'post_types' => array( 'fx_webinar' ),
				'context' => 'advanced',
				'priority' => 'high',
				'autosave' => false,
				'fields' => array(
					array(
						'id' => $prefix . 'webinar_topic',
						'type' => 'wysiwyg',
						'name' => esc_html__( 'Topic', 'fxprotools' ),
					),
					array(
						'id' => $prefix . 'webinar_start_date',
						'type' => 'date',
						'name' => esc_html__( 'Start Date', 'fxprotools' ),
					),
					array(
						'id' => $prefix . 'webinar_start_time',
						'type' => 'time',
						'name' => esc_html__( 'Start Time', 'fxprotools' ),
					),
					array(
						'id' => $prefix . 'webinar_meeting_link',
						'type' => 'text',
						'name' => esc_html__( 'Meeting Link', 'fxprotools' ),
					),
				),
			);

			return $meta_boxes;
		}

		public function register_funnel_post_type()
		{
			$labels = array(
				'name'                  => _x( 'Funnels', 'Post Type General Name', 'fxprotools' ),
				'singular_name'         => _x( 'Funnel', 'Post Type Singular Name', 'fxprotools' ),
				'menu_name'             => __( 'Funnels', 'fxprotools' ),
				'name_admin_bar'        => __( 'Funnel', 'fxprotools' ),
				'archives'              => __( 'Funnel Archives', 'fxprotools' ),
				'attributes'            => __( 'Funnel Attributes', 'fxprotools' ),
				'parent_item_colon'     => __( 'Parent Funnel:', 'fxprotools' ),
				'all_items'             => __( 'All Funnels', 'fxprotools' ),
				'add_new_item'          => __( 'Add New Funnel', 'fxprotools' ),
				'add_new'               => __( 'Add New', 'fxprotools' ),
				'new_item'              => __( 'New Funnel', 'fxprotools' ),
				'edit_item'             => __( 'Edit Funnel', 'fxprotools' ),
				'update_item'           => __( 'Update Funnel', 'fxprotools' ),
				'view_item'             => __( 'View Funnel', 'fxprotools' ),
				'view_items'            => __( 'View Funnels', 'fxprotools' ),
				'search_items'          => __( 'Search Funnel', 'fxprotools' ),
				'not_found'             => __( 'Not found', 'fxprotools' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'fxprotools' ),
				'featured_image'        => __( 'Featured Image', 'fxprotools' ),
				'set_featured_image'    => __( 'Set featured image', 'fxprotools' ),
				'remove_featured_image' => __( 'Remove featured image', 'fxprotools' ),
				'use_featured_image'    => __( 'Use as featured image', 'fxprotools' ),
				'insert_into_item'      => __( 'Insert into item', 'fxprotools' ),
				'uploaded_to_this_item' => __( 'Uploaded to this funnel', 'fxprotools' ),
				'items_list'            => __( 'Funnels list', 'fxprotools' ),
				'items_list_navigation' => __( 'Funnels list navigation', 'fxprotools' ),
				'filter_items_list'     => __( 'Filter Funnels list', 'fxprotools' ),
			);
			$args = array(
				'label'                 => __( 'Funnel', 'fxprotools' ),
				'description'           => __( 'Post Type Description', 'fxprotools' ),
				'labels'                => $labels,
				'supports'              => array( 'title', 'thumbnail', 'page-attributes'),
				'taxonomies'			=> array( 'category' ),
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-desktop',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,		
				'exclude_from_search'   => true,
				'publicly_queryable'    => true,
				'capability_type'       => 'page',
			);
			register_post_type( 'fx_funnel', $args );
		}

		public function register_webinar_post_type()
		{
			$labels = array(
				'name'                  => _x( 'Webinars', 'Post Type General Name', 'fxprotools' ),
				'singular_name'         => _x( 'Webinar', 'Post Type Singular Name', 'fxprotools' ),
				'menu_name'             => __( 'Webinars', 'fxprotools' ),
				'name_admin_bar'        => __( 'Webinar', 'fxprotools' ),
				'archives'              => __( 'Webinar Archives', 'fxprotools' ),
				'attributes'            => __( 'Webinar Attributes', 'fxprotools' ),
				'parent_item_colon'     => __( 'Parent Webinar:', 'fxprotools' ),
				'all_items'             => __( 'All Webinars', 'fxprotools' ),
				'add_new_item'          => __( 'Add New Webinar', 'fxprotools' ),
				'add_new'               => __( 'Add New', 'fxprotools' ),
				'new_item'              => __( 'New Webinar', 'fxprotools' ),
				'edit_item'             => __( 'Edit Webinar', 'fxprotools' ),
				'update_item'           => __( 'Update Webinar', 'fxprotools' ),
				'view_item'             => __( 'View Webinar', 'fxprotools' ),
				'view_items'            => __( 'View Webinars', 'fxprotools' ),
				'search_items'          => __( 'Search Webinar', 'fxprotools' ),
				'not_found'             => __( 'Not found', 'fxprotools' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'fxprotools' ),
				'featured_image'        => __( 'Featured Image', 'fxprotools' ),
				'set_featured_image'    => __( 'Set featured image', 'fxprotools' ),
				'remove_featured_image' => __( 'Remove featured image', 'fxprotools' ),
				'use_featured_image'    => __( 'Use as featured image', 'fxprotools' ),
				'insert_into_item'      => __( 'Insert into item', 'fxprotools' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Webinar', 'fxprotools' ),
				'items_list'            => __( 'Webinars list', 'fxprotools' ),
				'items_list_navigation' => __( 'Webinars list navigation', 'fxprotools' ),
				'filter_items_list'     => __( 'Filter Webinars list', 'fxprotools' ),
			);
			$args = array(
				'label'                 => __( 'Webinar', 'fxprotools' ),
				'description'           => __( 'Post Type Description', 'fxprotools' ),
				'labels'                => $labels,
				'supports'              => array( 'title', 'thumbnail', 'page-attributes'),
				'taxonomies'			=> array( 'category' ),
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-desktop',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,		
				'exclude_from_search'   => true,
				'publicly_queryable'    => true,
				'capability_type'       => 'page',
			);
			register_post_type( 'fx_webinar', $args );
		}


	}

}

return new AdminSettings();