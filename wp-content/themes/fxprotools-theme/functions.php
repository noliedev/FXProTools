<?php
/**
 * -------------
 * Core Settings
 * -------------
 * Core settings for admin and theme
 */
$theme = wp_get_theme('fxprotools-theme');
$theme_version = $theme['Version'];

$core_settings = array(
	'core-admin.php',
	'core-theme.php'
);
foreach ($core_settings as $setting) {
	require_once('core/'.$setting);
}

/**
 * -------------------
 * Authorize Net - SDK
 * ------------------
 * Include authorize.ned sdk
 */
include 'inc/authorize-net/AuthorizeNet.php';

/**
 * ----------------
 * Custom Functions
 * ----------------
 * Includes all custom functions
 */
$custom_functions = array(
	'function-helper.php',
	'function-wc-parsing.php'
);

if($custom_functions){
	foreach($custom_functions as $key => $function){
		require_once('inc/functions/'.$function);
	}
}
