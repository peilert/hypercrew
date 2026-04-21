<?php
/**
 * Custom image sizes goes here
 *
 * @package StarterTheme
 */

starter_security_check();

if ( ! function_exists( 'starter_image_sizes' ) ) {
    function starter_image_sizes() {
       add_image_size('blog_thumb_big', '830', '400', true);
       add_image_size('blog_thumb_small', '400', '170', true);
    }
}

add_action( 'after_setup_theme', 'starter_image_sizes' );
