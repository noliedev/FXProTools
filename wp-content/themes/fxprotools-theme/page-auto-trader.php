<?php
	$product_id = 49; //professional package
	$courses = get_courses_by_product_id( get_the_ID() ); 
?>
<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-products'); ?>

	<?php if ( WC_Subscriptions_Manager::user_has_subscription( '', $product_id, 'active') ) : ?>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<ul class="fx-list-courses">
						<?php if( $courses ) : ?>
							<?php $count = 0; foreach($courses as $post): setup_postdata($post); $count++; ?>
								<?php get_template_part('inc/templates/product/list-course'); ?>
							<?php endforeach;?>
							<?php wp_reset_query(); ?>
						<?php endif;?>
					</ul>
					<br/>
					<div class="fx-header-title">
						<h1><?php the_title();?></h1>
						<p><?php echo rwmb_meta('subtitle');?></p>
					</div>
				</div>
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div role="tabpanel">
								<ul class="nav nav-tabs fx-tabs courses" role="tablist">
									<li role="presentation" class="active">
										<a href="#one" aria-controls="one" role="tab" data-toggle="tab">Demo Account</a>
									</li>
									<li role="presentation">
										<a href="#two" aria-controls="two" role="tab" data-toggle="tab">Live Account</a>
									</li>
								</ul>
								<br/>
								<div class="tab-content">
									<div role="tabpanel" class="tab-pane active" id="one">
										<div class="fx-video-container"></div>
										<br/>
										<a href="#" class="btn btn-lg btn-danger block">Download Software</a>
									</div>
									<div role="tabpanel" class="tab-pane" id="two">
										<div class="fx-video-container"></div>
										<br/>
										<div class="panel panel-default">
											<div class="panel-body centered-item" style="height: 400px;">
												<i class="fa fa-users" style="font-size: 70px;"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php else: ?>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="fx-header-title">
						<h1>Oh Man! It Looks like You Don't Have<br/> Access To The Auto Trader</h1>
						<p>Lorem ipsum dolor sit amet</p>
					</div>
				</div>
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body" style="padding: 50px;">
							<div class="fx-video-container"></div>
							<br/>
							<a href="#" class="btn btn-lg btn-danger block">Upgrade Account</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>


<?php get_footer(); ?>