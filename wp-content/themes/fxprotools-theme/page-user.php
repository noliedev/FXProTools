<?php get_header(); ?>
<?php  
foreach($_POST as $user_key => $user_value)
{
	update_usermeta( $_GET['id'], $user_key,  $user_value );
}
?>test
<?php print_r( get_the_author_meta('track_user_history', get_current_user_id()) ); ?>
	<?php get_template_part('inc/templates/nav-marketing'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Your Contact</h1>
					<p>Check Below for your available contact</p>
				</div>
				<div class="panel panel-default fx-contact-panel">
					<div class="panel-body">
						<div class="media">
							<div class="media-left">
								<img src="<?php echo get_avatar_url($_GET['id']); ?>" class="media-object">
							</div>
							<div class="media-body">
								<div class="info">
									<h4 class="media-heading text-normal">
										<?php  
											if(get_the_author_meta('first_name', $_GET['id'])){
												echo get_the_author_meta('first_name', $_GET['id']) . ' ' . get_the_author_meta('last_name', $_GET['id']);
											}else{
												echo get_the_author_meta('user_login', $_GET['id']);
											}
										?>
									</h4>
									<ul class="info-list">
										<li><i class="fa fa-envelope-o"></i> <?php echo get_the_author_meta('email', $_GET['id']); ?></li>
										<li><i class="fa fa-mobile"></i> <?php echo get_the_author_meta('billing_phone', $_GET['id']); ?></li>
										<li><i class="fa fa-home"></i> <?php echo get_the_author_meta('billing_city', $_GET['id']); ?>, <?php echo get_the_author_meta('billing_state', $_GET['id']); ?></li>
									</ul>
									<p>IP Address: 192.168.8.1</p>
								</div>
								<div class="action">
									<div>
										<i class="fa fa-inbox block"></i>
										<a href="#">Send Message</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="fx-tabs-vertical marketing-contacts">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#a" data-toggle="tab">Contact Card</a></li>
								<li><a href="#b" data-toggle="tab">Edit Contact</a></li>
								<li><a href="#c" data-toggle="tab">Purchases</a></li>
								<li><a href="#d" data-toggle="tab">Memberships</a></li>
								<li><a href="#e" data-toggle="tab">Genealogy</a></li>
								<li><a href="#f" data-toggle="tab">Recent Activity</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade in active" id="a">
									<form action="<?php echo get_the_permalink(); ?>" method="POST" class="<?php echo ($_GET['action'] == 'edit' ? 'form-edit' : ''); ?>">
										<div class="row">
											<div class="col-md-6 m-b-lg">
												<p class="text-bold text-center">General Information</p>
												<ul class="list-info list-info-fields">
													<li><span>First Name:</span> <?php echo get_the_author_meta('first_name', $_GET['id']) ?></li>
													<li><span>Last Name:</span> <?php echo get_the_author_meta('last_name', $_GET['id']); ?></li>
													<li><span>Website:</span> <?php echo get_the_author_meta('website', $_GET['id']) ?></li>
													<li><span>Facebook:</span> <?php echo get_the_author_meta('facebook', $_GET['id']); ?></li>
													<li><span>Twitter:</span> <?php echo get_the_author_meta('twitter', $_GET['id']); ?></li>
													<li><span>Google Plus:</span> <?php echo get_the_author_meta('googleplus', $_GET['id']); ?></li>
												</ul>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-6">
												<p class="text-bold text-center">Billing Information</p>
												<ul class="list-info list-info-fields">
													<li><span>Business Name:</span> <?php echo get_the_author_meta('billing_company', $_GET['id']) ?></li>
													<li><span>House # & Street Name:</span> <?php echo get_the_author_meta('billing_address_1', $_GET['id']) ?></li>
													<li><span>Apt.,suite,unit,etc.:</span> <?php echo get_the_author_meta('billing_address_2', $_GET['id']) ?></li>
													<li><span>City:</span> <?php echo get_the_author_meta('billing_city', $_GET['id']) ?></li>
													<li><span>State:</span> <?php echo get_the_author_meta('billing_state', $_GET['id']) ?></li>
													<li><span>Zip Code:</span> <?php echo get_the_author_meta('billing_postcode', $_GET['id']) ?></li>
												</ul>
											</div>
											<div class="col-md-6">
												<p class="text-bold text-center">Shipping Information</p>
												<ul class="list-info list-info-fields">
													<li><span>Business Name:</span> <?php echo get_the_author_meta('shipping_company', $_GET['id']) ?></li>
													<li><span>House # & Street Name:</span> <?php echo get_the_author_meta('shipping_address_1', $_GET['id']) ?></li>
													<li><span>Apt.,suite,unit,etc.:</span> <?php echo get_the_author_meta('shipping_address_2', $_GET['id']) ?></li>
													<li><span>City:</span> <?php echo get_the_author_meta('shipping_city', $_GET['id']) ?></li>
													<li><span>State:</span> <?php echo get_the_author_meta('shipping_state', $_GET['id']) ?></li>
													<li><span>Zip Code:</span> <?php echo get_the_author_meta('shipping_postcode', $_GET['id']) ?></li>
												</ul>
											</div>
										</div>
									</form>
								</div>
								<div class="tab-pane fade" id="b">
									<form action="<?php echo get_the_permalink(); ?>/?id=<?php echo $_GET['id']; ?>" method="POST" class="form-edit">
										<div class="row">
											<div class="col-md-6 m-b-lg">
												<p class="text-bold text-center">General Information</p>
												<ul class="list-info list-info-fields">
													<li><span>First Name:</span> <input type="text" name="first_name" id="first_name" value="<?php echo get_the_author_meta('first_name', $_GET['id']) ?>" /></li>
													<li><span>Last Name:</span> <input type="text" name="last_name" id="last_name" value="<?php echo get_the_author_meta('last_name', $_GET['id']) ?>" /></li>
													<li><span>Website:</span> <input type="text" name="website" id="website" value="<?php echo get_the_author_meta('website', $_GET['id']) ?>" /></li>
													<li><span>Facebook:</span> <input type="text" name="facebook" id="facebook" value="<?php echo get_the_author_meta('facebook', $_GET['id']) ?>" /></li>
													<li><span>Twitter:</span> <input type="text" name="twitter" id="twitter" value="<?php echo get_the_author_meta('twitter', $_GET['id']) ?>" /></li>
													<li><span>Google Plus:</span> <input type="text" name="googleplus" id="googleplus" value="<?php echo get_the_author_meta('googleplus', $_GET['id']) ?>" /></li>
												</ul>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-6">
												<p class="text-bold text-center">Billing Information</p>
												<ul class="list-info list-info-fields">
													<li><span>Business Name:</span> <input type="text" name="billing_company" id="billing_company" value="<?php echo get_the_author_meta('billing_company', $_GET['id']) ?>" /></li>
													<li><span>House # & Street Name:</span> <input type="text" name="billing_address_1" id="billing_address_1" value="<?php echo get_the_author_meta('billing_address_1', $_GET['id']) ?>" /></li>
													<li><span>Apt.,suite,unit,etc.:</span> <input type="text" name="billing_address_2" id="billing_address_2" value="<?php echo get_the_author_meta('billing_address_2', $_GET['id']) ?>" /></li>
													<li><span>City:</span> <input type="text" name="billing_city" id="billing_city" value="<?php echo get_the_author_meta('billing_city', $_GET['id']) ?>" /></li>
													<li><span>State:</span> <input type="text" name="billing_state" id="billing_state" value="<?php echo get_the_author_meta('billing_state', $_GET['id']) ?>" /></li>
													<li><span>Zip Code:</span> <input type="text" name="billing_postcode" id="billing_postcode" value="<?php echo get_the_author_meta('billing_postcode', $_GET['id']) ?>" /></li>
												</ul>
											</div>
											<div class="col-md-6">
												<p class="text-bold text-center">Shipping Information</p>
												<ul class="list-info list-info-fields">
													<li><span>Business Name:</span> <input type="text" name="shipping_company" id="shipping_company" value="<?php echo get_the_author_meta('shipping_company', $_GET['id']) ?>" /></li>
													<li><span>House # & Street Name:</span> <input type="text" name="shipping_address_1" id="shipping_address_1" value="<?php echo get_the_author_meta('shipping_address_1', $_GET['id']) ?>" /></li>
													<li><span>Apt.,suite,unit,etc.:</span> <input type="text" name="shipping_address_2" id="shipping_address_2" value="<?php echo get_the_author_meta('shipping_address_2', $_GET['id']) ?>" /></li>
													<li><span>City:</span> <input type="text" name="shipping_city" id="shipping_city" value="<?php echo get_the_author_meta('shipping_city', $_GET['id']) ?>" /></li>
													<li><span>State:</span> <input type="text" name="shipping_state" id="shipping_state" value="<?php echo get_the_author_meta('shipping_state', $_GET['id']) ?>" /></li>
													<li><span>Zip Code:</span> <input type="text" name="shipping_postcode" id="shipping_postcode" value="<?php echo get_the_author_meta('shipping_postcode', $_GET['id']) ?>" /></li>
												</ul>
											</div>
										</div>
										<div class="btn-holder btn-right m-t-lg">
											<button type="submit" class="btn btn-default">Save</button>
										</div>
									</form>
								</div>
								<div class="tab-pane fade" id="c">
									<table id="table-purchases" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th>Time</th>
												<th>Product</th>
												<th>Amount</th>
												<th>Status</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php 
												$customer_orders = get_customer_orders($_GET['id']);
												foreach($customer_orders as $customer_order){
												?>
													<tr>
														<td><?php echo time_elapsed_string($customer_order->post_date); ?></td>
														<?php  
															$order = new WC_Order( $customer_order->ID );

													        $product_list = '';
													        $order_item = $order->get_items();

													        foreach( $order_item as $product ) {
													    ?>
													    		<td><?php echo $product['name'] ?></td>
																<td>$<?php echo $product['total']; ?></td>
													    <?php
													        }
														?>
														
														<td><?php echo wc_get_order_statuses()[$customer_order->post_status]; ?></td>
														<td class="text-center"><a href="#" class="btn btn-default view-purchase-details">Details</a></td>
													</tr>
											<?php
												}
											?>
										</tbody>
									</table>
									<div id="view-purchase-details">
										<div class="row">
											<div class="col-md-12">
												<p class="text-bold">Purchase Date</p>
												<ul class="list-info">
													<li><span>Created:</span> 2017-07-16 2:00PM</li>
													
												</ul>
											</div>
											<div class="col-md-12">
												<div class="fx-separator"></div>
											</div>
											<div class="col-md-12">
												<p class="text-bold">Purchase Details</p>
												<ul class="list-info">
													<li><span>Name:</span> Basic Product</li>
													<li><span>Unit Price:</span> $140</li>
												</ul>
											</div>
											<div class="col-md-12">
												<div class="fx-separator"></div>
											</div>
											<div class="col-md-12">
												<p class="text-bold">Comission</p>
												<ul class="list-info">
													<li>You Earned: <strong>$10.00</strong> for this product purchase</li>
												</ul>
											</div>
										</div>
										<a href="#" id="close-purchase-details" class="btn btn-default m-t-md">Back to List of Purchases</a>
									</div>
								</div>
								<div class="tab-pane fade" id="d">
									<p class="text-bold">Memberships Section</p>
								</div>
								<div class="tab-pane fade" id="e">
									<p class="text-bold">Genealogy Section</p>
								</div>
								<div class="tab-pane fade" id="f">
									<table id="table-recent-activity" class="table table-bordered">
										<thead>
											<tr>
												<th>Page Name</th>
												<th>Page Url</th>
												<th>Time</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Page Title Goes Here</td>
												<td>http://urlgoeshere.com?ref=username</td>
												<td>1 Day Ago</td>
											</tr>
											<tr>
												<td>Page Title Goes Here</td>
												<td>http://urlgoeshere.com?ref=username</td>
												<td>2 Days Ago</td>
											</tr>
											<tr>
												<td>Page Title Goes Here</td>
												<td>http://urlgoeshere.com?ref=username</td>
												<td>15 Day Ago</td>
											</tr>
											<tr>
												<td>Page Title Goes Here</td>
												<td>http://urlgoeshere.com?ref=username</td>
												<td>1 Month Ago</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>