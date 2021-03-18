<?php get_header(); ?>

<div id="site-content" class="site-content">
	<div class="container">		
		<div class="row">
			<div class="col-md-12 error-page text-center">
				<h2 class="wow animated fadeInDown"><?php esc_html_e('404','hotelone'); ?></h2>
				<h4 class="wow animated fadeInLeft"><?php esc_html_e('Oops! Page not found','hotelone'); ?></h4>
				<p class="wow animated fadeInUp"><?php esc_html_e('We`re sorry, but the page you are looking for doesn`t exist.','hotelone'); ?></p>
				<a class="hotel-btn hotel-primary wow animated slideInLeft" href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fa fa-arrow-left"></i> <?php _e('Back','hotelone'); ?></a>
			</div>
		</div>				
	</div>
</div><!-- .site-content -->
	
<?php get_footer(); ?>