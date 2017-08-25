<?php
$category_slug = get_query_var('category_slug'); 
$category = get_term_by('slug', $category_slug, 'ld_course_category' );

?>


<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-products'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>List of <?php echo $category->name;?></h1>
					<p>Filter <?php echo $category->name;?> With Buttons Below</p>
				</div>
			</div>
			<div class="col-md-12">
				<div role="tabpanel">
					<ul class="nav nav-tabs fx-tabs" role="tablist">
						<li role="presentation">
							<a href="#one" aria-controls="one" role="tab" data-toggle="tab">Binary</a>
						</li>
						<li role="presentation" class="active">
							<a href="#two" aria-controls="two" role="tab" data-toggle="tab">Forex</a>
						</li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane" id="one">
							<div class="fx-mid-header">
								<h2>Get The Most Out Of FX Pro Tools</h2>
								<p>Supercharge your learning experience using FX Pro Tools</p>
							</div>
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
												<span class="title">Basic Overview Of Forex</span>
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
												<span class="title">Forex Indicator Course</span>
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
												 	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
														
												 	</div>
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</li>
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
												<span class="title">Expert Training Course</span>
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
												 	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
														
												 	</div>
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</li>
							</ul>
						</div>
						<div role="tabpanel" class="tab-pane active" id="two">
							<div class="fx-mid-header">
								<h2>Get The Most Out Of FX Pro Tools</h2>
								<p>Supercharge your learning experience using FX Pro Tools</p>
							</div>
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
												<span class="title">Basic Overview Of Forex</span>
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
												<span class="title">Forex Indicator Course</span>
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
												 	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
														
												 	</div>
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</li>
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
												<span class="title">Expert Training Course</span>
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
												 	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
														
												 	</div>
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	

<?php get_footer(); ?>