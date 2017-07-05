
<div id="affwp-affiliate-dashboard-sub-affiliates" class="affwp-tab-content">

	<?php do_action( 'affwp_dashboard_sub_affiliates_top', affwp_get_affiliate_id() ); ?>

	<?php show_sub_affiliates(); ?>
    
    <?php do_action( 'affwp_dashboard_sub_affiliates_bottom', affwp_get_affiliate_id() ); ?>

</div>	