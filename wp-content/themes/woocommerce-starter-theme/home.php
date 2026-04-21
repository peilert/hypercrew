<?php
/**
 * Posts index template.
 */

get_header();
?>
<section class="blog-index py-5">
	<div class="container">
		<header class="mb-4">
			<h1><?php single_post_title(); ?></h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'mb-4' ); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php starter_posted_on(); ?>
					<div><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>

			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No posts found.', 'starter' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
