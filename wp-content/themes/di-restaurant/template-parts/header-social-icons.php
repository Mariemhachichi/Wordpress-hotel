<div class="social-div">

	<span class="woo-social-div-pr">
		<?php
		if( class_exists( 'WooCommerce' ) ) {
			echo "<span class='woo_icons_ctmzr'>";
			if( get_theme_mod( 'shop_icon_endis', '1' ) ) {
				?>
				<a title="<?php esc_attr_e( 'Shop', 'di-restaurant' ); ?>" class="social-icon" href="<?php echo esc_url( get_permalink( get_option('woocommerce_shop_page_id') ) ); ?>"><span class="fa fa-shopping-bag bgtoph-icon-clr"></span></a>
				<?php
			}

			if( get_theme_mod( 'cart_icon_endis', '1' ) ) {
				?>
				<a title="<?php esc_attr_e( 'Cart', 'di-restaurant' ); ?>" class="social-icon" href="<?php echo esc_url( get_permalink( get_option('woocommerce_cart_page_id') ) ); ?>"><span class="fa fa-shopping-cart bgtoph-icon-clr"></span></a>
				<?php
			}

			if( get_theme_mod( 'myaccount_icon_endis', '1' ) ) {
				?>
				<a title="<?php esc_attr_e( 'My Account', 'di-restaurant' ); ?>" class="social-icon" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>"><span class="fa fa-user bgtoph-icon-clr"></span></a>
				<?php
			}
			echo "</span>";
		}
		?>
	</span>


	<?php
	if( get_theme_mod( 'sprofile_link_endis', '1' ) == 1 ) {

		if( get_theme_mod( 'sprofile_link_ntabs', '1' ) == 1 ) {
			$s_link_tab = 'target="_blank"';
		} else {
			$s_link_tab = '';
		}
	?>

	<span class="icons-social-div-pr">

		<?php
		if( get_theme_mod( 'sprofile_link_facebook', 'http://facebook.com' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Facebook', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_facebook', 'http://facebook.com' ) ); ?>"><span class="fa fa-facebook bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_twitter', 'http://twitter.com' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Twitter', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_twitter', 'http://twitter.com' ) ); ?>"><span class="fa fa-twitter bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_youtube', 'http://youtube.com' ) ) {
			?>
			<a title="<?php esc_attr_e( 'YouTube', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_youtube', 'http://youtube.com' ) ); ?>"><span class="fa fa-youtube bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_vk' ) ) {
			?>
			<a title="<?php esc_attr_e( 'VK', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_vk' ) ); ?>"><span class="fa fa-vk bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_okru' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Ok.ru', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_okru' ) ); ?>"><span class="fa fa-odnoklassniki bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_linkedin' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Linkedin', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_linkedin' ) ); ?>"><span class="fa fa-linkedin bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_pinterest' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Pinterest', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_pinterest' ) ); ?>"><span class="fa fa-pinterest bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_instagram' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Instagram', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_instagram' ) ); ?>"><span class="fa fa-instagram bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_telegram' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Telegram', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_telegram' ) ); ?>"><span class="fa fa-telegram bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_snapchat' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Snapchat', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_snapchat' ) ); ?>"><span class="fa fa-snapchat bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_flickr' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Flickr', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_flickr' ) ); ?>"><span class="fa fa-flickr bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_reddit' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Reddit', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_reddit' ) ); ?>"><span class="fa fa-reddit bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_tumblr' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Tumblr', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_tumblr' ) ); ?>"><span class="fa fa-tumblr bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_yelp' ) ) {
			?>
			<a title="<?php esc_attr_e( 'Yelp', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> class="social-icon" href="<?php echo esc_url( get_theme_mod( 'sprofile_link_yelp' ) ); ?>"><span class="fa fa-yelp bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_whatsappno' ) ) {
			?>
			<a class="whatsapp-large social-icon" rel="nofollow" title="<?php esc_attr_e( 'WhatsApp', 'di-restaurant' ); ?>" <?php echo $s_link_tab; ?> href="https://web.whatsapp.com/send?text=&phone=<?php echo esc_attr( get_theme_mod( 'sprofile_link_whatsappno' ) ); ?>&abid=<?php echo esc_attr( get_theme_mod( 'sprofile_link_whatsappno' ) ); ?>"><span class="fa fa-whatsapp bgtoph-icon-clr"></span></a>

			<a class="whatsapp-small social-icon" rel="nofollow" title="<?php esc_attr_e( 'WhatsApp', 'di-restaurant' ); ?>" <?php echo $s_link_tab; ?> href="whatsapp://send?text=&phone=<?php echo esc_attr( get_theme_mod( 'sprofile_link_whatsappno' ) ); ?>&abid=<?php echo esc_attr( get_theme_mod( 'sprofile_link_whatsappno' ) ); ?>"><span class="fa fa-whatsapp bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		<?php
		if( get_theme_mod( 'sprofile_link_skype' ) ) {
			?>
			<a class="social-icon" title="<?php esc_attr_e( 'Skype', 'di-restaurant' ); ?>" rel="nofollow" <?php echo $s_link_tab; ?> href="skype:<?php echo esc_attr( get_theme_mod( 'sprofile_link_skype' ) ); ?>?add"><span class="fa fa-skype bgtoph-icon-clr"></span></a>
			<?php
		}
		?>

		</span>

	<?php
	}
	?>

</div>
