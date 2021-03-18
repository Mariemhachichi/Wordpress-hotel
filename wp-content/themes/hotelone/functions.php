<?php 
/**
 * HotelOne functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Hotelone
 */

 if( ! function_exists( 'hotelone_setup' ) ):
	function hotelone_setup() {
		load_theme_textdomain( 'hotelone', get_template_directory() . '/languages' );
		
		add_theme_support( 'automatic-feed-links' );
		
		add_theme_support( 'title-tag' );
		
		add_post_type_support( 'page', 'excerpt' );
		
		add_theme_support( 'post-thumbnails' );
		
		register_nav_menus( array(
			'primary'      => esc_html__( 'Primary Menu', 'hotelone' ),
		) );
		
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		
		add_editor_style( array( 'css/editor-style.css', hotelone_fonts_url() ) );
		
		add_theme_support( 'custom-logo', array(
            'height'      => 50,
            'width'       => 150,
            'flex-height' => true,
            'flex-width'  => true,
        ) );
		
		$args = array(
			'width'        => 1600,
			'flex-width'   => true,
			'default-image' => get_template_directory_uri() . '/images/section/sub-header.jpg',
			// Header text
			'header-text'   => false,
		);
		add_theme_support( 'custom-header', $args );

		add_theme_support( 'custom-background' );
		
		add_theme_support( 'recommend-plugins', array(
			'britetechs-companion' => array(
                'name' => esc_html__( 'Britetechs Companion', 'hotelone' ),
                'active_filename' => 'britetechs-companion/britetechs-companion.php',
				'desc' => esc_html__( 'We highly recommend that you install the britetechs companion plugin to gain access to the team and testimonial sections.', 'hotelone' ),
            ),
			'contact-form-7' => array(
                'name' => esc_html__( 'Contact Form 7', 'hotelone' ),
                'active_filename' => 'contact-form-7/wp-contact-form-7.php',
				'desc' => esc_html__( 'This is also recommended that you install the contact form plugin to show contact form on Contact Page contact section and Contact custom page template.', 'hotelone' ),
            ),
        ) );
		
		add_theme_support( 'customize-selective-refresh-widgets' );
		
		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio',
		) );
	}
 endif;
 add_action( 'after_setup_theme', 'hotelone_setup' );
 
 
/**
 * @global int $content_width
 */
function hotelone_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'hotelone_content_width', 800 );
}
add_action( 'after_setup_theme', 'hotelone_content_width', 0 );

if ( ! function_exists( 'hotelone_fonts_url' ) ) :
	/**
	 * Register default Google fonts
	 */
	function hotelone_fonts_url() {
	    $fonts_url = '';

	    $Lato = _x( 'on', 'Lato font: on or off', 'hotelone' );
	    $Roboto = _x( 'on', 'Roboto font: on or off', 'hotelone' );
	    $dansing = _x( 'on', 'Dansing Script font: on or off', 'hotelone' );
	    $merriweather = _x( 'on', 'Dansing Script font: on or off', 'hotelone' );

	    if ( 'off' !== $Roboto ) {
	        $font_families = array();

	        if ( 'off' !== $Roboto ) {
	            $font_families[] = 'Roboto:100,200,400,500,600,700,300,100,800,900,italic';
	        }

	        if ( 'off' !== $Lato ) {
	            $font_families[] = 'Lato:100,200,400,500,600,700,300,100,800,900,italic';
	        }

	        if ( 'off' !== $dansing ) {
	            $font_families[] = 'Dancing Script:100,200,400,500,600,700,300,100,800,900,italic';
	        }

	        if ( 'off' !== $merriweather ) {
	            $font_families[] = 'Merriweather:100,200,400,500,600,700,300,100,800,900,italic';
	        }
			
			$option = wp_parse_args(  get_option( 'hotelone_option', array() ), hotelone_reset_data() );
			$b_fontfamily = $option['typo_p_fontfamily'];
			if ( $b_fontfamily ) {
	            $font_families[] = $b_fontfamily . ':100,200,400,500,600,700,300,100,800,900,italic';
	        }
			$m_fontfamily = $option['typo_m_fontfamily'];
			if ( $m_fontfamily ) {
	            $font_families[] = $m_fontfamily . ':100,200,400,500,600,700,300,100,800,900,italic';
	        }
			$h_fontfamily = $option['typo_h_fontfamily'];
			if ( $h_fontfamily ) {
	            $font_families[] = $h_fontfamily . ':100,200,400,500,600,700,300,100,800,900,italic';
	        }else{
	        	$font_families[] = 'Nunito:100,200,400,500,600,700,300,100,800,900,italic';
	        }
			
	        $query_args = array(
	            'family' => urlencode( implode( '|', $font_families ) ),
	            'subset' => urlencode( 'latin,latin-ext' ),
	        );

	        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	    }

	    return esc_url_raw( $fonts_url );
	}
