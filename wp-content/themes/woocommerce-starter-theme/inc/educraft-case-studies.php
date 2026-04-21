<?php
/**
 * Case Study domain logic for the EduCraft task.
 *
 * @package StarterTheme
 */

starter_security_check();

if ( ! function_exists( 'starter_educraft_register_case_study_post_type' ) ) {
	function starter_educraft_register_case_study_post_type() {
		$labels = array(
			'name'               => __( 'Case Studies', 'starter' ),
			'singular_name'      => __( 'Case Study', 'starter' ),
			'menu_name'          => __( 'Case Studies', 'starter' ),
			'add_new'            => __( 'Add New', 'starter' ),
			'add_new_item'       => __( 'Add New Case Study', 'starter' ),
			'edit_item'          => __( 'Edit Case Study', 'starter' ),
			'new_item'           => __( 'New Case Study', 'starter' ),
			'view_item'          => __( 'View Case Study', 'starter' ),
			'view_items'         => __( 'View Case Studies', 'starter' ),
			'search_items'       => __( 'Search Case Studies', 'starter' ),
			'not_found'          => __( 'No case studies found.', 'starter' ),
			'not_found_in_trash' => __( 'No case studies found in Trash.', 'starter' ),
		);

		register_post_type(
			'case_study',
			array(
				'labels'             => $labels,
				'public'             => true,
				'has_archive'        => true,
				'rewrite'            => array( 'slug' => 'case-studies' ),
				'menu_icon'          => 'dashicons-analytics',
				'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
				'show_in_rest'       => true,
				'publicly_queryable' => true,
			)
		);
	}
}
add_action( 'init', 'starter_educraft_register_case_study_post_type' );

if ( ! function_exists( 'starter_educraft_register_case_study_taxonomy' ) ) {
	function starter_educraft_register_case_study_taxonomy() {
		$labels = array(
			'name'          => __( 'Branze', 'starter' ),
			'singular_name' => __( 'Branza', 'starter' ),
			'search_items'  => __( 'Search industries', 'starter' ),
			'all_items'     => __( 'All industries', 'starter' ),
			'edit_item'     => __( 'Edit industry', 'starter' ),
			'update_item'   => __( 'Update industry', 'starter' ),
			'add_new_item'  => __( 'Add new industry', 'starter' ),
			'new_item_name' => __( 'New industry name', 'starter' ),
			'menu_name'     => __( 'Branze', 'starter' ),
		);

		register_taxonomy(
			'case_industry',
			array( 'case_study' ),
			array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'branza' ),
			)
		);
	}
}
add_action( 'init', 'starter_educraft_register_case_study_taxonomy' );

if ( ! function_exists( 'starter_educraft_get_case_study_archive_query' ) ) {
	function starter_educraft_get_case_study_archive_query( $industry_slug = 'all' ) {
		$args = array(
			'post_type'      => 'case_study',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'order'          => 'DESC',
			'orderby'        => 'date',
		);

		if ( ! empty( $industry_slug ) && 'all' !== $industry_slug ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'case_industry',
					'field'    => 'slug',
					'terms'    => $industry_slug,
				),
			);
		}

		return new WP_Query( $args );
	}
}

if ( ! function_exists( 'starter_educraft_get_case_study_industry_labels' ) ) {
	function starter_educraft_get_case_study_industry_labels( $post_id = 0 ) {
		$post_id    = $post_id ? (int) $post_id : get_the_ID();
		$industries = get_the_terms( $post_id, 'case_industry' );

		if ( empty( $industries ) || is_wp_error( $industries ) ) {
			return '';
		}

		return implode( ', ', wp_list_pluck( $industries, 'name' ) );
	}
}

if ( ! function_exists( 'starter_educraft_get_case_study_card_image_html' ) ) {
	function starter_educraft_get_case_study_card_image_html( $post_id = 0 ) {
		$post_id    = $post_id ? (int) $post_id : get_the_ID();
		$main_image = starter_educraft_get_field_value( 'main_image', $post_id );

		if ( is_array( $main_image ) && ! empty( $main_image['ID'] ) ) {
			return wp_get_attachment_image(
				(int) $main_image['ID'],
				'medium_large',
				false,
				array(
					'class' => 'case-studies__card-image',
				)
			);
		}

		if ( has_post_thumbnail( $post_id ) ) {
			return get_the_post_thumbnail(
				$post_id,
				'medium_large',
				array(
					'class' => 'case-studies__card-image',
				)
			);
		}

		return '';
	}
}

if ( ! function_exists( 'starter_educraft_render_case_study_cards' ) ) {
	function starter_educraft_render_case_study_cards( WP_Query $query ) {
		if ( ! $query->have_posts() ) {
			echo '<p class="case-studies__empty">' . esc_html__( 'Brak case studies dla wybranej branzy.', 'starter' ) . '</p>';
			return;
		}

		echo '<div class="case-studies__grid">';

		while ( $query->have_posts() ) {
			$query->the_post();

			$client_name       = starter_educraft_get_field_value( 'client_name' );
			$short_description = starter_educraft_get_field_value( 'short_description' );
			$industry_labels   = starter_educraft_get_case_study_industry_labels();
			$card_image_html   = starter_educraft_get_case_study_card_image_html();
			?>
			<article class="case-studies__card" id="post-<?php the_ID(); ?>">
				<?php if ( $card_image_html ) : ?>
					<a class="case-studies__card-media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
						<?php echo wp_kses_post( $card_image_html ); ?>
					</a>
				<?php endif; ?>

				<div class="case-studies__card-body">
					<h2 class="case-studies__card-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<?php if ( $client_name ) : ?>
						<p class="case-studies__meta"><strong><?php esc_html_e( 'Klient:', 'starter' ); ?></strong> <?php echo esc_html( $client_name ); ?></p>
					<?php endif; ?>
					<?php if ( $industry_labels ) : ?>
						<p class="case-studies__meta"><strong><?php esc_html_e( 'Branza:', 'starter' ); ?></strong> <?php echo esc_html( $industry_labels ); ?></p>
					<?php endif; ?>
					<?php if ( $short_description ) : ?>
						<p class="case-studies__summary"><?php echo esc_html( $short_description ); ?></p>
					<?php else : ?>
						<p class="case-studies__summary"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
					<?php endif; ?>
				</div>
			</article>
			<?php
		}

		echo '</div>';
	}
}

if ( ! function_exists( 'starter_educraft_ajax_filter_case_studies' ) ) {
	function starter_educraft_ajax_filter_case_studies() {
		check_ajax_referer( 'starter_case_study_filter', 'nonce' );

		$industry = isset( $_POST['industry'] ) ? sanitize_title( wp_unslash( $_POST['industry'] ) ) : 'all';
		$query    = starter_educraft_get_case_study_archive_query( $industry );

		ob_start();
		starter_educraft_render_case_study_cards( $query );
		$html = ob_get_clean();

		wp_reset_postdata();

		wp_send_json_success(
			array(
				'html'  => $html,
				'count' => (int) $query->found_posts,
			)
		);
	}
}
add_action( 'wp_ajax_starter_filter_case_studies', 'starter_educraft_ajax_filter_case_studies' );
add_action( 'wp_ajax_nopriv_starter_filter_case_studies', 'starter_educraft_ajax_filter_case_studies' );
