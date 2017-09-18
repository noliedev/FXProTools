<?php
	$product_id = 50; 
	$wp_query->query_vars['_prod_id'] = $product_id;
	$_product = wc_get_product( $product_id );
	$wp_query->query_vars['_prod_data'] = $_product;
?>

<?php get_header(); ?>

	<?php if ( WC_Subscriptions_Manager::user_has_subscription( '', $product_id, 'active') || current_user_can('administrator')  ) : ?>
		<?php get_template_part('inc/templates/nav-products'); ?>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="fx-header-title">
						<h1>Coaching / Webinars</h1>
						<p>Check Below For Your Coaching Webinars</p>
					</div>
				</div>
				<div class="col-md-12">
					<a href="#" class="btn btn-danger inline-block">Schedule Coaching</a>
					<br/><br/>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<h2 class="text-center">1 On 1 Coaching With(Teacher Name)</h2><br/>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-5 col-md-offset-2">
									<img src="http://via.placeholder.com/500x400?text=Image" class="img-responsive">
								</div>
								<div class="col-md-3">
									<h3>$5,000</h3>
									<p>
										<strong>Bookable From:</strong><br/>
										July 1, 2017 to July 31, 2017
									</p>
									<p>
										<strong>Duration:</strong><br/>
										3 days
									</p>
									<div class="form-group">
										<p><strong>Start Date:</strong></p>
										<select class="form-control">
											<option>2017-07-20</option>
											<option>2017-07-20</option>
											<option>2017-07-20</option>
											<option>2017-07-20</option>
										</select>
									</div>
									<div class="form-group">
										<p><strong>Start Time:</strong></p>
										<select class="form-control">
											<option>2017-07-20</option>
											<option>2017-07-20</option>
											<option>2017-07-20</option>
											<option>2017-07-20</option>
										</select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
									<br/>
									<a href="#" class="btn btn-lg btn-danger block">Book Appointment</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php else: ?>
		<?php get_template_part('inc/templates/no-access'); ?>
	<?php endif; ?>

<?php get_footer(); ?>