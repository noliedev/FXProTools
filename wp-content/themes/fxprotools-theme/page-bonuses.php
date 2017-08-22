<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-wallet'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Bonuses Summary</h1>
					<p>All of your commisions you earned</p>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-5">
				<div class="panel panel-default text-center m-r-n-xs">
					<div class="panel-body">
						<span>Your A</span>
						<h3 class="m-t-xs">Customer</h3>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="panel panel-default text-center m-r-n-xs m-l-n-xs">
					<div class="panel-body">
						<span>Global Percentage</span>
						<h3 class="m-t-xs">%1</h3>
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="panel panel-default text-center m-l-n-xs">
					<div class="panel-body">
						<span>Global Pool "Bonus" Amount Earned</span>
						<h3 class="m-t-xs">$0 / Monthly</h3>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-3">
				<div class="panel panel-default text-center m-r-n-xs">
					<div class="panel-body">
						<span>Personal Volume</span>
						<h3 class="m-t-xs">1090</h3>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="panel panel-default text-center m-l-n-xs m-r-n-xs">
					<div class="panel-body">
						<span>Customers</span>
						<h3 class="m-t-xs">397</h3>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="form-inline pull-right">
							<div class="form-group">
								<input type="text" class="form-control" value="" placeholder="Starting: MM/DD/YYYY">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" value="" placeholder="Ending: MM/DD/YYYY">
							</div>
						</div>
						<h4>E-Wallet Summary</h4>
					</div>
				</div>
				<table class="table table-bordered with-padding">
					<thead>
						<tr>
							<th colspan="3">
								<h3 class="m-t-sm">Bonuses / Incentives</h3>
							</th>
						</tr>
						<tr>
							<th class="small">Title of Bonus</th>
							<th class="small">Rank Name</th>
							<th class="small">Details</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Free Membership</td>
							<td>CPS Generator</td>
							<td>You qualified for this on July 21st 2017.</td>
						</tr>
						<tr>
							<td>iWatch</td>
							<td>Senior</td>
							<td>You need 50 more in Personal Volume to unlock this.</td>
						</tr>
						<tr>
							<td>Macbook</td>
							<td>Director</td>
							<td>You need 500 more in Personal Volume to unlock this.</td>
						</tr>
						<tr>
							<td>Vacation</td>
							<td>Executive</td>
							<td>You need 1500 more in Personal Volume to unlock this.</td>
						</tr>
						<tr>
							<td>Rolex</td>
							<td>Chairman</td>
							<td>You need 2000 more in Personal Volume to unlock this.</td>
						</tr>
						<tr>
							<td>Jet Smarter</td>
							<td>President</td>
							<td>You need 2500 more in Personal Volume to unlock this.</td>
						</tr>
						<tr>
							<td>Lamborghini</td>
							<td>Crown</td>
							<td>You need 3000 more in Personal Volume to unlock this.</td>
						</tr>
						<tr>
							<td>???</td>
							<td>Elite 1</td>
							<td>You need 5000 more in Personal Volume to unlock this.</td>
						</tr>
						<tr>
							<td>???</td>
							<td>Elite 2</td>
							<td>You need 10,000 more in Personal Volume to unlock this.</td>
						</tr>
						<tr>
							<td>???</td>
							<td>Shareholder</td>
							<td>You need 20,000 more in Personal Volume to unlock this.</td>
						</tr>
					</tbody>
				</table>
				
				<?php $customer = false; ?>

				<?php if($customer): ?>

					<div class="panel panel-default">
						<div class="panel-body text-center p-lg">
							<h3 class="m-t-sm">Make Your Money Work For You! Discover How...</h3>
							<p>Generate an additional stream of monthly income with our volume based bonus plan.</p>
							<a href="#" class="btn btn-lg btn-warning fx-btn m-t-sm">Unlock This Feature On My Account</a>
						</div>
					</div>

				<?php endif; ?>
			</div>
		</div>
	</div>

	

<?php get_footer(); ?>