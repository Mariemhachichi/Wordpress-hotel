<?php

/**
 * Di Restaurant Theme Page.
 *
 * @package Di Restaurant
 */

/**
 * Class Di_Restaurant_Theme_Page.
 */
class Di_Restaurant_Theme_Page {

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
		add_action( 'admin_menu', [ $this, 'theme_page' ] );

		global $pagenow;
		if ( $pagenow === 'themes.php' && $_SERVER['QUERY_STRING'] === 'page=di-restaurant-theme' ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'theme_page_admin_js_css' ] );
		}

	}

	public function theme_page_admin_js_css() {
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), '4.0.0', 'all' );
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.js', array( 'jquery' ), '4.0.0', true );
	}

	public function theme_page() {
		add_theme_page(
			__( 'Di Restaurant Theme', 'di-restaurant' ),
			__( 'Di Restaurant Theme', 'di-restaurant' ),
			'manage_options',
			'di-restaurant-theme',
			[ $this, 'theme_page_callback' ]
		);
	}

	public function theme_page_callback() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Di Restaurant Theme Info', 'di-restaurant' ); ?></h1>
			<br />
			<div class="container-fluid" style="border: 2px dashed #C3C3C3;">
				<div class="row">

					<div class="col-md-6" style="padding:0px;">
						<img class="img-fluid" src="<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>" />
					</div>

					<div class="col-md-6">

						<h2><?php esc_html_e( 'Di Restaurant WordPress Theme', 'di-restaurant' ); ?></h2>

						<p style="font-size:16px;"><?php esc_html_e( 'Di Restaurant is a responsive, fast to load, SEO friendly, WooCommerce ready and customizable WordPress theme for Informational, eCommerce, Restaurants and Hotels websites.', 'di-restaurant' ); ?></p>

						<p style="font-size:16px;"><?php esc_html_e( 'Theme Features: One Click Demo Import, Typography Options, Blog Options, Social Icons, WooCommerce Icons, Footer Widget with 6 Layouts, Footer Copyright Section, Left Sidebar Layout, Right Sidebar Layout, Full Width Layout, Full Width Page Builder Template, Page Builder Plugins Ready, Elementor Page Builder Compatibility, Contact Form 7 Ready, Translation Ready, Sticky Menu and Options, Back to Top Icons, Page Preloader Icon and Options, Quick View, Wishlist, Blog Sidebar, Page Sidebar, WooCommerce Sidebar and much more.', 'di-restaurant' ); ?></p>

						<?php
						if( ! function_exists( 'di_restaurant_pro' ) ) {
						?>
						<p style="font-size:16px;"><b><?php esc_html_e( 'Di Restaurant Pro Features: ', 'di-restaurant' ); ?></b><?php esc_html_e( 'Widget Area Creation and Selection, Advance Header Image Options, Slider in Header, All Color Options, Options to Update Footer Front Credit Link, Premium Support.', 'di-restaurant' ); ?><p>
						<?php
						}
						?>

						<div style="text-align: center;" >

							<a target="_blank" class="btn btn-outline-success btn-sm" href="http://demo.dithemes.com/di-restaurant/" role="button"><?php esc_html_e( 'Theme Demo', 'di-restaurant' ); ?></a>

							<a target="_blank" class="btn btn-outline-success btn-sm" href="https://dithemes.com/di-restaurant-free-wordpress-theme-documentation/" role="button"><?php esc_html_e( 'Theme Docs', 'di-restaurant' ); ?></a>

							<a target="_blank" class="btn btn-outline-success btn-sm" href="<?php echo esc_url( get_dashboard_url() . 'customize.php' ); ?>" role="button"><?php esc_html_e( 'Theme Options', 'di-restaurant' ); ?></a>

							<?php
							if( function_exists( 'di_restaurant_pro' ) ) {
							?>
								<a target="_blank" class="btn btn-outline-success btn-sm" href="https://dithemes.com/my-tickets/" role="button"><?php esc_html_e( 'Get Premium Support', 'di-restaurant' ); ?></a>
							<?php
							} else {
							?>
								<a target="_blank" class="btn btn-outline-success btn-sm" href="https://dithemes.com/product/di-restaurant-pro-wordpress-theme/" role="button"><?php esc_html_e( 'Get Di Restaurant Pro', 'di-restaurant' ); ?></a>
							<?php
							}
							?>

						</div>
						<br />

					</div>
				</div>
			</div>
		</div>
		<?php
	}

}
Di_Restaurant_Theme_Page::get_instance();
