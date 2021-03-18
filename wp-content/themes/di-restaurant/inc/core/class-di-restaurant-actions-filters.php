<?php
/**
 * Di Restaurant Actions Filter. This file is responsible mostly actions and filters.
 *
 * @package Di Restaurant
 */

/**
 * Class Di_Restaurant_Engine.
 */

class Di_Restaurant_Actions_Filter {

	/**
	 * Instance object.
	 *
	 * @var instance
	 */
	public static $instance;

	/**
	 * Get_instance method.
	 *
	 * @return instance instance of the class.
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * [__construct description]
	 */
	public function __construct() {
		add_action( 'di_restaurant_the_head', [ $this, 'the_head' ] );
		add_filter( 'get_calendar', [ $this, 'calendar_css_class' ] );
		add_filter( 'widget_tag_cloud_args', [ $this, 'tag_cloud_fontsize_fix' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'inline_css' ] );
		add_filter( 'body_class', [ $this, 'di_body_class' ] );
		add_action( 'customize_register', [ $this, 'customize_pr_handle' ], 9999999 );
		add_action( 'di_restaurant_page_sidebar_file', [ $this, 'page_sidebar_file' ] );
		add_action( 'di_restaurant_post_sidebar_file', [ $this, 'post_sidebar_file' ] );
		add_action( 'di_restaurant_header_img_file', [ $this, 'header_img_file_clbk' ] );
		add_action( 'di_restaurant_color_options', [ $this, 'color_options_clbk' ] );
		add_action( 'di_restaurant_blog_options', [ $this, 'blog_options_clbk' ] );
		add_action( 'di_restaurant_footer_widget_options', [ $this, 'footer_widget_options_clbk' ] );
		add_action( 'di_restaurant_footer_cprt_right', [ $this, 'footer_cprt_right_contents' ] );
		add_action( 'di_restaurant_footer_copyright_right_setting', [ $this, 'footer_copyright_right_setting_clbk' ] );
		add_action( 'di_restaurant_cutmzr_theme_info', [ $this, 'cutmzr_theme_info_clbk' ] );

		add_action( 'admin_notices',  [ $this, 'admin_notice' ] );
		add_action( 'admin_init', [ $this, 'handle_admin_notice' ] );
		add_action('switch_theme', [ $this, 'delete_user_meta_ignore_notice' ] );

		if( ! is_admin() ) {
			add_filter( 'excerpt_length', [ $this, 'excerpt_length' ] );
			add_filter( 'excerpt_more', [ $this, 'excerpt_more' ] );
		}

		if( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_product_tag_cloud_widget_args', [ $this, 'woo_tag_cloud_fontsize_fix' ] );
		}
	}

	public function admin_notice() {
		global $current_user ;
		$user_id = $current_user->ID;
		
		/* Check that the user hasn't already clicked to ignore the message */
		if( ! get_user_meta( $user_id, 'di_restaurant_ignore_notice' ) ) {
			echo '<div class="updated"><p>';
			
			printf( __( 'Thank you for activating Di Restaurant Theme. Let start from <a target="_blank" href="%1$s">Documentation</a> | <a target="_blank" href="%2$s">Visit Demo</a> | <a target="_blank" href="%3$s">Customize Now</a> | <a href="%4$s">Hide it</a>', 'di-restaurant' ), 'https://dithemes.com/di-restaurant-free-wordpress-theme-documentation/', 'http://demo.dithemes.com/di-restaurant/', esc_url( get_admin_url() . 'customize.php' ) , esc_url( add_query_arg( 'di_restaurant_notics_ignore', '0' ) ) );
			
			echo "</p></div>";
		}
	}

	public function handle_admin_notice() {
		global $current_user;
		$user_id = $current_user->ID;
		if( isset( $_GET['di_restaurant_notics_ignore']) && '0' == $_GET['di_restaurant_notics_ignore'] ) {
			add_user_meta( $user_id, 'di_restaurant_ignore_notice', 'true', true );
		}
	}

