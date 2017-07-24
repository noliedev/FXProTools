<?php
/**
 * -------------------------
 * Storefront Child Settings
 * -------------------------
 * Storefront child core settings
 */

// Set the theme version number as a global variable
$theme = wp_get_theme('storefront-child');
$storefront_child_version = $theme['Version'];

$theme = wp_get_theme('storefront');
$storefront_version	= $theme['Version'];

// Load the individual class required by this theme
$core_settings = array(
	'class-storefront-admin.php',
	'class-storefront-theme.php',
);
foreach ($core_settings as $setting) {
	require_once('inc/'.$setting);
}

/**
 * ----------------
 * Custom Functions
 * ----------------
 * Includes all custom functions
 */
$custom_functions = array(
	'function-helper.php',
	'function-wc-parsing.php',
	'class-anet-api.php'
);

if($custom_functions){
	foreach($custom_functions as $key => $function){
		require_once('inc/'.$function);
	}
}