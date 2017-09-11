<?php 

	function afl_members_find () { 
		echo afl_eps_page_header();
		afl_content_wrapper_begin();
		member_find_table();
		$affiliates_table = new Eps_find_members_data_table();
	?>
		 
		
			<div class="wrap">
			<h1>
				<!-- <?php _e( 'Affiliates', 'affiliate-wp' ); ?> -->
				<!-- <a href="<?php echo esc_url( add_query_arg( array( 'affwp_notice' => false, 'action' => 'add_affiliate' ) ) ); ?>" class="page-title-action"><?php _e( 'Add New', 'affiliate-wp' ); ?></a>
				<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'affiliate-wp-reports', 'tab' => 'affiliates' ) ) ); ?>" class="page-title-action"><?php _ex( 'Reports', 'affiliates', 'affiliate-wp' ); ?></a> -->
			</h1>
			<?php

			/**
			 * Manage Members pf eps-affiliates
			 *
			 * Use this hook to add content to this section of AffiliateWP.
			 */
				do_action( 'eps_affiliates_page_top' );

				?>
				<form id="eps-affiliates-filter" method="get" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<?php $affiliates_table->search_box( __( 'Search', 'eps-affiliates' ), 'eps-affiliates' ); ?>

					<input type="hidden" name="page" value="affiliate-eps-find-members" />

					<?php //$affiliates_table->views() ?>
					<?php $affiliates_table->prepare_items() ?>
					<?php $affiliates_table->display() ?>
				</form>
				<?php
				/**
				 * Fires at the bottom of the admin affiliates page.
				 *
				 * Use this hook to add content to this section of AffiliateWP.
				 */
				do_action( 'eps_affiliates_page_bottom' );
				?>
			</div>
	<?php 
		afl_content_wrapper_end();

	}
 function member_find_table () {
 	$pagination = new CI_Pagination;

		$config['total_rows'] =  count(_get_members());
		$config['base_url'] 	= '?page=affiliate-eps-recent-log-messages';
		$config['per_page'] 	= 50;

		
		$index = !empty($_GET['page_count']) ? $_GET['page_count'] : 0;
		$data  = _get_members($index, $config['per_page']);

		$pagination->initialize($config);
		$links = $pagination->create_links();

		$table = array();
		$table['#links']  = $links;
		$table['#name'] 			= '';
		$table['#title'] 			= '';
		$table['#prefix'] 		= '';
		$table['#suffix'] 		= '';
		$table['#attributes'] = array(
						'class' => array(
								'table',
								'table-bordered',
								'my-table-center',
							)
						);

		$table['#header'] = array(
			__('#'),
			__('Status'),
			__('Type'),
			__('Date'),
			__('Message'),
			__('Variables')		
		);
		$rows = array();
		$table['#rows'] = $rows;

	

		echo apply_filters('afl_render_table',$table);
 }
  function _get_members ( $index = 0, $limit = '' ) {

  }