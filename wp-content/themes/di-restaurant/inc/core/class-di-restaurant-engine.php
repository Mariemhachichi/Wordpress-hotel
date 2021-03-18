<?php
/**
 * Di Restaurant Engine. This file is responsible for theme setup, scripts, styles, sidebar registration.
 *
 * @package Di Restaurant
 */

/**
 * Class Di_Restaurant_Engine.
 */
class Di_Restaurant_Engine {

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
		$this->set_constants();
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_and_styles' ) );
		add_action( 'customize_preview_init', array( $this, 'customizer_scripts_and_styles' ) );
		add_action( 'widgets_init', array( $this, 'sidebar_registration' ) );
	}

	/**
	 *  Set constants.
	 */
	public function set_constants() {
		define( 'DI_RESTAURANT_VERSION', wp_get_theme( 'di-restaurant' )->get( 'Version' ) );
	}

	public function setup() {

		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 730;
		}

		load_theme_textdomain( 'di-restaurant', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );

		add_theme_support( 'title-tag' );

		add_theme_support( 'align-wide' );

		add_theme_support( 'customize-selective-refresh-widgets' );

		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1150, 500, true );

		register_nav_menus( array(
			'primary'	=> __( 'Top Main Menu', 'di-restaurant' ),
		) );

		add_theme_support( 'custom-background', array(
			'default-color'      => '#f1f1f1',
			'default-attachment' => 'fixed',
		) );

		add_theme_support( 'custom-header', array(
			'width'         => 1350,
			'height'        => 540,
			'flex-width'    => true,
			'flex-height'   => true,
			'uploads'       => true,
			'header-text'	=> false,
			'default-image'	=> esc_url( get_template_directory_uri() . '/assets/images/mainbg.jpg' ),
		) );

		
		add_theme_support( 'custom-logo', array(
			'height'		=> '65',
			'width'			=> '210',
			'flex-height'	=> true,
			'flex-width'	=> true,
		) );

		
	}

	public function scripts_and_styles() {
		// Load bootstrap css.
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), '4.0.0', 'all' );

		// Load font-awesome file.
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.css', array(), '4.7.0', 'all' );

		// Load default css file.
		wp_enqueue_style( 'di-restaurant-style-default', get_stylesheet_uri(), array( 'bootstrap', 'font-awesome' ), DI_RESTAURANT_VERSION, 'all' );

		// Load css/style.css file.
		wp_enqueue_style( 'di-restaurant-style-core', get_template_directory_uri() . '/assets/css/style.css', array( 'bootstrap', 'font-awesome' ), DI_RESTAURANT_VERSION, 'all' );

		// Load woo if WooCommerce plugin is active.
		if( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style( 'di-restaurant-style-woo', get_template_directory_uri() . '/assets/css/woo.css', array( 'bootstrap', 'font-awesome' ), DI_RESTAURANT_VERSION, 'all' );
		}


		// Load bootstrap js.
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.js', array( 'jquery' ), '4.0.0', true );

		// Load script file.
		wp_enqueue_script( 'di-restaurant-script', get_template_directory_uri() . '/assets/js/script.js', array( 'jquery' ), DI_RESTAURANT_VERSION, true );

		// Load html5shiv.
		wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/assets/js/html5shiv.js', array(), '3.7.3', false );
		wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

		// Load respond js.
		wp_enqueue_script( 'respond', get_template_directory_uri() . '/assets/js/respond.js', array(), DI_RESTAURANT_VERSION, false );
		wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

		// load comment-reply js.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Load masonry.index.js if set blog_list_grid > msry2c || msry3c
		if( get_theme_mod( 'blog_list_grid', 'list' == 'msry2c' ) || get_theme_mod( 'blog_list_grid', 'list' == 'msry3c' ) ) {
			wp_enqueue_script( 'di-restaurant-masonry-index', get_template_directory_uri() . '/assets/js/masonry.index.js', array( 'jquery', 'imagesloaded', 'masonry' ), DI_RESTAURANT_VERSION, true );
		}

		// Load backtotop.js if enabled in customize
		if( get_theme_mod( 'back_to_top', '1' == '1' ) ) {
			wp_enqueue_script( 'di-restaurant-backtotop', get_template_directory_uri() . '/assets/js/backtotop.js', array( 'jquery' ), DI_RESTAURANT_VERSION, true );
		}

		// Load loadicon.js if enabled in customize
		if( get_theme_mod( 'loading_icon', '0' == '1' ) ) {
			wp_enqueue_script( 'di-restaurant-loadicon', get_template_directory_uri() . '/assets/js/loadicon.js', array( 'jquery' ), DI_RESTAURANT_VERSION, true );
		}

		// Do not load main-menu.js on landing page template
		if( ! is_page_template( 'template-landing-page.php' ) ) {
			wp_enqueue_script( 'di-restaurant-mainmenu', get_template_directory_uri() . '/assets/js/main-menu.js', array( 'jquery' ), DI_RESTAURANT_VERSION, true );
		}

	}

	/**
	 * [customizer_scripts_and_styles description]
	 * @return [type] [description]
	 */
	public function customizer_scripts_and_styles() {

		wp_enqueue_style( 'di-restaurant-customize-preview', get_template_directory_uri() . '/assets/css/customizer.css', array( 'customize-preview' ), DI_RESTAURANT_VERSION, 'all' );

		wp_enqueue_script( 'di-restaurant-customize-preview', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), DI_RESTAURANT_VERSION, true );

	}


	/**
	 * Sidebar_registration.
	 */
	public function sidebar_registration() {

		register_sidebar( array(
			'name'			=> __( 'Blog Sidebar', 'di-restaurant' ),
			'id'			=> 'sidebar-1',
			'description'	=> __( 'Widgets for Blog sidebar. If you are using Full Width Layout in customize, then this sidebar will not display.', 'di-restaurant' ),
			'before_widget'	=> '<div id="%1$s" class="widgets_sidebar clearfix %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h3 class="widgets_sidebar_title">',
			'after_title'	=> '</h3>',
		) );
		
		
		register_sidebar( array(
			'name'			=> __( 'Page Sidebar', 'di-restaurant' ),
			'id'			=> 'sidebar_page',
			'description'	=> __( 'Widgets for Page sidebar. Choose Sidebar Template to display it. Page edit screen > Page Attributes > Template.', 'di-restaurant' ),
			'before_widget'	=> '<div id="%1$s" class="widgets_sidebar clearfix %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h3 class="widgets_sidebar_title">',
			'after_title'	=> '</h3>',
		) );

		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar( array(
				'name'			=> __( 'WooCommerce Sidebar', 'di-restaurant' ),
				'id'			=> 'sidebar_woo',
				'description'	=> __( 'Widgets for WooCommerce Pages (For:- Product Loop, Product Search and Product Single Page). If you are using Full Width Layout in customize, then this sidebar will not display.', 'di-restaurant' ),
				'before_widget'	=> '<div id="%1$s" class="widgets_sidebar clearfix %2$s">',
				'after_widget'	=> '</div>',
				'before_title'	=> '<h3 class="widgets_sidebar_title">',
				'after_title'	=> '</h3>',
			) );
		}

		// Footer widget register base on settings.
		$enordis = absint( get_theme_mod( 'endis_ftr_wdgt', '0' ) );
		$layout = absint( get_theme_mod( 'ftr_wdget_lyot', '3' ) );
		if ( $enordis != 0 ) {
			if ( $layout == 48 || $layout == 84 ) {
				register_sidebar( array(
					'name'			=> __( 'Footer Widget 1', 'di-restaurant' ),
					'id'			=> 'footer_1',
					'description'	=> __( 'Widgets for footer 1', 'di-restaurant' ),
					'before_widget'	=> '<div id="%1$s" class="widgets_footer clearfix %2$s">',
					'after_widget'	=> '</div>',
					'before_title'	=> '<h3 class="widgets_footer_title">',
					'after_title'	=> '</h3>',
				) );

				register_sidebar( array(
					'name'			=> __( 'Footer Widget 2', 'di-restaurant' ),
					'id'			=> 'footer_2',
					'description'	=> __( 'Widgets for footer 2', 'di-restaurant' ),
					'before_widget'	=> '<div id="%1$s" class="widgets_footer clearfix %2$s">',
					'after_widget'	=> '</div>',
					'before_title'	=> '<h3 class="widgets_footer_title">',
					'after_title'	=> '</h3>',
				) );
			} else {
				for ( $i = 1; $i <= $layout; $i++ ) {
					register_sidebar( array(
						'name'			=> __( 'Footer Widget ', 'di-restaurant' ) . $i,
						'id'			=> 'footer_' . $i,
						'description'	=> __( 'Widgets for footer ', 'di-restaurant' ) . $i,
						'before_widget'	=> '<div id="%1$s" class="widgets_footer clearfix %2$s">',
						'after_widget'	=> '</div>',
						'before_title'	=> '<h3 class="widgets_footer_title">',
						'after_title'	=> '</h3>',
					) );
				}
			}
		}
	
	}
}
Di_Restaurant_Engine::get_instance();
