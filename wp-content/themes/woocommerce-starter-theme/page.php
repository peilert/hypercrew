<?php
/**
 * Default page template.
 */

get_header();
?>
<section class="page-content py-5">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	</div>
</section>
<?php
get_footer();
