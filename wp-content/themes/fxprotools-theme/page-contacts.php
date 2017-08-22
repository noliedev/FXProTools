<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-marketing'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Your Sales Funnels Stats</h1>
					<p>Let us do most of the work for you</p>
				</div>
				<div class="form-inline m-b-md">
					<div class="form-group">
						<input type="text" class="form-control" value="" placeholder="Starting: MM/DD/YYYY">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" value="" placeholder="Ending: MM/DD/YYYY">
					</div>
					<a href="#" class="btn btn-default pull-right"><i class="fa fa-download"></i> Export Contacts</a>
				</div>
				<div class="clearfix"></div>
				<div class="fx-search-contact">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="input-group">
								<div class="input-group-addon"><i class="fa fa-search"></i></div>
								<input type="text" class="form-control" placeholder="Search by name or e-mail">
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<ul class="fx-list-contacts">
					<li>
						<div class="media">
							<div class="media-left">
								<img src="http://via.placeholder.com/55x55?text=Image" class="media-object">
							</div>
							<div class="media-body">
								<div class="info">
									<h5 class="media-heading text-bold">Full Name</h5>
									<p>user-email@gmail.com</p>
								</div>
								<div class="actions">
									<span class="small">2 days ago</span>
									<a href="<?php bloginfo('url');?>/marketing/contact/user" class="btn btn-default btn-sm m-l-sm">Edit</a>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="media">
							<div class="media-left">
								<img src="http://via.placeholder.com/55x55?text=Image" class="media-object">
							</div>
							<div class="media-body">
								<div class="info">
									<h5 class="media-heading text-bold">Full Name</h5>
									<p>user-email@gmail.com</p>
								</div>
								<div class="actions">
									<span class="small">2 days ago</span>
									<a href="<?php bloginfo('url');?>/marketing/contact/user" class="btn btn-default btn-sm m-l-sm">Edit</a>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="media">
							<div class="media-left">
								<img src="http://via.placeholder.com/55x55?text=Image" class="media-object">
							</div>
							<div class="media-body">
								<div class="info">
									<h5 class="media-heading text-bold">Full Name</h5>
									<p>user-email@gmail.com</p>
								</div>
								<div class="actions">
									<span class="small">2 days ago</span>
									<a href="<?php bloginfo('url');?>/marketing/contact/user" class="btn btn-default btn-sm m-l-sm">Edit</a>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="media">
							<div class="media-left">
								<img src="http://via.placeholder.com/55x55?text=Image" class="media-object">
							</div>
							<div class="media-body">
								<div class="info">
									<h5 class="media-heading text-bold">Full Name</h5>
									<p>user-email@gmail.com</p>
								</div>
								<div class="actions">
									<span class="small">2 days ago</span>
									<a href="<?php bloginfo('url');?>/marketing/contact/user" class="btn btn-default btn-sm m-l-sm">Edit</a>
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="media">
							<div class="media-left">
								<img src="http://via.placeholder.com/55x55?text=Image" class="media-object">
							</div>
							<div class="media-body">
								<div class="info">
									<h5 class="media-heading text-bold">Full Name</h5>
									<p>user-email@gmail.com</p>
								</div>
								<div class="actions">
									<span class="small">2 days ago</span>
									<a href="<?php bloginfo('url');?>/marketing/contact/user" class="btn btn-default btn-sm m-l-sm">Edit</a>
								</div>
							</div>
						</div>
					</li>
				</ul>
				<div class="text-center">
					<ul class="pagination">
						<li><a href="#">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
						<li><a href="#">5</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>