<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-wallet'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>E-Wallet Summary</h1>
					<p>All of your commisions you earned</p>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-2">
				<div class="panel panel-default text-center m-r-n-xs">
					<div class="panel-body">
						<span>Today</span>
						<h3 class="m-t-xs">$2,300.00</h3>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="panel panel-default text-center m-r-n-xs m-l-n-xs">
					<div class="panel-body">
						<span>Yesterday</span>
						<h3 class="m-t-xs">$300.25</h3>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="panel panel-default text-center m-r-n-xs m-l-n-xs">
					<div class="panel-body">
						<span>Last Week</span>
						<h3 class="m-t-xs">$8,190.15</h3>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="panel panel-default text-center m-r-n-xs m-l-n-xs">
					<div class="panel-body">
						<span>Last Month</span>
						<h3 class="m-t-xs">$23,333.16</h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-default text-center m-l-n-xs">
					<div class="panel-body">
						<span>All Time</span>
						<h3 class="m-t-xs">$100,300.00</h3>
					</div>
				</div>
			</div>
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
							<th class="small">Date</th>
							<th class="small">Amount</th>
							<th class="small">Details</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>07-07-2017</td>
							<td>$1080.50</td>
							<td>You Earn $25.00 / Month Commission For: Jane Smith The amount in holding will be released by 07/21/2017</td>
						</tr>
						<tr>
							<td>07-07-2017</td>
							<td>$798.50</td>
							<td>You Earn $50.00 / Month Commission For: Jane Smith The amount in holding will be released by 07/21/2017</td>
						</tr>
						<tr>
							<td>07-07-2017</td>
							<td>$798.50</td>
							<td>You Earn $25.00 / Month Commission For: Jane Smith The amount in holding will be released by 07/21/2017</td>
						</tr>
					</tbody>
				</table>
				
				<?php $customer = false; ?>

				<?php if($customer): ?>
					<div class="panel panel-default">
						<div class="panel-body text-center p-lg">
							<h3 class="m-t-sm">Earn Commissions For Your Referrals</h3>
							<p>Go beyond just a free account & build a business that can generate an additional stream of monthly income with our volume based bonus plan.</p>
							<a href="#" class="btn btn-lg btn-warning fx-btn m-t-sm">Unlock This Feature On My Account</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	

<?php get_footer(); ?>