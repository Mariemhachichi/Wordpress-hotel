<?php
if( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Di Restaurant WooCommerce.
 *
 * @package Di Restaurant
 */

/**
 * Class Di_Restaurant_Woo.
 */
class Di_Restaurant_Woo {

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
		add_action( 'after_setup_theme', [ $this, 'woo_setup' ] );
		add_action( 'wp_loaded', [ $this, 'woo_features_support' ] );
		add_filter( 'loop_shop_per_page', [ $this, 'product_per_page' ], 20 );
		add_filter('loop_shop_columns', [ $this, 'loop_shop_columns' ] );
		add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );
	}

	public function woo_setup() {
		add_theme_support( 'woocommerce' );
	}

	public function woo_features_support() {

		if( get_theme_mod( 'support_gallery_zoom', '1' ) == 1 ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		}

		if( get_theme_mod( 'support_gallery_lightbox', '1' ) == 1 ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		}

		if( get_theme_mod( 'support_gallery_slider', '1' ) == 1 ) {
			add_theme_support( 'wc-product-gallery-slider' );
		}

		if( get_theme_mod( 'display_related_prdkt', '1' ) == 0 ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}

		if( get_theme_mod( 'order_again_btn', '1' ) == '0' ) {
			remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );
		}


	}

	public function product_per_page( $cols ) {
		$cols = absint( get_theme_mod( 'product_per_page', '12' ) );
		return $cols;
	}

	public function loop_shop_columns() {
		return absint( get_theme_mod( 'product_per_column', '4' ) );
	}

	public function related_products_args() {
		$args['posts_per_page'] = absint( get_theme_mod( 'product_per_column', '4' ) );
		$args['columns'] = 4;
		return $args;
	}

	
}
Di_Restaurant_Woo::get_instance();
