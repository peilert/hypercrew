<?php
/**
 * @package Starter Theme
 *
 * @package starter
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

function starter_posted_on()
{
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s"> (%4$s) </time>';
    }
    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date('c')),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date('c')),
        esc_html(get_the_modified_date())
    );
    $posted_on   = apply_filters(
        'starter_posted_on',
        sprintf(
            '<span class="posted-on">%1$s <a href="%2$s" rel="bookmark">%3$s</a></span>',
            esc_html_x('Published:', 'post date', 'starter'),
            esc_url(get_permalink()),
            apply_filters('starter_posted_on_time', $time_string)
        )
    );
    $byline      = apply_filters(
        'starter_posted_by',
        sprintf(
            '<span class="byline"> %1$s<span class="author vcard"> <a class="url fn n" href="%2$s">%3$s</a></span></span>',
            $posted_on ? esc_html_x('Author: ', 'post author', 'starter') : esc_html_x('Author', 'post author',
                'starter'),
            esc_url(get_author_posts_url(get_the_author_meta('ID'))),
            esc_html(get_the_author())
        )
    );

    echo $posted_on . $byline; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ( ! function_exists('starter_entry_footer')) {
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function starter_entry_footer()
    {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            $categories_list = get_the_category_list(esc_html__(', ', 'starter'));
            if ($categories_list) {
                printf(
                    '<span class="cat-links">' .
                    esc_html__('Category: %s', 'starter') . '</span>',
                    $categories_list
                );
            }

            $tags_list = get_the_tag_list('', esc_html__(', ', 'starter'));
            if ($tags_list) {
                printf(
                    '<span class="tags-links">' .
                    esc_html__('Tags %s', 'starter') . '</span>',
                    $tags_list
                );
            }
        }
    }
}

if ( ! function_exists('starter_custom_excerpt_length')) {
    /**
     * Prints short excerpt
     */

    function starter_custom_excerpt_length($length)
    {
        return 20;
    }

    add_filter('excerpt_length', 'starter_custom_excerpt_length', 999);
}
