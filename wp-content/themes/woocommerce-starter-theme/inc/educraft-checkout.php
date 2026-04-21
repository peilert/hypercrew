<?php
/**
 * WooCommerce checkout customizations for the EduCraft task.
 *
 * @package StarterTheme
 */

starter_security_check();

if ( ! function_exists( 'starter_educraft_get_b2b_term_ids' ) ) {
	function starter_educraft_get_b2b_term_ids() {
		$term_ids = array();

		$term_by_slug = get_term_by( 'slug', 'b2b', 'product_cat' );
		if ( $term_by_slug && ! is_wp_error( $term_by_slug ) ) {
			$term_ids[] = (int) $term_by_slug->term_id;
		}

		$term_by_name = get_term_by( 'name', 'B2B', 'product_cat' );
		if ( $term_by_name && ! is_wp_error( $term_by_name ) ) {
			$term_ids[] = (int) $term_by_name->term_id;
		}

		return array_values( array_unique( array_filter( $term_ids ) ) );
	}
}

if ( ! function_exists( 'starter_educraft_product_has_b2b_category' ) ) {
	function starter_educraft_product_has_b2b_category( $product_id ) {
		$product_id   = (int) $product_id;
		$b2b_term_ids = starter_educraft_get_b2b_term_ids();

		if ( ! $product_id || empty( $b2b_term_ids ) ) {
			return false;
		}

		$product_term_ids = wc_get_product_term_ids( $product_id, 'product_cat' );

		return ! empty( array_intersect( $b2b_term_ids, $product_term_ids ) );
	}
}

if ( ! function_exists( 'starter_educraft_cart_has_b2b_product' ) ) {
	function starter_educraft_cart_has_b2b_product() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return false;
		}

		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$product_id   = ! empty( $cart_item['product_id'] ) ? (int) $cart_item['product_id'] : 0;
			$variation_id = ! empty( $cart_item['variation_id'] ) ? (int) $cart_item['variation_id'] : 0;
			$parent_id    = $variation_id ? (int) wp_get_post_parent_id( $variation_id ) : 0;

			$candidate_ids = array_filter(
				array(
					$product_id,
					$variation_id,
					$parent_id,
				)
			);

			foreach ( $candidate_ids as $candidate_id ) {
				if ( starter_educraft_product_has_b2b_category( $candidate_id ) ) {
					return true;
				}
			}
		}

		return false;
	}
}

if ( ! function_exists( 'starter_educraft_add_nip_checkout_field' ) ) {
	function starter_educraft_add_nip_checkout_field( $fields ) {
		if ( ! starter_educraft_cart_has_b2b_product() ) {
			return $fields;
		}

		$fields['billing']['billing_nip'] = array(
			'label'       => __( 'NIP', 'starter' ),
			'type'        => 'text',
			'required'    => true,
			'class'       => array( 'form-row-wide' ),
			'priority'    => 125,
			'placeholder' => __( '1234567890', 'starter' ),
		);

		return $fields;
	}
}
add_filter( 'woocommerce_checkout_fields', 'starter_educraft_add_nip_checkout_field' );

if ( ! function_exists( 'starter_educraft_normalize_nip' ) ) {
	function starter_educraft_normalize_nip( $nip_raw ) {
		$nip = strtoupper( trim( (string) $nip_raw ) );
		$nip = preg_replace( '/^PL/i', '', $nip );

		return preg_replace( '/[^0-9]/', '', $nip );
	}
}

if ( ! function_exists( 'starter_educraft_is_valid_nip' ) ) {
	function starter_educraft_is_valid_nip( $nip ) {
		if ( ! preg_match( '/^\d{10}$/', $nip ) ) {
			return false;
		}

		$weights      = array( 6, 5, 7, 2, 3, 4, 5, 6, 7 );
		$checksum_sum = 0;

		for ( $index = 0; $index < 9; $index++ ) {
			$checksum_sum += $weights[ $index ] * (int) $nip[ $index ];
		}

		$control_digit = $checksum_sum % 11;

		if ( 10 === $control_digit ) {
			return false;
		}

		return $control_digit === (int) $nip[9];
	}
}

if ( ! function_exists( 'starter_educraft_validate_checkout_nip' ) ) {
	function starter_educraft_validate_checkout_nip() {
		if ( ! starter_educraft_cart_has_b2b_product() ) {
			return;
		}

		$nip_raw = isset( $_POST['billing_nip'] ) ? wp_unslash( $_POST['billing_nip'] ) : '';
		$nip     = starter_educraft_normalize_nip( $nip_raw );

		if ( '' === $nip ) {
			wc_add_notice( __( 'Pole NIP jest wymagane, gdy w koszyku sa produkty B2B.', 'starter' ), 'error' );
			return;
		}

		if ( ! starter_educraft_is_valid_nip( $nip ) ) {
			wc_add_notice( __( 'Podany NIP ma niepoprawny format lub sume kontrolna.', 'starter' ), 'error' );
		}
	}
}
add_action( 'woocommerce_checkout_process', 'starter_educraft_validate_checkout_nip' );

if ( ! function_exists( 'starter_educraft_render_classic_checkout_for_custom_fields' ) ) {
	function starter_educraft_render_classic_checkout_for_custom_fields( $content ) {
		if ( is_admin() || ! function_exists( 'is_checkout' ) ) {
			return $content;
		}

		if ( ! is_checkout() || is_order_received_page() || is_wc_endpoint_url( 'order-pay' ) ) {
			return $content;
		}

		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$checkout_page_id = function_exists( 'wc_get_page_id' ) ? (int) wc_get_page_id( 'checkout' ) : 0;
		$current_page_id  = (int) get_queried_object_id();

		if ( $checkout_page_id <= 0 || $checkout_page_id !== $current_page_id ) {
			return $content;
		}

		if ( function_exists( 'has_block' ) && has_block( 'woocommerce/checkout', $checkout_page_id ) ) {
			return do_shortcode( '[woocommerce_checkout]' );
		}

		return $content;
	}
}
add_filter( 'the_content', 'starter_educraft_render_classic_checkout_for_custom_fields', 20 );

if ( ! function_exists( 'starter_educraft_save_nip_order_meta' ) ) {
	function starter_educraft_save_nip_order_meta( $order ) {
		if ( ! isset( $_POST['billing_nip'] ) ) {
			return;
		}

		$nip = starter_educraft_normalize_nip( wp_unslash( $_POST['billing_nip'] ) );
		if ( '' !== $nip ) {
			$order->update_meta_data( '_billing_nip', $nip );
		}
	}
}
add_action( 'woocommerce_checkout_create_order', 'starter_educraft_save_nip_order_meta', 10 );

if ( ! function_exists( 'starter_educraft_show_nip_in_admin_order' ) ) {
	function starter_educraft_show_nip_in_admin_order( $order ) {
		$nip = $order->get_meta( '_billing_nip' );
		if ( ! $nip ) {
			return;
		}
		?>
		<p><strong><?php esc_html_e( 'NIP', 'starter' ); ?>:</strong> <?php echo esc_html( $nip ); ?></p>
		<?php
	}
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'starter_educraft_show_nip_in_admin_order' );
