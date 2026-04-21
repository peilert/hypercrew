<?php
/**
 * Front page template.
 */

get_header();
?>
<?php if ( class_exists( 'WooCommerce' ) ) : ?>
	<section class="front-page front-page--shop">
		<div class="container">
			<header class="front-page__header">
				<h1><?php esc_html_e( 'Sklep', 'starter' ); ?></h1>
				<p><?php esc_html_e( 'Wybierz szkolenie lub kurs i dodaj do koszyka.', 'starter' ); ?></p>
			</header>
			<?php echo do_shortcode( '[products limit="12" columns="4" paginate="true" orderby="date" order="DESC"]' ); ?>
		</div>
	</section>
<?php else : ?>
	<section class="front-page">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php if ( get_the_title() ) : ?>
						<h1><?php the_title(); ?></h1>
					<?php endif; ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			<?php else : ?>
				<h1><?php bloginfo( 'name' ); ?></h1>
				<p><?php esc_html_e( 'Start building your next WooCommerce project from here.', 'starter' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
<?php
get_footer();
