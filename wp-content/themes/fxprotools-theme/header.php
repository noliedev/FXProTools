<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div class="<?php echo !is_home() ? 'fx-wrapper' : ''; ?> <?php echo is_page(array('login', 'forgot-password')) ? 'fx-login' : ''; ?>">
		
		<?php if( is_user_logged_in() && !is_page(array('login', 'forgot-password', 'f1', 'f2', 'f3', 'f4', 'signals')) ): ?>
		<nav class="navbar fx-navbar-main" role="navigation">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<div class="navbar-header fx-navbar-header">
							<a class="navbar-brand" href="#">
								<img src="http://via.placeholder.com/115x50?text=Logo">
							</a>
						</div>
					</div>
					<div class="col-md-7">
						<ul class="fx-nav-options">
							<li><a href="<?php bloginfo('url'); ?>/dashboard" title="Dashboard Icon"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
							<li><a href="<?php bloginfo('url'); ?>/basic-training" title="Product Icon"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
							<?php  if ( WC_Subscriptions_Manager::user_has_subscription( '', 48, 'active') || current_user_can('administrator') ): ?>
								<li><a href="<?php bloginfo('url'); ?>/marketing/funnels" title="Marketing Icon"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
								<li><a href="<?php bloginfo('url'); ?>/team" title="Team Icon"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
							<?php endif;?>
							<li><a href="<?php bloginfo('url'); ?>/wallet" title="Money Icon"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
						</ul>
					</div>
					<div class="col-md-3">
						<ul class="fx-nav-options">
							<li><a href="#" title="Select Language"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
							<li class="account"><a href="<?php bloginfo('url'); ?>/my-account">Account</a></li>
							<li><a href="#" title="Download Icon"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
							<li><a href="#" title="Support Icon"><i class="fa fa-th-large" aria-hidden="true"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</nav>
		<?php endif; ?>