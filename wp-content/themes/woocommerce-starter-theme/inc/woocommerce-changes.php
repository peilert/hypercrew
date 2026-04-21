<?php
/**
 * WooCommerce support for starter theme.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'starter_woocommerce_support' ) ) {
	function starter_woocommerce_support() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}

add_action( 'after_setup_theme', 'starter_woocommerce_support' );

if ( ! function_exists( 'starter_woocommerce_wrapper_start' ) ) {
	function starter_woocommerce_wrapper_start() {
		echo '<section class="starter-woocommerce-layout"><div class="container">';
	}
}

if ( ! function_exists( 'starter_woocommerce_wrapper_end' ) ) {
	function starter_woocommerce_wrapper_end() {
		echo '</div></section>';
	}
}

if ( ! function_exists( 'starter_woocommerce_custom_wrappers' ) ) {
	function starter_woocommerce_custom_wrappers() {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

		add_action( 'woocommerce_before_main_content', 'starter_woocommerce_wrapper_start', 10 );
		add_action( 'woocommerce_after_main_content', 'starter_woocommerce_wrapper_end', 10 );
	}
}
add_action( 'wp', 'starter_woocommerce_custom_wrappers' );

if ( ! function_exists( 'starter_woocommerce_product_thumbnail_size' ) ) {
	function starter_woocommerce_product_thumbnail_size() {
		return 'medium_large';
	}
}
add_filter( 'single_product_archive_thumbnail_size', 'starter_woocommerce_product_thumbnail_size' );
add_filter( 'subcategory_archive_thumbnail_size', 'starter_woocommerce_product_thumbnail_size' );

if ( ! function_exists( 'starter_woocommerce_cart_item_thumbnail' ) ) {
	function starter_woocommerce_cart_item_thumbnail( $thumbnail, $cart_item ) {
		if ( empty( $cart_item['data'] ) || ! is_a( $cart_item['data'], 'WC_Product' ) ) {
			return $thumbnail;
		}

		$product = $cart_item['data'];
		return $product->get_image( 'medium' );
	}
}
add_filter( 'woocommerce_cart_item_thumbnail', 'starter_woocommerce_cart_item_thumbnail', 10, 2 );
