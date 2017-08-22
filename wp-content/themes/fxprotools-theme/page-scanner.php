<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-products'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="fx-list-courses">
					<li class="list-item">
						<div class="left">
							<div class="box">
								<span class="sash">Active</span>
								<span class="number">01</span>
							</div>
						</div>
						<div class="right">
							<div class="row">
								<div class="col-md-12">
									<span class="title">Learn How To Use The Forex Scanner</span>
								</div>
								<div class="col-md-10">
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
									quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
									cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
									proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>	
								</div>
								<div class="col-md-2">
									<a href="<?php bloginfo('url');?>/product/course" class="btn btn-default block">Learn More</a>
								</div>
								<div class="col-md-12">
									<div class="progress">
									 	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
											25%
									 	</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
				</ul>
				<br/>
				<div class="fx-header-title">
					<h1>Forex Scanner</h1>
					<p>Let us do most of the work for you</p>
				</div>
			</div>
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div role="tabpanel">
							<ul class="nav nav-tabs fx-tabs courses" role="tablist">
								<li role="presentation" class="active">
									<a href="#binary" aria-controls="binary" role="tab" data-toggle="tab">Binary</a>
								</li>
								<li role="presentation">
									<a href="#forex" aria-controls="forex" role="tab" data-toggle="tab">Forex</a>
								</li>
							</ul>
							<br/>
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="binary">
									<div class="fx-video-container"></div>
									<br/>
									<div class="panel panel-default">
										<div class="panel-body centered-item" style="height: 400px;">
											<i class="fa fa-users" style="font-size: 70px;"></i>
										</div>
									</div>
								</div>
								<div role="tabpanel" class="tab-pane" id="forex">
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

	

<?php get_footer(); ?>