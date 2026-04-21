<?php
/**
 * Custom helper functions that can be used across the theme.
 *
 * @package StarterTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Prevent direct access
if ( ! function_exists( 'starter_security_check' ) ) {
    function starter_security_check() {
        if ( ! defined( 'ABSPATH' ) ) {
            exit;
        }
    }
}

if (!function_exists('is_repeater_empty')) {
	function is_repeater_empty( $customField ) {
		$isntEmpty = 0;
		if ( $customField ) {
			foreach ( $customField as $field ) {
				if ( $field ) {
					foreach ( $field as $subfield ) {
						if ( $subfield ) {
							$isntEmpty = 1;
						}
					}
				}
			}
		}
		return $isntEmpty;
	}
}