endif;
/**
 * Enqueue scripts and styles for the template.
 */
function hotelone_scripts() {
	$theme = wp_get_theme( 'hotelone' );
    $version = $theme->get( 'Version' );

	$disableGoogleFonts = get_theme_mod('hotelone_hide_g_font', 0 );
	if ( $disableGoogleFonts == false ) {
        wp_enqueue_style('google-fonts', hotelone_fonts_url(), array(), $version);
    }
	
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() .'/css/bootstrap.css', array(), $version );
	
	wp_enqueue_style( 'animate', get_template_directory_uri() .'/css/animate.css', array(), $version );
	
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() .'/css/font-awesome/css/font-awesome.css', array(), '4.4.0' );
	
	wp_enqueue_style( 'hotelone-style', get_template_directory_uri() .'/style.css', array(), $version );
	wp_enqueue_style( 'hotelone-awebooking', get_template_directory_uri() .'/css/awebooking.css', array(), $version, true );
	wp_enqueue_style( 'meanmenu-css', get_template_directory_uri() .'/css/meanmenu.css', array(), $version );

	wp_enqueue_script( 'jquery' );
	
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ), $version, true );
	
	wp_enqueue_script( 'hotelone-page-scroll', get_template_directory_uri() . '/js/page-scroll.js', array(), $version, true );
	
	wp_enqueue_script( 'wow', get_template_directory_uri() . '/js/wow.js', array(), $version, true );

	wp_enqueue_script( 'meanmenu-js', get_template_directory_uri() . '/js/jquery.meanmenu.js', array(), $version, true );
	
	wp_enqueue_script( 'hotelone-custom-js', get_template_directory_uri() . '/js/custom.js', array(), $version, true );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	
	
	// settings.
    $hotelone_settings = array(
        'homeUrl'     => home_url( '/' ),
		'disable_animations' => get_theme_mod('hotelone_animation_hide',false),
		'is_frontpage' => is_front_page(),
    );
	wp_localize_script( 'hotelone-page-scroll', 'hotelone_settings', $hotelone_settings );
}
add_action( 'wp_enqueue_scripts', 'hotelone_scripts' );
// media upload
function hotelone_upload_scripts(){
	 wp_enqueue_media();	
	 wp_enqueue_script('media-upload');
     wp_enqueue_script('thickbox');
     wp_enqueue_script('hotelone-upload_media_widget', get_template_directory_uri() . '/js/upload-media.js', array('jquery'));
	 wp_enqueue_style('thickbox');
}
add_action("admin_enqueue_scripts","hotelone_upload_scripts");
add_action( 'admin_enqueue_scripts', 'hotelone_admin_enqueue_script_function' );
function hotelone_admin_enqueue_script_function(){
	wp_enqueue_style('hotelone-drag-drop', get_template_directory_uri() . '/css/drag-drop.css');
}
function hotelone_widgets_register(){
	
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'hotelone' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div><!-- .widget -->',
		'before_title'  => '<div class="widget_title_area"><h3 class="widget_title">',
		'after_title'   => '</h3></div>',
	) );

	$frontpage_sidebars = array(
		0 => 'Slider',
		1 => 'Service',
		2 => 'Room',
		4 => 'News',
		6 => 'CallToAction',
	);

	if(function_exists('bc_init')){
		$frontpage_sidebars[3] = 'Testimonial';
		$frontpage_sidebars[5] = 'Team';
	}

	foreach ($frontpage_sidebars as $key => $fpsidebar) {
		$fpsidebarid = strtolower($fpsidebar);

		$topcolumn = get_theme_mod('frontpage_'.$fpsidebarid.'_top_layout',12);
		$bottomcolumn = get_theme_mod('frontpage_'.$fpsidebarid.'_bottom_layout',12);

		register_sidebar( array(
			'name'          => sprintf(__( 'FrontPage %s Top Sidebar', 'hotelone' ),$fpsidebar),
			'id'            => 'front-page-'.$fpsidebarid.'-top',
			'description'   => '',
			'before_widget' => '<div class="col-md-'.$topcolumn.' col-sm-6"><div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div></div><!-- .widget -->',
			'before_title'  => '<div class="widget_title_area"><h3 class="widget_title">',
			'after_title'   => '</h3></div>',
		) );

		register_sidebar( array(
			'name'          => sprintf(__( 'FrontPage %s Bottom Sidebar', 'hotelone' ),$fpsidebar),
			'id'            => 'front-page-'.$fpsidebarid.'-bottom',
			'description'   => '',
			'before_widget' => '<div class="col-md-'.$bottomcolumn.' col-sm-6"><div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div></div><!-- .widget -->',
			'before_title'  => '<div class="widget_title_area"><h3 class="widget_title">',
			'after_title'   => '</h3></div>',
		) );
	}
	
	for ( $i = 1; $i<= 4; $i++ ) {
		register_sidebar( array(
			'name'          => sprintf( esc_html__('Footer %s', 'hotelone'), $i ),
			'id'            => 'footer-' . $i,
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div><!-- .widget -->',
			'before_title'  => '<h3 class="widget_title wow animated slideInLeft">',
			'after_title'   => '</h3>',
		) );
	}
	
}
add_action( 'widgets_init', 'hotelone_widgets_register' );

