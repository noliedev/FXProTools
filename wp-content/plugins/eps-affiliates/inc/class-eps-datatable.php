<?php

/*
 * -----------------------------------------------------
 * List table view
 * -----------------------------------------------------
*/
 	if( ! class_exists( 'WP_List_Table' ) ) {
	    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}
	class Eps_data_table extends WP_List_Table {
	/**
	 * Default number of items to show per page
	 *
	 * @var string
	 * @since 1.0
	*/
		public $per_page = 30;
  /**
	 * Total number of affiliates found
	 *
	 * @var int
	 * @since 1.0
	*/
		public $total_count;
	/**
	 * Number of active affiliates found
	 *
	 * @var string
	 * @since 1.0
	*/
		public $active_count;
	/**
	 *  Number of inactive affiliates found
	 *
	 * @var string
	 * @since 1.0
	*/
		public $inactive_count;
	/**
	 * Number of Blocked affiliates found
	 *
	 * @var string
	 * @since 1.0
	*/
		public $blocked_count;
	/**
	 * EPS-Databse class object
	 *
	 * @var Object
	 * @since 1.0
	*/
		public $db_obj;

	/**
	 * Get things started
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @see WP_List_Table::__construct()
	 *
	 * @param array $args Optional. Arbitrary display and query arguments to pass through
	 *                    the list table. Default empty array.
	*/
		public function __construct( $args = array() ) {
			$args = wp_parse_args( $args, array(
				'singular' => 'affiliate',
				'plural'   => 'affiliates',
			) );

			parent::__construct( $args );

			$this->get_affiliates_counts();

			$this->db_obj = new Eps_db;

		}
	/**
	 * Retrieve the discount code counts
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	*/
		public function get_affiliates_counts() {

			$search = isset( $_GET['s'] ) ? $_GET['s'] : '';

			$this->active_count = 10;

			$this->inactive_count = 5;

			$this->blocked_count = 5;

			$this->total_count = $this->active_count + $this->inactive_count + $this->blocked_count;
		}

	/**
	 * Show the search field
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @param string $text Label for the search box
	 * @param string $input_id ID of the search box
	 *
	 * @return void
	 */
		public function search_box( $text, $input_id ) {
			if ( empty( $_REQUEST['s'] ) && !$this->has_items() )
				return;

			$input_id = $input_id . '-search-input';

			if ( ! empty( $_REQUEST['orderby'] ) )
				echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
			if ( ! empty( $_REQUEST['order'] ) )
				echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
			?>
			<p class="search-box">
				<label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
				<input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
				<?php submit_button( $text, 'button', false, false, array( 'ID' => 'search-submit' ) ); ?>
			</p>
		<?php
		}


	/**
	 * Retrieve the view types
	 *
	 * @access public
	 * @since 1.0
	 * @return array $views All the views available
	 */
		public function get_views() {
			$base           = 'manage-members';

			$current        = isset( $_GET['status'] ) ? $_GET['status'] : '';
			$total_count    = '&nbsp;<span class="count">(' . $this->total_count    . ')</span>';
			$active_count   = '&nbsp;<span class="count">(' . $this->active_count . ')</span>';
			$inactive_count = '&nbsp;<span class="count">(' . $this->inactive_count  . ')</span>';
			$pending_count  = '&nbsp;<span class="count">(' . $this->pending_count  . ')</span>';
			$rejected_count = '&nbsp;<span class="count">(' . $this->rejected_count  . ')</span>';

			$views = array(
				'all'		=> sprintf( '<a href="%s"%s>%s</a>', esc_url( remove_query_arg( 'status', $base ) ), $current === 'all' || $current == '' ? ' class="current"' : '', __('All', 'affiliate-wp') . $total_count ),
				'active'	=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'status', 'active', $base ) ), $current === 'active' ? ' class="current"' : '', __('Active', 'affiliate-wp') . $active_count ),
				'inactive'	=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'status', 'inactive', $base ) ), $current === 'inactive' ? ' class="current"' : '', __('Inactive', 'affiliate-wp') . $inactive_count ),
				'pending'	=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'status', 'pending', $base ) ), $current === 'pending' ? ' class="current"' : '', __('Pending', 'affiliate-wp') . $pending_count ),
				'rejected'	=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'status', 'rejected', $base ) ), $current === 'rejected' ? ' class="current"' : '', __('Rejected', 'affiliate-wp') . $rejected_count ),
			);

			return $views;
		}
	/**
	 * Retrieve the table columns
	 *
	 * @access public
	 * @since 1.0
	 * @return array $columns Array of all the list table columns
	 */
		function get_columns(){
		  $columns = array(
		  	'cb'        		=> '<input type="checkbox" />',
		  	'member'				=> __( 'Member', 'affiliate-eps' ),
		  	'parent'				=> __( 'Parent', 'affiliate-eps' ),
		  	'sponsor'				=> __( 'Sponsor', 'affiliate-eps' ),
		  	'role'					=> __( 'Roles(s)', 'affiliate-eps' ),
		  	'member_status'	=> __( 'Member Status', 'affiliate-eps' ),
		  	'Rank'					=> __( 'Rank', 'affiliate-eps' ),
		  	'registered_on'	=> __( 'Registered On', 'affiliate-eps' ),
		  	// 'edit_link'			=> __( 'Edit Link', 'affiliate-eps' ),
		  );
		  return apply_filters('affiliate_eps_member_data_table_colums',$columns);
		}
	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @param  The current affiliate object.
	 * @param string           $column_name The name of the column
	 * @return string The column value.
	 */

		function column_default( $item, $column_name ) {
		  switch( $column_name ) {
		    case 'booktitle':
		    case 'author':
		    case 'isbn':
		      return $item[ $column_name ];
		    default:
		      return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		  }
		}
	/**
	 * Retrieve the table's sortable columns
	 *
	 * @access public
	 * @since 1.0
	 * @return array Array of all the sortable columns
	 */
		public function get_sortable_columns() {
			return array(
				// 'member'        => array( 'member',        false ),
				// 'parent'        => array( 'parent',        false ),
				// 'sponsor'       => array( 'sponsor',       false ),
				// 'role' 					=> array( 'role', 				 false ),
				// 'member_status' => array( 'member_status', false ),
				// 'registered_on' => array( 'registered_on', false ),
			);
		}

		public function usort_reorder( $a, $b ) {
		  // If no sort, default to title
		  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'booktitle';
		  // If no order, default to asc
		  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		  // Determine sort order
		  $result = strcmp( $a[$orderby], $b[$orderby] );
		  // Send final sort direction to usort
		  return ( $order === 'asc' ) ? $result : -$result;
		}
	/**
	 * Get he column member value
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	*/
		public function column_member($item) {
		  $actions = array(
		            'edit'      => sprintf('<a href="?page=%s&action=%s&book=%s">Edit</a>',$_REQUEST['page'],'edit',$item->uid),
		            'delete'    => sprintf('<a href="?page=%s&action=%s&book=%s">Delete</a>',$_REQUEST['page'],'delete',$item->uid),
		        );
		  $text = $item->display_name.'<span class="label label-primary">'.$item->uid.'</span>';
		  return $text;
		}
	/**
	 * column parent
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	*/
	  public function column_parent($item) {

			  $node  = afl_genealogy_node($item->parent_uid);

				if (!empty($node) && !empty($node->display_name)) {
					$value = $node->display_name;
				} else {
					$value = 'unverified';
				}
			  /**
				 * Filters the parent column data for the affiliates list table.
				 *
				 * @param string           $value     Data shown in the Username column.
				 * @param \AffWP\Affiliate $affiliate The current affiliate object.
				 */
				return apply_filters( 'eps_affiliate_member_table_parent', $value, $item );
		}
	/**
	 * column Sponsor
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	*/
	  public function column_sponsor($item) {

			  $node  = afl_genealogy_node($item->referrer_uid);

				if (!empty($node) && !empty($node->display_name)) {
					$value = $node->display_name;
				} else {
					$value = 'unverified';
				}
			  /**
				 * Filters the parent column data for the affiliates list table.
				 *
				 * @param string           $value     Data shown in the Username column.
				 * @param \AffWP\Affiliate $affiliate The current affiliate object.
				 */
				return apply_filters( 'eps_affiliate_member_table_sponsor', $value, $item );
		}
	/**
	 * column role(s)
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	*/
	  public function column_role($item) {
				$roles = afl_user_roles($item->uid);
				$value = '';
				$value = implode( ',', array_map( 'strval', $roles ) );

			  /**
				 * Filters the parent column data for the affiliates list table.
				 *
				 * @param string           $value     Data shown in the Username column.
				 * @param \AffWP\Affiliate $affiliate The current affiliate object.
				 */
				return apply_filters( 'eps_affiliate_member_table_roles', $value, $item );
		}
	/**
	 * column Member_status
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	*/
	  public function column_member_status($item) {
				$statuses = list_extract_allowed_values(afl_variable_get('member_status'), 'list_text', '');
			  $value = $statuses[$item->status];
			  /**
				 * Filters the parent column data for the affiliates list table.
				 *
				 * @param string           $value     Data shown in the Username column.
				 * @param \AffWP\Affiliate $affiliate The current affiliate object.
				 */
				return apply_filters( 'eps_affiliate_member_table_member_status', $value, $item );
		}
	/**
	 * column member rank
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	*/
	  public function column_rank($item) {

			  $value = render_rank($item->member_rank);
			  /**
				 * Filters the parent column data for the affiliates list table.
				 *
				 * @param string           $value     Data shown in the Username column.
				 * @param \AffWP\Affiliate $affiliate The current affiliate object.
				 */
				return $value;
		}
	/**
	 * column Registered on
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	*/
	  public function column_registered_on($item) {
			  $value = date('Y-md-d', $item->created);
			  /**
				 * Filters the parent column data for the affiliates list table.
				 *
				 * @param string           $value     Data shown in the Username column.
				 * @param \AffWP\Affiliate $affiliate The current affiliate object.
				 */
				return apply_filters( 'eps_affiliate_member_table_member_registered_on', $value, $item );
		}
	/**
	 * column Edit Link
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	*/
	  // public function column_edit_link($item) {
    //
		// 	  $value = $item->display_name;
		// 	  /**
		// 		 * Filters the parent column data for the affiliates list table.
		// 		 *
		// 		 * @param string           $value     Data shown in the Username column.
		// 		 * @param \AffWP\Affiliate $affiliate The current affiliate object.
		// 		 */
		// 		return apply_filters( 'eps_affiliate_member_table_member_edit_link', $value, $item );
		// }
	/**
	 * Retrieve the bulk actions
	 *
	 * @access public
	 * @since 1.0
	 * @return array $actions Array of the bulk actions
	*/
		public function get_bulk_actions() {
			$actions = array(
				'accept'     => __( 'Accept', 'affiliate-wp' ),
				'reject'     => __( 'Reject', 'affiliate-wp' ),
				'activate'   => __( 'Activate', 'affiliate-wp' ),
				'deactivate' => __( 'Deactivate', 'affiliate-wp' ),
				'delete'     => __( 'Delete', 'affiliate-wp' )
			);

			/**
			 * Filters the bulk actions to return in the eps-affiliates list table.
			 *
			 * @param array $actions Bulk actions.
			 */
			return apply_filters( 'eps_affiliats_bulk_action', $actions );
		}

		function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="book[]" value="%s" />', 1
        );
    }


    /**
		 * Renders the message to be displayed when there are no affiliates.
		 *
		 * @access public
		 * @since  1.7.2
		 */
		function no_items() {
			_e( 'No affiliates found.', 'affiliate-wp' );
		}


	/**
	 * Process the bulk actions
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
		public function process_bulk_action() {

			if ( empty( $_REQUEST['_wpnonce'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-affiliates' ) && ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'affiliate-nonce' ) ) {
				return;
			}

			$ids = isset( $_GET['affiliate_id'] ) ? $_GET['affiliate_id'] : false;

			if ( ! is_array( $ids ) ) {
				$ids = array( $ids );
			}

			$ids = array_map( 'absint', $ids );

			if ( empty( $ids ) ) {
				return;
			}

			foreach ( $ids as $id ) {

				if ( 'accept' === $this->current_action() ) {
					// affwp_set_affiliate_status( $id, 'active' );
				}

				if ( 'reject' === $this->current_action() ) {
					// affwp_set_affiliate_status( $id, 'rejected' );
				}

				if ( 'activate' === $this->current_action() ) {
					// affwp_set_affiliate_status( $id, 'active' );
				}

				if ( 'deactivate' === $this->current_action() ) {
					// affwp_set_affiliate_status( $id, 'inactive' );
				}

				if ( 'delete' === $this->current_action() ) {
					// affwp_delete_affiliate( $id );
				}

				/**
				 * Fires after an affiliate bulk action is performed.
				 *
				 * The dynamic portion of the hook name, `$this->current_action()` refers
				 * to the current bulk action being performed.
				 *
				 * @since 2.0.2
				 *
				 * @param int $id The ID of the object.
				 */
				do_action( 'eps_affiliates_do_bulk_action' . $this->current_action(), $id );

			}

		}


		function prepare_items() {
			$per_page = 5;

		  $columns = $this->get_columns();

		  $hidden = array();

		  $sortable =	 $this->get_sortable_columns();

		  $this->_column_headers = array($columns, $hidden, $sortable);

		  $this->get_column_info();

			$this->process_bulk_action();



		  $this->items = $this->affiliate_data();

		  $current_page = $this->get_pagenum();

		  $total_items = count($this->example_data);

		}
	/**
	 * Retrieve all the data for all the Affiliates
	 *
	 * @access public
	 * @since 1.0
	 * @return array $affiliate_data Array of all the data for the Affiliates
	*/
		public function affiliate_data() {

			$page    = isset( $_GET['paged'] )    ? absint( $_GET['paged'] ) : 1;
			$status  = isset( $_GET['status'] )   ? $_GET['status']          : '';
			$search  = isset( $_GET['s'] )        ? $_GET['s']               : '';
			$order   = isset( $_GET['order'] )    ? $_GET['order']           : 'DESC';
			$orderby = isset( $_GET['orderby'] )  ? $_GET['orderby']         : 'affiliate_id';

			$per_page = $this->get_items_per_page( 'affwp_edit_affiliates_per_page', $this->per_page );

			$args = wp_parse_args( $this->query_args, array(
				'number'  => $per_page,
				'offset'  => $per_page * ( $page - 1 ),
				'status'  => $status,
				'search'  => $search,
				'orderby' => sanitize_text_field( $orderby ),
				'order'   => sanitize_text_field( $order )
			) );
			// $db_obj = new Eps_db;
			$affiliates = $this->db_obj->get_members( $args );

			// Retrieve the "current" total count for pagination purposes.
			$args['number']      = -1;
			// $this->current_count = $db_obj->get_members( $args , TRUE);

			return $affiliates;
		}
	}
