<?php
/**
 * Single template.
 */

if ( function_exists( 'is_product' ) && is_product() ) {
	require_once get_template_directory() . '/woocommerce/single-product.php';
	exit;
}

get_header();
?>
<section class="single-post py-5">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<?php starter_posted_on(); ?>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-post__thumb my-3"><?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?></div>
				<?php endif; ?>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	</div>
</section>
<?php
get_footer();
