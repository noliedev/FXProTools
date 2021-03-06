<?php
$checklist = get_user_checklist();
?>
<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-dashboard'); ?>

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
						<li>
							<span class="fx-checkbox <?php echo $checklist['verified_email'] ? 'checked' : '';?>"></span>
							<span class="fx-text">Verify your e-mail</span>
						</li>
						<li>
							<span class="fx-checkbox <?php echo $checklist['verified_profile'] ? 'checked' : '';?>"></span>
							<span class="fx-text">Update/Verify Profile (SMS #)</span>
						</li>
						<li>
							<span class="fx-checkbox <?php echo $checklist['scheduled_webinar'] ? 'checked' : '';?>"></span>
							<span class="fx-text">Schedule For Webinar</span>
						</li>
						<li>
							<span class="fx-checkbox <?php echo $checklist['accessed_products'] ? 'checked' : '';?>"></span>
							<span class="fx-text">Access your product</span>
						</li>
						<li>
							<span class="fx-checkbox <?php echo $checklist['got_shirt'] ? 'checked' : '';?>"></span>
							<span class="fx-text">Get your free shirt</span>
						</li>
						<li>
							<span class="fx-checkbox <?php echo $checklist['shared_video'] ? 'checked' : '';?>"></span>
							<span class="fx-text">Share Video</span>
						</li>
						<li>
							<span class="fx-checkbox <?php echo $checklist['referred_friend'] ? 'checked' : '';?>"></span>
							<span class="fx-text">Refer A Friend</span>
						</li>
						<li><a href="<?php echo get_checklist_next_step_url();?>" class="btn btn-danger btn-lg fx-btn block">I'm ready for the next step</a></li>
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