if ( ! function_exists( 'hotelone_register_required_plugins' ) ) :

	function hotelone_register_required_plugins() {

		$plugins = array(
			array(
				'name'               => 'Contact Form 7', // The plugin name.
				'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
				'source'             => '', // The plugin source.
				'required'           => false, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '4.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			),
		);

		$config = array(
			'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.

			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'hotelone' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'hotelone' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'hotelone' ), // %s = plugin name.
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'hotelone' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'hotelone' ), // %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'hotelone' ), // %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %1$s plugin.', 'Sorry, but you do not have the correct permissions to install the %1$s plugins.', 'hotelone' ), // %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'hotelone' ), // %1$s = plugin name(s).
				'notice_ask_to_update_maybe'      => _n_noop( 'There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'hotelone' ), // %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %1$s plugin.', 'Sorry, but you do not have the correct permissions to update the %1$s plugins.', 'hotelone' ), // %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'hotelone' ), // %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'hotelone' ), // %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %1$s plugin.', 'Sorry, but you do not have the correct permissions to activate the %1$s plugins.', 'hotelone' ), // %1$s = plugin name(s).
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'hotelone' ),
				'update_link' 					  => _n_noop( 'Begin updating plugin', 'Begin updating plugins', 'hotelone' ),
				'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'hotelone' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'hotelone' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'hotelone' ),
				'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:', 'hotelone' ),
				'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.', 'hotelone' ),  // %1$s = plugin name(s).
				'plugin_needs_higher_version'     => esc_html__( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'hotelone' ),  // %1$s = plugin name(s).
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %1$s', 'hotelone' ), // %s = dashboard link.
				'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.', 'hotelone' ),
				'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			),

		);

		tgmpa( $plugins, $config );
	}

endif;
add_action( 'tgmpa_register', 'hotelone_register_required_plugins' );

/**
 * reset data file
 */
require get_parent_theme_file_path('/inc/resetdata.php');

/**
 * load template tags file
 */
require get_parent_theme_file_path('/inc/template-tags.php');

/**
 * hotelone nav walker
 */
require get_parent_theme_file_path('/inc/hotelone_navwalker.php');

/**
 * hotelone extra
 */
require get_parent_theme_file_path('/inc/extra.php');


/**
 * hotelone sanitize
 */
require get_parent_theme_file_path('/inc/sanitize.php');

/**
 * customizer register
 */
require get_parent_theme_file_path('/inc/customizer.php');
require get_parent_theme_file_path('/inc/customizer-selective-refresh.php');

/**
 * welcome page
 */
require get_parent_theme_file_path('/inc/about-screen/welcome-screen.php');
require get_parent_theme_file_path('/inc/install/class-install-helper.php');