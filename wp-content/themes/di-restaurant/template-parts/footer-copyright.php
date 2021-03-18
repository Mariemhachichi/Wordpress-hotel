<div class="container-fluid footer-copyright">
	<div class="container">
		<div class="row">
			<div class="col-md-4 footer-copyright-left">
				<?php
				echo wp_kses_post( do_shortcode( get_theme_mod( 'left_footer_setting', '<p>' . esc_html__( 'Site Title, Some rights reserved.', 'di-restaurant' ) . '</p>' ) ) );
				?> 
			</div>
			<div class="col-md-4 footer-copyright-center">
				<?php
				echo wp_kses_post( do_shortcode( get_theme_mod( 'center_footer_setting', '<p><a href="#">' . esc_html__( 'Terms of Use - Privacy Policy', 'di-restaurant' ) . '</a></p>' ) ) );
				?> 
			</div>
			<div class="col-md-4 footer-copyright-right">
				<?php
				do_action( 'di_restaurant_footer_cprt_right' );
				?>
			</div>
		</div>
	</div>
</div>
