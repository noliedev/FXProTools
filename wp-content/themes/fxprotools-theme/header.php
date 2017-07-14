<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

	<?php //do_action( 'storefront_before_site' ); ?>

	<div id="wrapper">
		<?php get_template_part('inc/template/navigation-side') ?>
		<div id="page-wrapper" class="gray-bg">
		<?php get_template_part('inc/template/navigation-top') ?>