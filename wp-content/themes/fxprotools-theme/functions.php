<?php
/**
 * -------------------
 * FXprotools Settings
 * -------------------
 * Fxprotools theme settings
 */

// Set the theme version number as a global variable
$theme          = wp_get_theme('fxprotools-theme');
$theme_version	= $theme['Version'];

$core_settings = [
	'core-admin-settings.php',
	'core-theme-settings.php',
];
foreach ($core_settings as $cs) {
	require_once('inc/core/'.$cs);
}

/**
 * ----------------
 * Custom Functions
 * ----------------
 * Includes all custom functions
 */
$custom_functions = array(
	'function-helper.php'
);

if($custom_functions){
	foreach($custom_functions as $key => $cf){
		require_once('inc/'.$cf);
	}
}

/**
 * -----------
 * ANET - CISM
 * -----------
 * Authorize.net customer information and subscription manager
 */
$anet_includes = [
	'auth-api.php',
	'auth-ajax.php',
];
foreach ($anet_includes as $a) {
	require_once('inc/authorize-net/'.$a);
}