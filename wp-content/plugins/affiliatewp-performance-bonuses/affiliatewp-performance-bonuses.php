<?php
/**
 * Plugin Name: AffiliateWP Performance Bonuses
 * Plugin URI: http://propluginmarketplace.com/plugins/affiliatewp-performance-bonuses
 * Description: Boost your sales by rewarding your affiliates with special bonuses for meeting the performance goals that you’ve set.
 * Author: Christian Freeman
 * Author URI: http://propluginmarketplace.com
 * Version: 1.0.3.1
 * Text Domain: affiliatewp-performance-bonuses
 * Domain Path: languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class AFFWP_PB_License {

	static function init() {

		if ( ! class_exists( 'AFFWP_PB_License_Menu' ) ) {
			require_once( plugin_dir_path( __FILE__ ) . 'am-license-menu.php' );
			
			AFFWP_PB_License_Menu::instance( __FILE__, 'AffiliateWP Performance Bonuses', '1.0.3.1', 'plugin', 'http://propluginmarketplace.com' );
		}
	}

}
AFFWP_PB_License::init();

if ( ! class_exists( 'AffiliateWP_Performance_Bonuses' ) ) {

	final class AffiliateWP_Performance_Bonuses {

		/**
		 * Plugin instance.
		 *
		 * @see instance()
		 * @type object
		 */
		private static $instance;

		/**
		 * URL to this plugin's directory.
		 *
		 * @type string
		 */
		public static  $plugin_dir;
		public static  $plugin_url;
		private static $version;

		/**
		 * The settings instance variable
		 *
		 * @var AffiliateWP_MLM_Settings
		 * @since 1.0
		 */
		public $settings;

		/**
		 * Class Properties
		 *
		 * @var AffiliateWP_Performance_Bonuses_Emails
		 * @since 1.0.3
		 */
		// public $emails;

		/**
		 * Main AffiliateWP_Performance_Bonuses Instance
		 *
		 * Insures that only one instance of AffiliateWP_Performance_Bonuses exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @return The one true AffiliateWP_Performance_Bonuses
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_Performance_Bonuses ) ) {
				
				self::$instance = new AffiliateWP_Performance_Bonuses;
				self::$version  = '1.0.3.1';

				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->setup_objects();
				self::$instance->hooks();
				// self::$instance->load_textdomain();
				// self::$instance->emails = new AffiliateWP_Performance_Bonuses_Emails;

			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-performance-bonuses' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-performance-bonuses' ), '1.0' );
		}

		/**
		 * Constructor Function
		 *
		 * @since 1.0
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Reset the instance of the class
		 *
		 * @since 1.0
		 * @access public
		 * @static
		 */
		public static function reset() {
			self::$instance = null;
		}
		
		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function setup_constants() {
			// Plugin version
			if ( ! defined( 'AFFWP_PB_VERSION' ) ) {
				define( 'AFFWP_PB_VERSION', self::$version );
			}

			// Plugin Folder Path
			if ( ! defined( 'AFFWP_PB_PLUGIN_DIR' ) ) {
				define( 'AFFWP_PB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'AFFWP_PB_PLUGIN_URL' ) ) {
				define( 'AFFWP_PB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'AFFWP_PB_PLUGIN_FILE' ) ) {
				define( 'AFFWP_PB_PLUGIN_FILE', __FILE__ );
			}
			
			// API URL
			if ( ! defined( 'AFFWP_PB_API_URL' ) ) {
				define( 'AFFWP_PB_API_URL', 'http://propluginmarketplace.com' );
			}
			
			// Software Title
			if ( ! defined( 'AFFWP_PB_SOFTWARE_TITLE' ) ) {
				define( 'AFFWP_PB_SOFTWARE_TITLE', 'AffiliateWP Performance Bonuses' );
			}
		}

		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {

			require_once AFFWP_PB_PLUGIN_DIR . 'includes/admin/class-settings.php';

			require_once AFFWP_PB_PLUGIN_DIR . 'includes/functions.php';
			require_once AFFWP_PB_PLUGIN_DIR . 'includes/scripts.php';
			require_once AFFWP_PB_PLUGIN_DIR . 'includes/actions.php';
			require_once AFFWP_PB_PLUGIN_DIR . 'includes/compatibility.php';
			
			// require_once AFFWP_PB_PLUGIN_DIR . 'includes/class-emails.php';
			
		}

		/**
		 * Setup all objects
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function setup_objects() {
			self::$instance->settings = new AffiliateWP_Performance_Bonuses_Settings;
			// self::$instance->emails = new AffiliateWP_Performance_Bonuses_Emails;
		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function hooks() {
		
			// plugin meta
			add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), null, 2 );
			
			// Add bonuses tab
			add_action( 'affwp_affiliate_dashboard_tabs', array( $this, 'add_bonuses_tab' ), 10, 2 );
			
			// Add template folder to hold the bonuses tab content
			add_filter( 'affwp_template_paths', array( $this, 'get_theme_template_paths' ) );

			// Add to the tabs list for 1.8.1 (fails silently if the hook doesn't exist).
			add_filter( 'affwp_affiliate_area_tabs', function( $tabs ) {
				return array_merge( $tabs, array( 'bonuses' ) );
			} );
		}
		
		/**
		 * Modify plugin metalinks
		 *
		 * @access      public
		 * @since       1.0
		 * @param       array $links The current links array
		 * @param       string $file A specific plugin table entry
		 * @return      array $links The modified links array
		 */
		public function plugin_meta( $links, $file ) {
		    if ( $file == plugin_basename( __FILE__ ) ) {
		        $plugins_link = array(
		            '<a title="' . __( 'Get more add-ons for AffiliateWP', 'affiliatewp-performance-bonuses' ) . '" href="http://propluginmarketplace.com/product-category/add-ons/affiliatewp/" target="_blank">' . __( 'Get add-ons', 'affiliatewp-performance-bonuses' ) . '</a>'
		        );

		        $links = array_merge( $links, $plugins_link );
		    }

		    return $links;
		}

		/**
		 * Whether or not we're on the bonuses tab of the dashboard
		 *
		 * @since 1.0
		 *
		 * @return boolean
		 */
		public function is_bonuses_tab() {
			if ( isset( $_GET['tab']) && 'bonuses' == $_GET['tab'] ) {
				return (bool) true;
			}
	
			return (bool) false;
		}

		/**
		 * Add bonuses tab
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function add_bonuses_tab( $affiliate_id, $active_tab ) {

			?>
            <li class="affwp-affiliate-dashboard-tab<?php echo $active_tab == 'bonuses' ? ' active' : ''; ?>">
                <a href="<?php echo esc_url( add_query_arg( 'tab', 'bonuses' ) ); ?>"><?php _e( 'Bonuses', 'affiliate-wp' ); ?></a>
            </li>
		<?php	
		}
	
		/**
		 * Add template folder to hold the sub affiliates tab content
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function get_theme_template_paths( $file_paths ) {
			$file_paths[91] = plugin_dir_path( __FILE__ ) . '/templates';
	
			return $file_paths;
		}

		/**
		 * Get an affiliate's bonus referrals 
		 *
		 * @since  1.0
		 */
		public function get_bonus_referrals( $args = array() ) {

			$referral_type = 'performance_bonus';
		
			$defaults = array(
				'number'       => -1,
				'offset'       => 0,
				'referrals_id' => 0,
				'affiliate_id' => affwp_get_affiliate_id(),
				'context'      => '',
				'status'       => array( 'paid', 'unpaid', 'rejected' )
			);

			$args  = wp_parse_args( $args, $defaults );

			// Get the affiliate's referrals
			$referrals = affiliate_wp()->referrals->get_referrals(
				array(
					'number'       => $args['number'],
					'offset'       => $args['offset'],
					'referrals_id' => $args['referrals_id'],
					'affiliate_id' => $args['affiliate_id'],
					'context'      => $args['context'],
					'status'       => $args['status']
				)
			);

			// Only show referrals by type
			if ( $referrals ) {
				foreach ( $referrals as $key => $referral ) {
				
					$bonus_order = $referral->custom == $referral_type ? $referral->custom : '';
		
					if ( ! $bonus_order ) {
						unset( $referrals[$key] );
					}
		
				}
		
				return $referrals;
			}

		}

		/**
		 * Count bonus referrals
		 *
		 * @since  1.0
		 */
		public function count_bonus_referrals() {
			return count( $this->get_bonus_referrals() );
		}

	}

	/**
	 * The main function responsible for returning the one true AffiliateWP_Performance_Bonuses
	 * Instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $affiliatewp_performance_bonuses = affiliatewp_performance_bonuses(); ?>
	 *
	 * @since 1.0
	 * @return object The one true AffiliateWP_Performance_Bonuses Instance
	 */
	function affiliatewp_performance_bonuses() {

	    if ( ! class_exists( 'Affiliate_WP' ) ) {
	    	
	        if ( ! class_exists( 'AffiliateWP_Activation' ) ) {
	            require_once 'includes/class-activation.php';
	        }

	        $activation = new AffiliateWP_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
	        $activation = $activation->run();
	    } else {
	        return AffiliateWP_Performance_Bonuses::instance();
	    }
	}
	add_action( 'plugins_loaded', 'affiliatewp_performance_bonuses', 100 );

}