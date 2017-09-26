<?php get_header(); ?>
<?php  
foreach($_POST as $user_key => $user_value)
{
	update_usermeta( $_GET['id'], $user_key,  $user_value );
}
?>
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
									<div class="user-cancellation">
										<div class="progress">
										  <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">STEP 3 of 3</div>
										</div>
										<h2 class="text-center;">WAIT! Final Step BEFORE Your Account Is Deleted!</h2>
										<p>This is a special one time offer! You may not see this offer available again if you close this page.</p>
										<p>You don't currently qualify for any downgraded plan option other then "Paused". </p>
										<div class="row">
											<div class="col-md-6">
												<h3>Pause Account - $9.99 Month</h3>
											</div>
											<div class="col-md-6">
												<a href="#" class="btn btn-danger btn-block btn-lg">Pause My Account - $9.99 / Month</a>
											</div>
										</div>
										<p>(If you pause, your pages will not display live, you won't be able to use the ClickFunnels App... but we'll keep your subdomain reserved and all your pages and funnels waiting so you can resume your account anytime.)</p>
										<div class="row">
											<div class="col-md-6">
												<h3>Or...Finalize Account Cancellation:</h3>
											</div>
											<div class="col-md-6">
												<button type="button" data-toggle="modal" data-target="#cancellation-modal" class="btn btn-danger btn-block btn-lg">Finalize Cancellation</button>
											</div>
										</div>
										<p><strong>IMPORTANT:</strong> If you cancel your account, please note that your username (USER_NAME) will be made available for someone else; any progress and access to pages you've created will be disabled; optins and leads will not be collected; and videos will not display if you added your own.</p>
									</div>
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
												$purchase_counter = 1;
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
														<td class="text-center"><a href="#" data-target="purchase-detail-item-<?php echo $purchase_counter; ?>" class="btn btn-default view-purchase-details">Details</a></td>
													</tr>

												<div id="purchase-detail-item-<?php echo $purchase_counter; ?>" class="row purchase-detail-item">
													<div class="col-md-12">
														<p class="text-bold">Purchase Date</p>
														<ul class="list-info">
															<li><span>Created:</span> <?php echo $customer_order->post_date; ?></li>
															
														</ul>
													</div>
													<div class="col-md-12">
														<div class="fx-separator"></div>
													</div>
													<div class="col-md-12">
														<p class="text-bold">Purchase Details</p>
														<ul class="list-info">
															<?php foreach( $order_item as $product ) { ?>
																<li><span>Name:</span> <?php echo $product['name'] ?></li>
																<li><span>Unit Price:</span> $<?php echo $product['total'] ?></li>
															<?php } ?>
														</ul>
														<p class="text-bold">Purchase Details</p>
														<p>The card on file for your account ends with 7576</p>
														<div class="row">
															<div class="col-md-6">
																<a href="#" class="btn btn-success btn-lg btn-block">Update Payment Information</a>
															</div>
															<div class="col-md-6">
																<a href="#" class="btn btn-success btn-lg btn-block">Choose Another Plan</a>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="fx-separator"></div>
													</div>
												</div>
											<?php
													$purchase_counter++;
												}
											?>
										</tbody>
									</table>
									<div id="view-purchase-details">
										<div class="purchase-details-info"></div>
										<div class="panel panel-default">
											<div class="clearfix">
												<div class="col-md-12">
													<h3 class="m-b-md">Cancel Purchase</h3>
													<div class="progress">
													  <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 33%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">STEP 1 of 3</div>
													</div>
													<p><a href="<?php echo get_option('home'); ?>/cancel-step-1" class="btn btn-danger btn-lg">Start Cancellation Process</a></p>
													<p><strong>IMPORTANT:</strong> If you cancel your account, please note that your subdomain (mastermindmedia) will be made available for someone else; any funnels and pages you've created will be disabled; optins and leads will not be collected; and videos will not play.</p>
												</div>
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

<!-- Modal -->
<div class="modal fade" id="cancellation-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">ARE YOU SURE?</h4>
      </div>
      <div class="modal-body">
        <p><strong>IMPORTANT:</strong> If you cancel your account, please note that your username (<?php echo get_the_author_meta('user_login', $_GET['id']); ?>) will be made available for someone else; any progress and access to pages you've created will be disabled; optins and leads will not be collected; and videos will not display if you added your own.</p>
        <div class="title-loader">
        	<div class="spinner">
			  <div class="rect1"></div>
			  <div class="rect2"></div>
			  <div class="rect3"></div>
			  <div class="rect4"></div>
			  <div class="rect5"></div>
			</div>
			<h4>Removing Access to Training...</h4>
        </div>
        <p>4 Binary Options Courses<br>
		12 Training Lessons<br>
		4 Forex Courses<br>
		14 Forex Training Lessons</p>
		<div class="title-loader">
        	<div class="spinner">
			  <div class="rect1"></div>
			  <div class="rect2"></div>
			  <div class="rect3"></div>
			  <div class="rect4"></div>
			  <div class="rect5"></div>
			</div>
			<h4>Removing Access to Software...</h4>
        </div>
		<p>1 Forex Scanners<br>
		1 Binary Scanner<br>
		All Trading Tools</p>
		<div class="title-loader">
        	<div class="spinner">
			  <div class="rect1"></div>
			  <div class="rect2"></div>
			  <div class="rect3"></div>
			  <div class="rect4"></div>
			  <div class="rect5"></div>
			</div>
			<h4>Removing Access to Webinars / Coaching...</h4>
        </div>
		<p>30 Forex Webinars<br>
		20 Binary Webinars</p>
		<div class="title-loader">
        	<div class="spinner">
			  <div class="rect1"></div>
			  <div class="rect2"></div>
			  <div class="rect3"></div>
			  <div class="rect4"></div>
			  <div class="rect5"></div>
			</div>
			<h4>Deleting Contacts...</h4>
        </div>
		<div class="title-loader">
        	<div class="spinner">
			  <div class="rect1"></div>
			  <div class="rect2"></div>
			  <div class="rect3"></div>
			  <div class="rect4"></div>
			  <div class="rect5"></div>
			</div>
			<h4>Removing Access To Pages...</h4>
        </div>
		<div class="row">
			<div class="col-md-6">
				<h4>Or...Finalize Account Cancellation:</h4>
			</div>
			<div class="col-md-6">
				<div class="btn-2-holder">
					<button type="button" class="btn btn-success" data-dismiss="modal">NO</button>
					<button type="button" class="btn btn-danger">YES</button>
				</div>
			</div>
		</div>
		<p><strong>IMPORTANT:</strong> If you cancel your account, please note that your username (USER_NAME) will be made available for someone else; any progress and access to pages you've created will be disabled; optins and leads will not be collected; and videos will not display if you added your own.</p>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>

<script type="text/javascript">
	$(document).ready(function(){
		$('.view-purchase-details').click(function(e){
			e.preventDefault();
			var html = $('#'+$(this).attr('data-target'))[0].outerHTML;
			$('#view-purchase-details .purchase-details-info').html('');
			$('#view-purchase-details .purchase-details-info').prepend(html);
			$('#table-purchases').hide();
			$('#view-purchase-details').fadeIn();
		});
		$('#close-purchase-details').click(function(e){
			e.preventDefault();
			$('#view-purchase-details').hide();
			$('#table-purchases').fadeIn();
		});
	});
</script>

<?php if($_GET['cancel'] == "yes"){ ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.marketing-contacts a[href="#c"]').click();
		$('.tab-pane#c').addClass('tab-pane-cancellation');
	});
</script>
<?php } ?>