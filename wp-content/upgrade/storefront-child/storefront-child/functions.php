<?php
/**
 * -------------------------
 * Storefront Child Settings
 * -------------------------
 * Storefront child core settings
 */

// Set the theme version number as a global variable
$theme = wp_get_theme( 'storefront-child' );
$storefront_child_version = $theme['Version'];

$theme = wp_get_theme( 'storefront' );
$storefront_version	= $theme['Version'];

// Load the individual class required by this theme
require_once( 'inc/class-storefront-child.php' );