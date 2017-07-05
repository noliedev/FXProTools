<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class AffiliateWP_Performance_Bonuses_Settings {
	
	public function __construct() {

		add_filter( 'affwp_settings_tabs', array( $this, 'settings_tab' ) );
		add_filter( 'affwp_settings', array( $this, 'settings' ), 10, 1 );
		add_action( 'admin_init', array( $this, 'bonus_settings' ) );
		add_filter( 'affwp_settings_rates_sanitize', array( $this, 'sanitize_bonuses' ) );

	}

	/**
	 * Register the Bonuses Settings Tab
	 *
	 * @since 1.0
	 */
	public function settings_tab( $tabs ) {
		$tabs['bonuses'] = __( 'Bonuses', 'affiliatewp-performance-bonuses' );
		return $tabs;
	}
	
	/**
	 * Register Bonus Settings
	 *
	 * @since 1.0
	 */
	public function settings( $settings ) {
	
		// Bonus Settings
		$bonus_settings = array(
			
			'bonuses' => apply_filters( 'affwp_settings_pb',
				array(
					'affwp_pb_bonuses_header' => array(
						'name' => '<strong>' . __( 'Bonus Settings', 'affiliatewp-performance-bonuses' ) . '</strong>',
						'type' => 'header',
					),
					'affwp_pb_default_status' => array(
						'name' => __( 'Default Bonus Status', 'affiliatewp-performance-bonuses' ),
						'desc' => '<p class="description">' . __( 'Should bonuses be marked as unpaid or pending by default?', 'affiliatewp-performance-bonuses' ) . '</p>',
						'type' => 'select',
						'options' => array(
							'unpaid' => __( 'Unpaid', 'affiliatewp-performance-bonuses' ),
							'pending' => __( 'Pending', 'affiliatewp-performance-bonuses' )
						),
						'std' => 'unpaid'
					),
					'affwp_pb_referrals_basis' => array(
						'name' => __( 'Referral Calculation', 'affiliatewp-performance-bonuses' ),
						'desc' => '<p class="description">' . __( 'How should referrals be calculated?', 'affiliatewp-performance-bonuses' ) . '</p>',
						'type' => 'select',
						'options' => array(
							'paid' => __( 'Paid', 'affiliatewp-performance-bonuses' ),
							'unpaid' => __( 'Unpaid', 'affiliatewp-performance-bonuses' ),
							'total' => __( 'Total (Paid + Unpaid)', 'affiliatewp-performance-bonuses' )
						),
						'std' => 'paid'
					),
					'affwp_pb_earnings_basis' => array(
						'name' => __( 'Earnings Calculation', 'affiliatewp-performance-bonuses' ),
						'desc' => '<p class="description">' . __( 'How should earnings be calculated?', 'affiliatewp-performance-bonuses' ) . '</p>',
						'type' => 'select',
						'options' => array(
							'paid' => __( 'Paid', 'affiliatewp-performance-bonuses' ),
							'unpaid' => __( 'Unpaid', 'affiliatewp-performance-bonuses' ),
							'total' => __( 'Total (Paid + Unpaid)', 'affiliatewp-performance-bonuses' )
						),
						'std' => 'paid'
					),
					
					
				)
			)
		);

		$settings = array_merge( $settings, $bonus_settings );
		
		return $settings;
	}

	/**
	 * Bonus Settings
	 *
	 * @since 1.0
	 */
	public function bonus_settings() {

		add_settings_field(
			'affwp_settings[bonuses]',
			__( 'Bonuses', 'affiliatewp-performance-bonuses' ), 
			array( $this, 'bonuses_table' ),
			'affwp_settings_bonuses',
			'affwp_settings_bonuses'
		);
	}

	/**
	 * Get ALL Bonuses
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function get_bonuses() {
		$bonuses = affiliate_wp()->settings->get( 'bonuses', array() );
		return apply_filters( 'affwp_pb_bonuses', array_values( $bonuses ) );
	}

	public function sanitize_bonuses( $input ) {

		if( ! empty( $input['bonuses'] ) ) {

			if( ! is_array( $input['bonuses'] ) ) {
				$input['bonuses'] = array();
			}

			foreach( $input['bonuses'] as $key => $bonus ) {
				
				// Require the Amount, and Requirement fields To DO - Add Error Message "You must enter a Bonus Amount, and Requirement"
				if( empty( $bonus['amount'] ) || empty( $bonus['requirement'] ) ) {
				
					unset( $input['bonuses'][ $key ] );
				
				} else {

					$input['bonuses'][ $key ]['title'] = sanitize_text_field( $bonus['title'] );
					$input['bonuses'][ $key ]['amount'] = sanitize_text_field( $bonus['amount'] );
					$input['bonuses'][ $key ]['requirement'] = absint( $bonus['requirement'] ); 

				}

			}

		}

		return $input;
	}

	public function bonuses_table() {

		$bonuses = $this->get_bonuses();
		$count = count( $bonuses );
									
?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.affwp_pb_remove_bonus').on('click', function(e) {
				e.preventDefault();
				$(this).parent().parent().remove();
			});

			$('#affwp_pb_new_bonus').on('click', function(e) {

				e.preventDefault();

				var row = $('#affiliatewp-pb-bonuses tbody tr:last');

				clone = row.clone();

				var count = $('#affiliatewp-pb-bonuses tbody tr').length;

				clone.find( 'td input' ).val( '' );
				clone.find( 'input' ).each(function() {
					var name = $( this ).attr( 'name' );

					name = name.replace( /\[(\d+)\]/, '[' + parseInt( count ) + ']');

					$( this ).attr( 'name', name ).attr( 'id', name );
				});

				clone.insertAfter( row );

			});
		});
		</script>
		<style type="text/css">
		#affiliatewp-pb-bonuses th { padding-left: 10px; }
		.affwp_pb_remove_bonus { margin: 8px 0 0 0; cursor: pointer; width: 10px; height: 10px; display: inline-block; text-indent: -9999px; overflow: hidden; }
		.affwp_pb_remove_bonus:active, .affwp_pb_remove_bonus:hover { background-position: -10px 0!important }
		</style>
		<form id="affiliatewp-pb-bonuses-form">
			<table id="affiliatewp-pb-bonuses" class="form-table wp-list-table widefat fixed posts">
				<thead>
					<tr>
                    	<th style="width: 7.5%; text-align: center;"><?php _e( 'Active', 'affiliatewp-performance-bonuses' ); ?></th>
                    	<th style="width: 7.5%; text-align: center;"><?php _e( 'ID', 'affiliatewp-performance-bonuses' ); ?></th>
						<th style="width: 20%; text-align: center;"><?php _e( 'Title', 'affiliatewp-performance-bonuses' ); ?></th>
						<th style="width: 15%; text-align: center;"><?php _e( 'Type', 'affiliatewp-performance-bonuses' ); ?></th>
						<th style="width: 15%; text-align: center;"><?php _e( 'Requirement', 'affiliatewp-performance-bonuses' ); ?></th>
						<th style="width: 15%; text-align: center;"><?php _e( 'Prerequisite', 'affiliatewp-performance-bonuses' ); ?></th>
						<th style="width: 15%; text-align: center;"><?php _e( 'Amount', 'affiliatewp-performance-bonuses' ); ?></th>
						<th style="width:5%;"></th>
					</tr>
				</thead>
				<tbody>
                	<?php if( $bonuses ) :

							foreach( $bonuses as $key => $bonus ) : 
								// Default Bonus Type
								$enabled = (bool) $bonus['active'];
								$type = $bonus['type'];
								$pre_bonus = $bonus['pre_bonus'];
								$bonus_id = !empty( $bonus['id'] ) ? $bonus['id'] : affwp_pb_new_bonus_id();
							?>
							<tr>
                            
                                <td>
                                    <input name="affwp_settings[bonuses][<?php echo $key; ?>][active]" type="checkbox" value="1" <?php if( $enabled == 1 ) { ?>checked="checked"<?php } ?> />
                                </td>                  
                                <td>
                                    <input name="affwp_settings[bonuses][<?php echo $key; ?>][id]" class="small-text" type="text" value="<?php echo esc_attr( $bonus_id ); ?>" <?php if( !empty( $bonus['id'] ) ) { ?>readonly="1"<?php } ?> />
                                </td>          
								<td>
									<input name="affwp_settings[bonuses][<?php echo $key; ?>][title]" type="text" value="<?php echo esc_attr( $bonus['title'] ); ?>" style="width: 100%;" />
								</td>
                                <td>
                                    <select name="affwp_settings[bonuses][<?php echo $key; ?>][type]">
                                        <?php foreach( affwp_pb_get_bonus_types() as $type_key => $type_option ) { ?>
                                            <option value="<?php echo esc_attr( $type_key ); ?>"<?php selected( $type_key, $type ); ?>><?php echo esc_html( $type_option ); ?></option>
                                        <?php }; ?>
                                    </select>
                                </td>
								<td>
									<input name="affwp_settings[bonuses][<?php echo $key; ?>][requirement]" type="text" value="<?php echo esc_attr( $bonus['requirement'] ); ?>" style="width: 100%;" />
								</td>                                
                                <td>
                                    <select name="affwp_settings[bonuses][<?php echo $key; ?>][pre_bonus]">
                                        <option value=""></option>
                                        <?php foreach( get_active_bonuses() as $bonus_key => $bonus_option ) { ?>
                                            <option value="<?php echo esc_attr( $bonus_option['id'] ); ?>"<?php selected( $bonus_option['id'], $pre_bonus ); ?>><?php echo esc_html( $bonus_option['title'] ); ?></option>
                                        <?php }; ?>
                                    </select>
                                </td>     
								<td>
									<input name="affwp_settings[bonuses][<?php echo $key; ?>][amount]" type="text" value="<?php echo esc_attr( $bonus['amount'] ); ?>" style="width: 100%;" />
								</td>
								<td>
									<a href="#" class="affwp_pb_remove_bonus" style="background: url(<?php echo admin_url('/images/xit.gif'); ?>) no-repeat;">&times;</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="6" style="text-align: center;"><?php _e( 'No bonuses created yet', 'affiliatewp-performance-bonuses' ); ?></td>
						</tr>
					<?php endif; ?>
                    <?php if( empty( $bonuses ) ) : ?>
                    
							<tr>
                                <td>
                                    <input name="affwp_settings[bonuses][<?php echo $count; ?>][active]" type="checkbox" value="1" />
                                </td>
                                <td>
                                    <input name="affwp_settings[bonuses][<?php echo $count; ?>][id]" class="small-text" type="text" value="<?php echo esc_attr( $bonus_id ); ?>" />
                                </td>    
								<td>
									<input name="affwp_settings[bonuses][<?php echo $count; ?>][title]" type="text" value="<?php echo esc_attr( $bonus['title'] ); ?>" style="width: 100%;" />
								</td>
                                <td>
                                    <select name="affwp_settings[bonuses][<?php echo $count; ?>][type]">
                                        <?php foreach( affwp_pb_get_bonus_types() as $type_key => $type_option ) { ?>
                                            <option value="<?php echo esc_attr( $type_key ); ?>"<?php selected( $type_key, $type ); ?>><?php echo esc_html( $type_option ); ?></option>
                                        <?php }; ?>
                                    </select>
                                </td>
								<td>
									<input name="affwp_settings[bonuses][<?php echo $count; ?>][requirement]" type="text" value="<?php echo esc_attr( $bonus['requirement'] ); ?>" style="width: 100%;" />
								</td>
                                <td>
                                    <select name="affwp_settings[bonuses][<?php echo $count; ?>][pre_bonus]">
                                        <option value=""></option>
										<?php foreach( get_active_bonuses() as $bonus_key => $bonus_option ) { ?>
                                            <option value="<?php echo esc_attr( $bonus_option['id'] ); ?>"<?php selected( $bonus_option['id'], $pre_bonus ); ?>><?php echo esc_html( $bonus_option['title'] ); ?></option>
                                        <?php }; ?>
                                    </select>
                                </td>
								<td>
									<input name="affwp_settings[bonuses][<?php echo $count; ?>][amount]" type="text" value="<?php echo esc_attr( $bonus['amount'] ); ?>" style="width: 100%;" />
								</td>
								<td>
									<a href="#" class="affwp_pb_remove_bonus" style="background: url(<?php echo admin_url('/images/xit.gif'); ?>) no-repeat;">&times;</a>
								</td>
							</tr>                        
                        
					<?php endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="8">
							<button id="affwp_pb_new_bonus" name="affwp_pb_new_bonus" class="button" style="width: 100%; height: 110%;"><?php _e( 'Add New Bonus', 'affiliatewp-performance-bonuses' ); ?></button>
						</th>
					</tr>
				</tfoot>
			</table>
		</form>
<?php
	}
}