<?php
/**
 * --------------
 * Theme Settings
 * --------------
 * Storefront child core settings
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
