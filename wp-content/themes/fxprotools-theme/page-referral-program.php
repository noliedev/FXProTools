<?php get_header(); ?>

	<?php get_template_part('inc/templates/nav-distributor'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-header-title">
					<h1>Wait! Did You Know Your Access Could Be Free</h1>
					<p>Step#3 - Refer 3 People To FX Pro Tools Products & Yours Become Free</p>
				</div>
			</div>
			<div class="col-md-8">
				<div class="fx-video-container"></div>
			</div>
			<div class="col-md-4">
				<div class="fx-board checklist">
					<div class="fx-board-title w-text">
						<span>Refer 3 & Your Free</span>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua.</p>
					</div>
					<ul class="fx-board-list">
						<?php $lists = ['Refer First Friend', 'Refer Second Friend', 'Refer Third Friend']; ?>
						<?php foreach ($lists as $key => $list): ?>
						<li>
							<span class="fx-checkbox <?php echo $key == 0 ? 'checked': ''; ?>"></span>
							<span class="fx-text"><?php echo $list; ?></span>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="fx-ref-links">
					<div class="row">
						<div class="col-md-6">
							<div class="fx-ref-link">
								<div class="fx-ref-title">
									<h3>Option 1: Refer To Someone</h3>
									<p>Tell your friends and coworkers about FX Pro Tools</p>
								</div>
								<div class="box">
									Share your unique referral link
									<div class="link">
										(Dynamic to Highest %) http://url.com?ref=22
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="fx-ref-link">
								<div class="fx-ref-title">
									<h3>Option 2: With Our Sales Funnels</h3>
									<p>Promoting your business should be easy</p>
								</div>
								<div class="box">
									<div class="text-center">
										We provide you with all the Marketing Sales <br/>Funnels you need to explain FX Pro tools
									</div>
									<a href="#" class="link-btn">
										Access Your Sales Funnels
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php get_footer(); ?>