<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) : ?>
		<?php $theme_icons_uri = get_template_directory_uri() . '/icons'; ?>
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( $theme_icons_uri . '/apple-touch-icon.png' ); ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $theme_icons_uri . '/favicon-32x32.png' ); ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $theme_icons_uri . '/favicon-16x16.png' ); ?>">
		<link rel="icon" href="<?php echo esc_url( $theme_icons_uri . '/favicon.ico' ); ?>" sizes="any">
		<link rel="manifest" href="<?php echo esc_url( $theme_icons_uri . '/site.webmanifest' ); ?>">
		<meta name="theme-color" content="#0f5bd8">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<div class="container site-header__inner">
		<div class="site-branding">
			<?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
			<?php endif; ?>
		</div>

		<nav class="site-nav site-nav--primary" aria-label="<?php esc_attr_e( 'Primary menu', 'starter' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'menu menu--primary',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<nav class="site-nav site-nav--secondary" aria-label="<?php esc_attr_e( 'Secondary menu', 'starter' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'secondary',
					'container'      => false,
					'menu_class'     => 'menu menu--secondary',
					'fallback_cb'    => false,
				)
			);
			?>
			<?php if ( function_exists( 'WC' ) && WC()->cart ) : ?>
				<a class="site-cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
					<?php esc_html_e( 'Koszyk', 'starter' ); ?> (<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>)
				</a>
			<?php endif; ?>
		</nav>
	</div>
</header>

<main id="main" class="site-main">
