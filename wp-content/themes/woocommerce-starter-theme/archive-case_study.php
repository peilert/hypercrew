<?php
/**
 * Archive template for Case Study CPT.
 *
 * @package StarterTheme
 */

get_header();

$selected_industry = isset( $_GET['industry'] ) ? sanitize_title( wp_unslash( $_GET['industry'] ) ) : 'all';
$terms             = get_terms(
	array(
		'taxonomy'   => 'case_industry',
		'hide_empty' => false,
	)
);

$query = starter_educraft_get_case_study_archive_query( $selected_industry );
?>
<section class="case-studies case-studies--archive">
	<div class="container">
		<header class="case-studies__header">
			<h1><?php post_type_archive_title(); ?></h1>
			<p><?php esc_html_e( 'Poznaj efekty szkolen i warsztatow realizowanych dla klientow EduCraft.', 'starter' ); ?></p>
		</header>

		<form
			id="case-study-filter-form"
			class="case-studies__filter"
			action="#"
			method="get"
			data-case-study-filter
		>
			<label for="case-study-industry-filter"><?php esc_html_e( 'Filtruj po branzy:', 'starter' ); ?></label>
			<select id="case-study-industry-filter" name="industry" data-case-study-filter-select>
				<option value="all"><?php esc_html_e( 'Wszystkie branze', 'starter' ); ?></option>
				<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
					<?php foreach ( $terms as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $selected_industry, $term->slug ); ?>>
							<?php echo esc_html( $term->name ); ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</form>

		<div
			id="case-study-results"
			class="case-studies__results"
			aria-live="polite"
			data-case-study-filter-results
		>
			<?php
			starter_educraft_render_case_study_cards( $query );
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
<?php
get_footer();
