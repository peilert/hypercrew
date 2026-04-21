<?php
/**
 * Theme bootstrap.
 *
 * @package Starter Theme
 */

require_once get_template_directory() . '/inc/template-functions.php';
starter_security_check();

$includes = array(
	'/theme-settings.php',
	'/theme-tags.php',
	'/image-sizes.php',
	'/nav-menus.php',
	'/enqueue.php',
	'/customizer.php',
	'/educraft-acf.php',
	'/educraft-case-studies.php',
	'/educraft-checkout.php',
);

foreach ( $includes as $file ) {
	$filepath = locate_template( 'inc' . $file );
	if ( ! $filepath ) {
		trigger_error( sprintf( 'Error locating /inc%s for inclusion', $file ), E_USER_ERROR );
	}
	require_once $filepath;
}

if ( class_exists( 'WooCommerce' ) ) {
	$woocommerce_file = locate_template( 'inc/woocommerce-changes.php' );
	if ( $woocommerce_file ) {
		require_once $woocommerce_file;
	}
}
