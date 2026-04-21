<?php
/**
 * Register all styles and scripts
 *
 * @package StarterTheme
 */
starter_security_check();

if ( ! function_exists( 'starter_get_asset_manifest' ) ) {
	function starter_get_asset_manifest() {
		static $manifest = null;

		if ( null !== $manifest ) {
			return $manifest;
		}

		$manifest_path = get_template_directory() . '/assets/manifest.json';
		if ( ! file_exists( $manifest_path ) ) {
			$manifest = array();
			return $manifest;
		}

		$manifest_contents = file_get_contents( $manifest_path );
		$manifest_data     = json_decode( (string) $manifest_contents, true );
		$manifest          = is_array( $manifest_data ) ? $manifest_data : array();

		return $manifest;
	}
}

if ( ! function_exists( 'starter_get_asset_path' ) ) {
	function starter_get_asset_path( $asset_name ) {
		$manifest      = starter_get_asset_manifest();
		$manifest_path = isset( $manifest[ $asset_name ] ) ? ltrim( (string) $manifest[ $asset_name ], '/' ) : 'assets/' . $asset_name;

		return get_template_directory() . '/' . $manifest_path;
	}
}

if ( ! function_exists( 'starter_get_asset_uri' ) ) {
	function starter_get_asset_uri( $asset_name ) {
		$manifest     = starter_get_asset_manifest();
		$manifest_uri = isset( $manifest[ $asset_name ] ) ? ltrim( (string) $manifest[ $asset_name ], '/' ) : 'assets/' . $asset_name;

		return get_template_directory_uri() . '/' . $manifest_uri;
	}
}

if ( ! function_exists( 'starter_enqueue_styles' ) ) {
	function starter_enqueue_styles() {
		$css_file    = starter_get_asset_path( 'main.css' );
		$css_path    = starter_get_asset_uri( 'main.css' );
		$css_version = file_exists( $css_file ) ? filemtime( $css_file ) : wp_get_theme()->get( 'Version' );

		wp_enqueue_style( 'starter-styles', $css_path, array(), $css_version );
	}
}

if ( ! function_exists( 'starter_enqueue_scripts' ) ) {
	function starter_enqueue_scripts() {
		$js_file    = starter_get_asset_path( 'main.js' );
		$js_path    = starter_get_asset_uri( 'main.js' );
		$js_version = file_exists( $js_file ) ? filemtime( $js_file ) : wp_get_theme()->get( 'Version' );

		wp_enqueue_script( 'starter-scripts', $js_path, array(), $js_version, true );

		$frontend_data = array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		);

		if ( is_post_type_archive( 'case_study' ) ) {
			$frontend_data['caseStudyFilter'] = array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'starter_case_study_filter' ),
			);
		}

		wp_add_inline_script(
			'starter-scripts',
			'window.starterThemeData = ' . wp_json_encode( $frontend_data ) . ';',
			'before'
		);
	}
}

add_action( 'wp_enqueue_scripts', 'starter_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'starter_enqueue_scripts', 5 );
