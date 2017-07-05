<?php
/**
 * Template file for displaying affiliate rank table
 *
 * @since 1.0
 */ 
?>

<div id="affwp-affiliate-dashboard-affiliate_rank" class="affwp-tab-content" style="padding-top: 20px;">

	<?php show_affiliate_rank_table( $affiliate_id ); ?>
    
	<?php do_action( 'affwp_affiliate_dashboard_after_affiliate_rank', affwp_get_affiliate_id() ); ?>

</div>