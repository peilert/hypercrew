<?php
/**
 * 404 template.
 */

get_header();
?>
<section class="not-found py-5">
	<div class="container">
		<h1><?php esc_html_e( '404 - Page not found', 'starter' ); ?></h1>
		<p><?php esc_html_e( 'The page you are looking for does not exist or has been moved.', 'starter' ); ?></p>
		<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to homepage', 'starter' ); ?></a></p>
	</div>
</section>
<?php
get_footer();
