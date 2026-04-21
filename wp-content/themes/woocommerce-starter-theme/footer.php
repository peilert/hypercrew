</main>

<footer class="site-footer">
	<div class="container site-footer__inner">
		<nav class="site-nav site-nav--footer" aria-label="<?php esc_attr_e( 'Footer menu', 'starter' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'menu menu--footer',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
		<p class="site-footer__copyright">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
