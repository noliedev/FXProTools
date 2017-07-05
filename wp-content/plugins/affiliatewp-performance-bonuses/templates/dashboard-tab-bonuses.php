<?php 

	$affiliate_id = affwp_get_affiliate_id();
	$bonuses = get_active_bonuses();
	$bonus_count = count( $bonuses );
	
	// Run if AffiliateWP Ranks is Active
	if ( class_exists( 'AffiliateWP_Ranks' ) ) {
		$ranks = get_ranks();
	}
	
?>

<div id="affwp-affiliate-dashboard-bonuses" class="affwp-tab-content">

	<?php 	if ( $bonuses ) : ?>
            <h4><?php printf( __( 'Bonuses %s', 'affiliatewp-performance-bonuses' ), $sub_affiliate_count ); ?></h4>
            
			<div class="affwp-pb-bonuses-wrapper">
            
            <?php foreach ( $bonuses as $bonus ) : ?>
            
            <?php
				$bonus_name = $bonus['title'];
				$bonus_type = $bonus['type'];
				$bonus_requirement = $bonus['requirement'];
				$pre_bonus = get_bonus_by_id( $bonus['pre_bonus'] );
				$pre_bonus_title = $pre_bonus[0]['title'] ? $pre_bonus[0]['title'] : 'None';
				$bonus_amount = affwp_currency_filter( $bonus['amount'] );
				
				if( $bonus_type == 'referral' ){
				
					$affiliate_progress = affwp_pb_get_affiliate_referral_count( $affiliate_id );
					$bonus_qualification = 'To earn this bonus, you must <strong>Refer ' . $bonus_requirement . ' Sales</strong>';
					
				} elseif( ( $bonus_type == 'earnings' ) || ( $bonus_type == 'earnings_percentage' ) ){
				
					$affiliate_progress = affwp_pb_get_affiliate_earnings_count( $affiliate_id );
					$bonus_qualification = 'To earn this bonus, you must <strong>Earn ' . affwp_currency_filter( $bonus_requirement ) . ' in Commissions</strong>';	
					
				} elseif( $bonus_type == 'sub_affiliate' ){
				
					$sub_affiliates = affwp_mlm_filter_by_status( affwp_mlm_get_direct_sub_affiliates( ( $affiliate_id ) ) );
					$affiliate_progress = count( $sub_affiliates );
					$bonus_qualification = 'To earn this bonus, you must <strong>Directly Refer ' . $bonus_requirement . ' Sub Affiliates</strong>';	
					
				} elseif( $bonus_type == 'rank_promotion' ){
									
					$rank_id = $bonus['requirement'];
					$rank = get_rank_by_id( $rank_id );
					$rank_name = $rank[0]['name'];
					$rank_type = $rank[0]['type'];					
					$affiliate_progress = 0;
										
					if( $rank_type == 'referral' ){
						$affiliate_progress = affwp_pb_get_affiliate_referral_count( $affiliate_id );
					}
					
					if( $rank_type == 'earnings' ){
						$affiliate_progress = affwp_pb_get_affiliate_earnings_count( $affiliate_id );
					}
					
					if ( $rank_type == 'sub_affiliate' ){
						$sub_affiliates = affwp_mlm_filter_by_status( affwp_mlm_get_direct_sub_affiliates( ( $affiliate_id ) ) );
						$affiliate_progress = count( $sub_affiliates );
					}
						
					$bonus_requirement = $rank[0]['requirement'];
					$bonus_qualification = 'To earn this bonus, you must <strong>Earn the ' . $rank_name . ' Rank</strong>';	
					
				}
				
				if( $affiliate_progress > $bonus_requirement )
					$affiliate_progress = $bonus_requirement;
				
				$bonus_earned = affwp_pb_get_bonus_log( $affiliate_id, $bonus['pre_bonus'] );
				
				// Check for prerequisite bonus
				if( !empty( $bonus['pre_bonus'] ) && empty( $bonus_earned ) )
					$affiliate_progress = 0;
				
				if ( empty( $bonus_type ) || ! is_numeric( $bonus_requirement ) ) {
					
					// No progress to be made
					$bonus_progress = 0; 
					$bonus_progress_txt = 'None';
					
				} else{
					// Calculate progress. We need a percentage with 1 decimal
					$bonus_progress = number_format( ( ( $affiliate_progress / $bonus_requirement ) * 100 ), 0 );
					$bonus_progress_txt = $affiliate_progress .' of '. $bonus_requirement;	
				}
			?>
            
                <div class="affwp-pb-bonus-wrapper">
               		<div class="affwp-pb-bonus-amount">
                    	<p><?php echo $bonus_amount; ?></p>
                    </div>
                	<div class="affwp-pb-bonus-title"><?php echo $bonus_name; ?></div>
                    <div class="affwp-pb-bonus-progress">       
                        <progress max="100" value="<?php echo $bonus_progress; ?>" class="affwp-pb-bonus-progress-bar"></progress>
                        <span class="affwp-pb-bonus-progress-text"><?php echo $bonus_progress_txt; ?></span>
                    </div>
                    <div class="affwp-pb-bonus-info">
                    	<p><?php echo $bonus_qualification; ?></p>
                    </div>
                    <div class="affwp-pb-bonus-pre-bonus">Prerequisite</div>
                    <div class="affwp-pb-bonus-pre-bonus-title">
                        <?php echo $pre_bonus_title; ?>
                    </div>
                </div>
            
            <?php endforeach; ?>
            
			</div>
            
            <?php else : ?>
            
            <h4><?php _e( 'There are no active bonuses yet.', 'affiliatewp-performance-bonuses' ); ?></h4>
            
        <?php endif; ?>

	<h4><?php _e( 'Bonus Commissions', 'affiliatewp-performance-bonuses' ); ?></h4>
	
	<?php
		
		$per_page  = 30;
		$page      = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

		// gGt the affiliate's bonus referrals
		$bonus_referrals = affiliatewp_performance_bonuses()->get_bonus_referrals(
			array(
				'number' => $per_page,
				'offset' => $per_page * ( $page - 1 ),
			)
		);
	?>

	<?php if ( $bonus_referrals ) : ?>
	
	<table id="affwp-affiliate-dashboard-referrals" class="affwp-table">
		<thead>
			<tr>
				<th class="referral-amount"><?php _e( 'Amount', 'affiliate-wp' ); ?></th>
				<th class="referral-description"><?php _e( 'Bonus', 'affiliate-wp' ); ?></th>
				<th class="referral-status"><?php _e( 'Status', 'affiliate-wp' ); ?></th>
				<th class="referral-date"><?php _e( 'Date', 'affiliate-wp' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php if ( $bonus_referrals ) { ?>

				<?php foreach ( $bonus_referrals as $referral ) : ?>
					<tr>
						<td class="referral-amount"><?php echo affwp_currency_filter( affwp_format_amount( $referral->amount ) ); ?></td>
						<td class="referral-description"><?php echo $referral->description; ?></td>
						<td class="referral-status <?php echo $referral->status; ?>"><?php echo $referral->status; ?></td>
						<td class="referral-date"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $referral->date ) ); ?></td>
					</tr>
				<?php endforeach; ?>

			<?php } else{ ?>

				<tr>
					<td colspan="4"><?php _e( 'You have not earned any performance bonuses yet.', 'affiliatewp-performance-bonuses' ); ?></td>
				</tr>

			<?php } ?>
		</tbody>
	</table>
    
	<?php endif; ?>
    
	<div class="affwp-pagination">
		<?php
			echo paginate_links( array(
				'current'      => $page,
				'total'        => ceil( affiliatewp_performance_bonuses()->count_bonus_referrals() / $per_page ),
				'add_fragment' => '#affwp-affiliate-dashboard-bonuses',
				'add_args'     => array(
				'tab'          => 'bonuses'
				)
			) );
		?>
	</div>

</div>	