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
			add_filter('rwmb_meta_boxes',  array($this, 'initialize_meta_boxes'));
			add_action('init', array($this, 'course_category_rewrite'));
			add_action('template_redirect',  array($this, 'course_category_template'));
			add_filter('query_vars',  array($this,'course_category_vars'));
			add_action('init', array($this, 'register_funnel_post_type'));
			add_action('user_register', array($this, 'register_user_checklist'));
			add_action('user_register', array($this, 'send_email_verification'));
			add_action('user_register', array($this, 'register_affiliate'));
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
			wp_enqueue_style('style-boostrap-datepicker', get_template_directory_uri().'/vendors/boostrap-datepicker-1.7.1/css/bootstrap-datepicker.min.css', $theme_version);
			// Styles - Custom
			wp_enqueue_style('theme-style', get_template_directory_uri().'/assets/css/theme.css', $theme_version);
			// Scripts - Core
			wp_enqueue_script('jquery', get_stylesheet_directory_uri().'/vendors/jquery-3.2.1/jquery-3.2.1.min.js', $theme_version);
			wp_enqueue_script('script-bootstrap', get_stylesheet_directory_uri().'/vendors/bootstrap-3.3.7/js/bootstrap.min.js', $theme_version);
			wp_enqueue_script('script-bootstrap-datepicker', get_stylesheet_directory_uri().'/vendors/boostrap-datepicker-1.7.1/js/bootstrap-datepicker.min.js', $theme_version);
			wp_enqueue_script('script-clipboardjs', get_stylesheet_directory_uri().'/vendors/clipboard-js-1.7.1/js/clipboard.min.js', $theme_version);
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

			$meta_boxes[] = array(
				'id' => 'capture_page_fields',
				'title' => esc_html__( 'Capture Page Fields', 'fxprotools' ),
				'post_types' => array( 'fx_funnel' ),
				'context' => 'advanced',
				'priority' => 'high',
				'autosave' => false,
				'fields' => array(
					array(
						'id' => $prefix . 'capture_page_url',
						'type' => 'text',
						'name' => esc_html__( 'Capture Page URL', 'fxprotools' ),
						'size' => 100,
					),
					array(
						'id' => $prefix . 'capture_page_title',
						'type' => 'text',
						'name' => esc_html__( 'Capture Page Title', 'fxprotools' ),
						'size' => 100,
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
						'id' => $prefix . 'landing_page_url',
						'type' => 'text',
						'name' => esc_html__( 'Landing Page URL', 'fxprotools' ),
						'size' => 100,
					),
					array(
						'id' => $prefix . 'landing_page_title',
						'type' => 'text',
						'name' => esc_html__( 'Landing Page Title', 'fxprotools' ),
						'size' => 100,
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
			return $meta_boxes;
		}

		public function course_category_rewrite()
		{
			add_rewrite_rule('course-category/([^/]*)/?','index.php?category_slug=$matches[1]&course_category=1','top');
		}

		public function course_category_vars( $vars )
		{
		    $vars[] = 'course_category';
		    $vars[] = 'category_slug';
		    return $vars;
		}

		public function course_category_template()
		{
		    if ( get_query_var( 'category_slug' ) ) {
		        add_filter( 'template_include', function() {
		            return get_template_directory() . '/sfwd-course-category.php';
		        });
		    }
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

		public function register_user_checklist($user_id)
		{
			$checklist = array(
				'verified_email' 	=> false, 
				'verified_profile'	=> false,
				'scheduled_webinar'	=> false,
				'accessed_products' => false,
				'got_shirt'			=> false,
				'shared_video'		=> false,
				'referred_friend'	=> false,
			);
			add_user_meta( $user_id, '_onboard_checklist', $checklist);
		}

		public function send_email_verification($user_id)
		{
			$user = get_user_by('id', $user_id);
			$secret = "fxprotools-";
			$hash = MD5( $secret . $user->data->user_email);
			$to =  $user->data->user_email;
			$subject = 'Please verify your Email Address';
			$message = "Click <a href='" . home_url() . '/verify-email/?code=' . $hash . "' target='_blank'>here</a> to verify your email address.";
			$headers = array('Content-Type: text/html; charset=UTF-8');
			wp_mail( $to, $subject, $message, $headers );
		}

		public function register_affiliate($user_id)
		{
			$data = array('user_id' => $user_id, 'notes' => 'affiliate added via fxprotools');
			$affiliate_id = affwp_add_affiliate($data);
		}

		public function add_new_user_fields( $user ) { ?>
			<h3>General Information</h3>
			<table class="form-table">
				<tr>
					<th><label for="c_bday">Birthday</label></th>
					<td>
						<input type="date" name="c_bday" id="c_bday" value="<?php echo esc_attr( get_the_author_meta( 'c_bday', $user->ID ) ); ?>" class="regular-text" /><br />
					</td>
				</tr>
				<?php if(get_the_author_meta( 'c_bday', $user->ID )){ ?>
				<tr>
					<th><label for="c_age">Age</label></th>
					<td>
						<input type="text" disabled="disabled" name="c_age" id="c_age" value="<?php echo date_diff(date_create(get_the_author_meta( 'c_bday', $user->ID )), date_create('today'))->y; ?>" class="regular-text" /><br />
					</td>
				</tr>
				<?php } ?>
				<tr>
					<th><label for="c_gender">Gender</label></th>
					<td>
						<select name="c_gender" id="c_gender">
							<option value="Male" <?php if(get_the_author_meta( 'c_gender', $user->ID ) == "Male"){echo 'selected';} ?>>Male</option>
							<option value="Female" <?php if(get_the_author_meta( 'c_gender', $user->ID ) == "Female"){echo 'selected';} ?>>Female</option>
						</select>
					</td>
				</tr>
			</table>
		<?php }

		function save_new_user_fields($user_id) {
			update_usermeta( $user_id, 'c_bday', $_POST['c_bday'] );
			update_usermeta( $user_id, 'c_gender', $_POST['c_gender'] );
		}

		function add_c_bday_checkout( $checkout_fields = array() ) {
		    $checkout_fields['order']['c_bday'] = array(
		        'type'          => 'text',
		        'class'         => array('form-row form-row-wide'),
		        'label'         => __('Date of Birth'),
		        'required'      => true, 
		        );
		    return $checkout_fields;
		}

		function add_c_gender_checkout( $checkout_fields = array() ) {
		    $checkout_fields['order']['c_gender'] = array(
		        'type'          => 'select',
				'required'	=> true,
				'class'         => array('form-row', 'form-row-wide'),
				'label'         => 'Gender',
				'options'	=> array(
					'Male'	=> 'Male',
					'Female'	=> 'Female'
				)
		    );
		    return $checkout_fields;
		}

		function add_checkout_script() {
		    echo '<script> jQuery(function() { jQuery("#c_bday").attr("type","date"); }) </script>';
		}

		function save_custom_checkout_fields( $customer_id, $posted ) {
		    if (isset($posted['c_bday'])) {
		        $dob = sanitize_text_field( $posted['c_bday'] );
		        update_user_meta( $customer_id, 'c_bday', $dob);
		    }
		    if (isset($posted['c_gender'])) {
		        $dob = sanitize_text_field( $posted['c_gender'] );
		        update_user_meta( $customer_id, 'c_gender', $dob);
		    }
		}

	}
}

return new ThemeSettings();