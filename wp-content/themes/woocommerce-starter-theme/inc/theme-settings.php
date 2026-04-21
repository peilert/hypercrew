<?php
/**
 * All theme suports goes here
 *
 * @package Starter Theme
 */

starter_security_check();

if ( ! function_exists( 'starter_theme_supports' ) ) {
    function starter_theme_supports() {
        load_theme_textdomain('starter');

        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'menus' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'customize-selective-refresh-widgets' );
        add_theme_support( 'custom-logo' );
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );
	    //Turn off auto adding <p> tags in Contact Form 7
	    add_filter('wpcf7_autop_or_not', '__return_false');
    }
}

add_action( 'after_setup_theme', 'starter_theme_supports' );
