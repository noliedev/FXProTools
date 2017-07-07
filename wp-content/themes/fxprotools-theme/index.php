<?php get_header(); ?>

<div class="wrapper wrapper-content animated fadeInLeft">
	
	<div class="row">
		<div class="col-md-3">
			<div class="ibox">
				<div class="ibox-title">
					<h5>Downline Members</h5>
				</div>
				<div class="ibox-content">
					<h1 class="m-b-sm text-success">20</h1>
					<small>As of July 15, 2017</small>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="ibox">
				<div class="ibox-title">
					<h5>Cash Withdrawn</h5>
				</div>
				<div class="ibox-content">
					<h1 class="m-b-sm text-success">20,000 <small>usd</small></h1>
					<small>As of July 15, 2017</small>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="ibox">
				<div class="ibox-title">
					<h5>E-Wallet Credits</h5>
				</div>
				<div class="ibox-content">
					<h1 class="m-b-sm text-success">45,500 <small>usd</small></h1>
					<small>As of July 15, 2017</small>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="ibox">
				<div class="ibox-title">
					<h5>E-Wallet Debits</h5>
				</div>
				<div class="ibox-content">
					<h1 class="m-b-sm text-success">100,000 <small>usd</small></h1>
					<small>As of July 15, 2017</small>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8">
			<div class="ibox">
				<div class="ibox-title">
					<h5>Downline Members</h5>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-md-12">
							<div class="flot-chart">
								<div class="flot-chart-content" id="flot-dashboard-chart"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Recent Joinees</h5>
				</div>
				<div class="ibox-content">
					<div class="feed-activity-list">
						<?php for ($i=0; $i < 5; $i++) { ?>
						<div class="feed-element">
							<a href="profile.html" class="pull-left">
								<img alt="image" class="img-circle" src="<?php bloginfo('template_url') ?>/assets/img/blank-profile-photo.png">
							</a>
							<div class="media-body" data-count="<?php echo $i; ?>">
								<strong>John Doe</strong> <br>
								<small class="text-muted">Today 5:60 pm - July 6, 2017</small>
							</div>
						</div>
						<?php } ?>
					</div>
					<button class="btn btn-primary btn-block m-t"><i class="fa fa-arrow-down"></i> Show All</button>
				</div>
			</div>
		</div>
	</div>

</div>

<?php get_footer(); ?>