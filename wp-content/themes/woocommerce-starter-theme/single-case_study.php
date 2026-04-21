<?php
/**
 * Single template for Case Study CPT.
 *
 * @package StarterTheme
 */

get_header();
?>
<section class="case-study-single">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
			$client_name       = starter_educraft_get_field_value( 'client_name' );
			$short_description = starter_educraft_get_field_value( 'short_description' );
			$client_url        = starter_educraft_get_field_value( 'client_url' );
			$main_image        = function_exists( 'get_field' ) ? get_field( 'main_image' ) : null;
			$gallery_images    = function_exists( 'get_field' ) ? get_field( 'case_gallery' ) : array();
			$industry_labels   = starter_educraft_get_case_study_industry_labels();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'case-study-single__article' ); ?>>
				<header class="case-study-single__header">
					<h1><?php the_title(); ?></h1>
					<?php if ( $industry_labels ) : ?>
						<p class="case-study-single__meta"><strong><?php esc_html_e( 'Branza:', 'starter' ); ?></strong> <?php echo esc_html( $industry_labels ); ?></p>
					<?php endif; ?>
					<?php if ( $client_name ) : ?>
						<p class="case-study-single__meta"><strong><?php esc_html_e( 'Klient:', 'starter' ); ?></strong> <?php echo esc_html( $client_name ); ?></p>
					<?php endif; ?>
					<?php if ( $client_url ) : ?>
						<p class="case-study-single__meta">
							<strong><?php esc_html_e( 'Strona klienta:', 'starter' ); ?></strong>
							<a href="<?php echo esc_url( $client_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $client_url ); ?></a>
						</p>
					<?php endif; ?>
				</header>

				<?php if ( ! empty( $main_image ) && ! empty( $main_image['ID'] ) ) : ?>
					<div class="case-study-single__main-image">
						<?php echo wp_kses_post( wp_get_attachment_image( (int) $main_image['ID'], 'large', false, array( 'class' => 'img-fluid' ) ) ); ?>
					</div>
				<?php elseif ( has_post_thumbnail() ) : ?>
					<div class="case-study-single__main-image">
						<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $short_description ) : ?>
					<p class="case-study-single__short-description"><?php echo esc_html( $short_description ); ?></p>
				<?php endif; ?>

				<div class="case-study-single__content">
					<?php the_content(); ?>
				</div>

				<?php if ( ! empty( $gallery_images ) && is_array( $gallery_images ) ) : ?>
					<section class="case-study-single__gallery">
						<h2><?php esc_html_e( 'Galeria', 'starter' ); ?></h2>
						<div class="case-study-single__gallery-grid">
							<?php foreach ( $gallery_images as $gallery_image ) : ?>
								<?php if ( is_array( $gallery_image ) && ! empty( $gallery_image['ID'] ) ) : ?>
									<?php echo wp_kses_post( wp_get_attachment_image( (int) $gallery_image['ID'], 'medium_large', false, array( 'class' => 'img-fluid' ) ) ); ?>
								<?php elseif ( is_numeric( $gallery_image ) ) : ?>
									<?php echo wp_kses_post( wp_get_attachment_image( (int) $gallery_image, 'medium_large', false, array( 'class' => 'img-fluid' ) ) ); ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>
			</article>
		<?php endwhile; ?>
	</div>
</section>
<?php
get_footer();
