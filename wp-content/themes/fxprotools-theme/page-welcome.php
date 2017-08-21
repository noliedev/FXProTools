<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-welcome'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Welcome! Thanks for Being A Loyal Distributor</h1>
					<p>Step#1 - The Vision & Your FX Pro Tools Onboarding</p>
				</div>
			</div>
			<div class="col-md-8">
				<div class="fx-video-container"></div>
			</div>
			<div class="col-md-4">
				<div class="fx-board checklist">
					<div class="fx-board-title">
						<span>Onboard Checklist</span>
					</div>
					<ul class="fx-board-list">
						<?php $lists = ['Verify your e-mail', 'Update/Verify Profile (SMS #)', 'Schedule For Webinar',	'Access your product', 'Get your free shirt',	'Share Video', 'Refer A Friend']; ?>
						<?php foreach ($lists as $key => $list): ?>
						<li>
							<span class="fx-checkbox <?php echo $key == 0 ? 'checked': ''; ?>"></span>
							<span class="fx-text"><?php echo $list; ?></span>
						</li>
						<?php endforeach; ?>
						<li><a href="" class="btn btn-danger btn-lg fx-btn block">I'm ready for the next step</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container m-t-lg">
		<div class="row">
			<div class="col-md-8">
				<a href="#" class="btn btn-default btn-lg block">Schedule for "Welcome Webinar"</a>
			</div>
			<div class="col-md-4">
				<a href="#" class="btn btn-default btn-lg block">Get Your Free T-shirt(Store)</a>
			</div>
		</div>
	</div>

<?php get_footer(); ?>