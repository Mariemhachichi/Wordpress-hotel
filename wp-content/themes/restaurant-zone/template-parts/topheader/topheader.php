<?php
/**
 * Displays top navigation
 *
 * @package Restaurant Zone
 */
?>

<div class="container">
	<div class="row">
		<div class="col-lg-5 col-md-5">
			<div class="top-info">
				<div class="row">
					<div class="col-lg-6 col-md-6">
						<?php if(get_theme_mod('restaurant_zone_phone_number_info') != ''){ ?>
							<p><i class="fas fa-phone"></i><?php echo esc_html(get_theme_mod('restaurant_zone_phone_number_info','')); ?></p>
						<?php }?>
					</div>
					<div class="col-lg-6 col-md-6">
						<?php if(get_theme_mod('restaurant_zone_email_info') != ''){ ?>
							<p><i class="far fa-envelope"></i><?php echo esc_html(get_theme_mod('restaurant_zone_email_info','')); ?></p>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-7 col-md-7">
			<div class="row">
			  	<div class="col-lg-9 col-md-8">
			  		<div class="social-link">
				  		<?php if(get_theme_mod('restaurant_zone_facebook_url') != ''){ ?>
							<a href="<?php echo esc_url(get_theme_mod('restaurant_zone_facebook_url','')); ?>"><i class="fab fa-facebook-f"></i></a>
						<?php }?>
						<?php if(get_theme_mod('restaurant_zone_twitter_url') != ''){ ?>
							<a href="<?php echo esc_url(get_theme_mod('restaurant_zone_twitter_url','')); ?>"><i class="fab fa-twitter"></i></a>
						<?php }?>
						<?php if(get_theme_mod('restaurant_zone_intagram_url') != ''){ ?>
							<a href="<?php echo esc_url(get_theme_mod('restaurant_zone_intagram_url','')); ?>"><i class="fab fa-instagram"></i></a>
						<?php }?>
						<?php if(get_theme_mod('restaurant_zone_linkedin_url') != ''){ ?>
							<a href="<?php echo esc_url(get_theme_mod('restaurant_zone_linkedin_url','')); ?>"><i class="fab fa-linkedin-in"></i></a>
						<?php }?>
						<?php if(get_theme_mod('restaurant_zone_pintrest_url') != ''){ ?>
							<a href="<?php echo esc_url(get_theme_mod('restaurant_zone_pintrest_url','')); ?>"><i class="fab fa-pinterest-p"></i></a>
						<?php }?>
						<?php if(class_exists('woocommerce')){ ?>
				        	<?php global $woocommerce; ?>
				        	<a class="cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e( 'shopping cart','restaurant-zone' ); ?>"><i class="fas fa-shopping-cart"></i></a>
				        <?php }?>
					</div>
			  	</div>
			  	<div class="col-lg-3 col-md-4 reservation-btn">
					<?php if(get_theme_mod('restaurant_zone_reservation_button') != ''){ ?>
    					<a href="<?php echo esc_url(get_theme_mod('restaurant_zone_reservation_button','')); ?>"><?php esc_html_e('RESERVATION','restaurant-zone'); ?></a>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>