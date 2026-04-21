<?php
/**
 * All nav menus goes here
 *
 * @package StarterTheme
 */


starter_security_check();
 
if ( ! function_exists( 'starter_nav_menus' ) ) {
    function starter_nav_menus() {
        register_nav_menus( array(
            'primary'   => __('Primary menu', 'starter'),
            'secondary' => __('Secondary menu', 'starter'),
            'footer'    => __('Footer menu', 'starter'),
        ) );
    }
}

add_action( 'after_setup_theme', 'starter_nav_menus' );
