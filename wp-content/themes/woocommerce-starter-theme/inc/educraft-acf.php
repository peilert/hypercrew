<?php
/**
 * ACF integration for the EduCraft task.
 *
 * @package StarterTheme
 */

starter_security_check();

if ( ! function_exists( 'starter_educraft_acf_json_save_point' ) ) {
	function starter_educraft_acf_json_save_point( $path ) {
		return get_template_directory() . '/acf-json';
	}
}
add_filter( 'acf/settings/save_json', 'starter_educraft_acf_json_save_point' );

if ( ! function_exists( 'starter_educraft_acf_json_load_points' ) ) {
	function starter_educraft_acf_json_load_points( $paths ) {
		$paths[] = get_template_directory() . '/acf-json';

		return array_values( array_unique( $paths ) );
	}
}
add_filter( 'acf/settings/load_json', 'starter_educraft_acf_json_load_points' );

if ( ! function_exists( 'starter_educraft_get_field_value' ) ) {
	function starter_educraft_get_field_value( $field_name, $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : get_the_ID();

		if ( function_exists( 'get_field' ) ) {
			return get_field( $field_name, $post_id );
		}

		return get_post_meta( $post_id, $field_name, true );
	}
}