	public function delete_user_meta_ignore_notice() {
		global $current_user;
		$user_id = $current_user->ID;
		if( get_user_meta( $user_id, 'di_restaurant_ignore_notice' ) ) {
			delete_user_meta( $user_id, 'di_restaurant_ignore_notice' );
		}
	}

	public function color_options_clbk() {
		Kirki::add_field( 'di_restaurant_config', array(
			'type'        => 'custom',
			'settings'    => 'custom_clroptions_freecon',
			'label'       => esc_attr__( 'More Color Options', 'di-restaurant' ),
			'section'     => 'color_options',
			'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">' . esc_html__( 'More color options are available in', 'di-restaurant' ) . ' <a target="_blank" href="https://dithemes.com/product/di-restaurant-pro-wordpress-theme/">' . esc_html__( 'Di Restaurant Pro', 'di-restaurant' ) . '</a>.</div>',
			'active_callback'  => array(
				array(
					'setting'  => 'endis_ftr_wdgt',
					'operator' => '==',
					'value'    => 1,
				),
			)
		) );
	}

	public function blog_options_clbk() {
		Kirki::add_field( 'di_restaurant_config', array(
			'type'        => 'custom',
			'settings'    => 'custom_blog_options_freecon',
			'label'       => esc_attr__( 'Color Options', 'di-restaurant' ),
			'section'     => 'blog_options',
			'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">' . esc_html__( 'Color options are available in', 'di-restaurant' ) . ' <a target="_blank" href="https://dithemes.com/product/di-restaurant-pro-wordpress-theme/">' . esc_html__( 'Di Restaurant Pro', 'di-restaurant' ) . '</a>.</div>',
			'active_callback'  => array(
				array(
					'setting'  => 'endis_ftr_wdgt',
					'operator' => '==',
					'value'    => 1,
				),
			)
		) );
	}

	public function footer_widget_options_clbk() {
		Kirki::add_field( 'di_restaurant_config', array(
			'type'        => 'custom',
			'settings'    => 'custom_footer_options_freecon',
			'label'       => esc_attr__( 'Color Options', 'di-restaurant' ),
			'section'     => 'footer_options',
			'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">' . esc_html__( 'Color options are available in', 'di-restaurant' ) . ' <a target="_blank" href="https://dithemes.com/product/di-restaurant-pro-wordpress-theme/">' . esc_html__( 'Di Restaurant Pro', 'di-restaurant' ) . '</a>.</div>',
			'active_callback'  => array(
				array(
					'setting'  => 'endis_ftr_wdgt',
					'operator' => '==',
					'value'    => 1,
				),
			)
		) );
	}

	public function the_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="profile" href="http://gmpg.org/xfn/11" />

		<?php if ( is_singular() && pings_open( get_queried_object() ) ) { ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php } ?>
		
		<?php
	}

	public function calendar_css_class( $html ) {
		return str_replace( 'id="wp-calendar"', 'id="wp-calendar" class="table table-bordered"', $html );
	}

	public function tag_cloud_fontsize_fix( $args ) {
		$args['largest']  = 14;
		$args['smallest'] = 14;
		$args['unit']     = 'px';
		return $args;
	}

	public function inline_css() {
		
		$custom_css = "";

		// blog_list_grid
		if( get_theme_mod( 'blog_list_grid', 'list' ) == 'msry2c' ) {
			$custom_css .= "
				@media (min-width: 768px) {
					.dimasonrybox {
						width: 48%;
						margin-right: 2% !important;
					}
				}
				";
		}

		if( get_theme_mod( 'blog_list_grid', 'list' ) == 'msry3c' ) {
			$custom_css .= "
				@media (min-width: 768px) {
					.dimasonrybox {
						width: 31%;
						margin-right: 2% !important;
					}
				}
				";
		}

		// For load icon
		if( get_theme_mod( 'loading_icon_img' ) ) {
			$icon_link =  esc_url( get_theme_mod( 'loading_icon_img' ) );
		} else {
			$icon_link =  esc_url( get_template_directory_uri() . '/assets/images/Preloader_2.gif' );
		}

		$custom_css .= "
		.load-icon {
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999999;
			background: url( '" . $icon_link . "' ) center no-repeat #fff;
		}
		";

		// For disable / enable sticky menu small device.
		if( get_theme_mod( 'stickymenu_setting_lrg', '1' ) == 1 ) { // sticky is enabled for large device
			$custom_css .= "
			@media (min-width: 768px) {
				#navbarouter {
					position: fixed;
					top: 0;
				}

				.sticky_menu_top {
					position: fixed;
					top: 0;
				}

				.header-img-otr {
					min-height: 50px;
				}
			}
			";
		}

		if( get_theme_mod( 'stickymenu_setting_smal', '0' ) == 1 ) { // Sticky enabled for small device
			$custom_css .= "
			@media (max-width: 767px) {
				#navbarouter {
					position: fixed;
					top: 0;
				}

				.sticky_menu_top {
					position: fixed;
					top: 0;
				}

				.header-img-otr {
					min-height: 50px;
				}
			}
			";
		}

		if( get_theme_mod( 'hdricon_endis_smal', '0' ) == 1 ) { // Hide Header Icons for Small Devices - EnDis
			$custom_css .= "
			@media (max-width: 767px) {
				.navbarprimary .social-div {
					display: none;
				}
			}
			";
		}



		wp_add_inline_style( 'di-restaurant-style-core', $custom_css );
	}

	public function di_body_class( $classes ) {
		if( get_theme_mod( 'loading_icon', '0' ) == 1 ) {
			return array_merge( $classes, array( 'overflowhide' ) );
		}
		return array_merge( $classes, array( '' ) );
	}

	public function customize_pr_handle( $wp_customize ) {
		// Let refresh page on logo change.
		$wp_customize->get_setting( 'custom_logo' )->transport 	= 'refresh';

		// Blog name partial refresh handle.
		$wp_customize->get_setting( 'blogname' )->transport          = 'postMessage';
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-name-pr',
			'render_callback' => function() {
				return esc_html( get_bloginfo( 'name' ) );
			},
		) );

		// Blog header_image partial refresh handle.
		$wp_customize->get_setting( 'header_image' )->transport   = 'refresh';
		$wp_customize->selective_refresh->add_partial( 'header_image', array(
			'selector' => '.wp-custom-header',
		) );

		// Top Main menu partial refresh handle.
		$wp_customize->add_setting(
			'top_main_menu_hidden_field', array(
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'top_main_menu_hidden_field', array(
				'priority' => 25,
				'type'     => 'hidden',
				'section'  => 'menu_locations',
			)
		);

		$wp_customize->get_setting( 'top_main_menu_hidden_field' )->transport   = 'refresh';
		$wp_customize->selective_refresh->add_partial( 'top_main_menu_hidden_field', array(
				'selector'	=> '.nav.navbar-nav.primary-menu',
			)
		);

		// For social profile.
		$wp_customize->get_setting( 'sprofile_link_facebook' )->transport   = 'refresh';
		$wp_customize->selective_refresh->add_partial( 'sprofile_link_facebook', array(
				'selector'	=> '.icons-social-div-pr',
			)
		);

		if( class_exists( 'WooCommerce' ) ) {
			// For Woo icons.
			$wp_customize->get_setting( 'shop_icon_endis' )->transport   = 'refresh';
			$wp_customize->selective_refresh->add_partial( 'shop_icon_endis', array(
					'selector'	=> '.woo-social-div-pr',
				)
			);
		}

		// For back to top icon.
		$wp_customize->get_setting( 'back_to_top' )->transport   = 'refresh';
		$wp_customize->selective_refresh->add_partial( 'back_to_top', array(
				'selector'	=> '.back-to-top',
			)
		);

		
	}

	public function page_sidebar_file() {
		if( is_active_sidebar( 'sidebar_page' ) ) {
			dynamic_sidebar( 'sidebar_page' );
		}
	}

	public function post_sidebar_file() {
		if( is_active_sidebar( 'sidebar-1' ) ) {
			dynamic_sidebar( 'sidebar-1' );
		}
	}

	public function footer_copyright_right_setting_clbk() {
		Kirki::add_field( 'di_restaurant_config', array(
			'type'        => 'custom',
			'settings'    => 'custom_footer_copy_options_fc',
			'label'       => esc_attr__( 'Footer Right', 'di-restaurant' ),
			'section'     => 'footer_copy_options',
			'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">' . esc_html__( 'Footer Right Option and Color Options are available in', 'di-restaurant' ) . ' <a target="_blank" href="https://dithemes.com/product/di-restaurant-pro-wordpress-theme/">' . esc_html__( 'Di Restaurant Pro', 'di-restaurant' ) . '</a>.</div>',
		) );
	}

	public function cutmzr_theme_info_clbk() {
		Kirki::add_field( 'di_restaurant_config', array(
			'type'        => 'custom',
			'settings'    => 'custom_theme_info_sprt_fc',
			'label'       => esc_attr__( 'Di Restaurant Support', 'di-restaurant' ),
			'section'     => 'theme_info',
			'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">' . esc_html__( 'If you want our support, Please', 'di-restaurant' ) . ' <a target="_blank" href="https://wordpress.org/support/theme/di-restaurant">' . esc_html__( 'Create a Support Topic', 'di-restaurant' ) . '</a>.</div>',
		) );

		Kirki::add_field( 'di_restaurant_config', array(
			'type'        => 'custom',
			'settings'    => 'custom_theme_info_pro_fc',
			'label'       => esc_attr__( 'Di Restaurant Pro', 'di-restaurant' ),
			'section'     => 'theme_info',
			'default'     => '<div style="background-color: #333;border-radius: 9px;color: #fff;padding: 13px 7px;">' . __( 'Premium Features:<br />- All Color Options<br />- Option to Update Footer Right Credit<br />- Widget Creation and Selection<br />- Advance Header Image<br />- Slider in Header<br />- Premium Support<br />', 'di-restaurant' ) . ' <a target="_blank" href="https://dithemes.com/product/di-restaurant-pro-wordpress-theme/">' . esc_html__( 'Get Di Restaurant Pro', 'di-restaurant' ) . '</a></div>',
		) );
	}

	public function excerpt_length() {
		return absint( get_theme_mod( 'excerpt_length', '40' ) );
	}

	public function excerpt_more( $more ) {
		global $post;
		return '... <div class="readmore-section"><a href="' . esc_url( get_permalink( $post->ID ) ) . '"><input type="submit" name="submit" value="' . __( 'Continue reading', 'di-restaurant' ) . '&#8230;" class="readmore-btn" ></a></div>';

	}

	public function footer_cprt_right_contents() {
		
		printf(
			/* translators: 1: p tag open, 2: p tag close, 3: a tag open and FA icon, 4: a tag close */
			esc_html__( '%1$sWordPress %3$s Di Restaurant%4$s Theme%2$s', 'di-restaurant' ), 
			'<p>',
			'</p>',
			'<a target="_blank" href="https://dithemes.com/di-restaurant-free-wordpress-theme/"><span class="footer_span fa fa-thumbs-o-up"></span>',
			'</a>'
		);

	}

	public function header_img_file_clbk() {
		?>
		<div class="header-img-otr">

			<?php
			// return if disabled using metabox (disabled on individual page || post)
			if( is_home() ) {
				$di_post_id = get_option( 'page_for_posts' );
			} else {
				$di_post_id = get_the_ID();
			}

			if( $di_post_id ) {
				if( get_post_meta( $di_post_id, '_di_restaurant_hide_hdrimg', true ) == '1' ) {
					?>
					</div> <!-- header-img-otr END -->
					<?php
					return;
				}
			}

			if( has_header_image() ) { ?>
				<div class="container-fluid header-img">
					<?php the_custom_header_markup(); ?>
				</div>
			<?php
			}
			?>
			
		</div> <!-- header-img-otr END -->
		<?php
	}







	public function woo_tag_cloud_fontsize_fix( $args ) {
		$args['largest']  = 14;
		$args['smallest'] = 14;
		$args['unit']     = 'px';
		return $args;
	}

}
Di_Restaurant_Actions_Filter::get_instance();
