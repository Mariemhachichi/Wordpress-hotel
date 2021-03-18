<?php
/**
 * Restaurant Zone Theme Customizer
 *
 * @link: https://developer.wordpress.org/themes/customize-api/customizer-objects/
 *
 * @package Restaurant Zone
 */

use WPTRT\Customize\Section\Restaurant_Zone_Button;

add_action( 'customize_register', function( $manager ) {

    $manager->register_section_type( Restaurant_Zone_Button::class );

    $manager->add_section(
        new Restaurant_Zone_Button( $manager, 'restaurant_zone_pro', [
            'title'       => __( 'Restaurant Zone Pro', 'restaurant-zone' ),
            'priority'    => 0,
            'button_text' => __( 'GET PREMIUM', 'restaurant-zone' ),
            'button_url'  => esc_url( 'https://www.themagnifico.net/themes/restaurant-wordpress-theme/', 'restaurant-zone')
        ] )
    );

} );

// Load the JS and CSS.
add_action( 'customize_controls_enqueue_scripts', function() {

    $version = wp_get_theme()->get( 'Version' );

    wp_enqueue_script(
        'restaurant-zone-customize-section-button',
        get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/js/customize-controls.js' ),
        [ 'customize-controls' ],
        $version,
        true
    );

    wp_enqueue_style(
        'restaurant-zone-customize-section-button',
        get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/css/customize-controls.css' ),
        [ 'customize-controls' ],
        $version
    );

} );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function restaurant_zone_customize_register($wp_customize){
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        // Site title
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector' => '.site-title',
            'render_callback' => 'restaurant_zone_customize_partial_blogname',
        ));
    }

    // Top Header
    $wp_customize->add_section('restaurant_zone_top_header',array(
        'title' => esc_html__('Top Header','restaurant-zone'),
        'description' => esc_html__('Topbar content','restaurant-zone'),
    ));

    $wp_customize->add_setting('restaurant_zone_phone_number_info',array(
        'default' => '',
        'sanitize_callback' => 'restaurant_zone_sanitize_phone_number'
    )); 
    $wp_customize->add_control('restaurant_zone_phone_number_info',array(
        'label' => esc_html__('Phone Number','restaurant-zone'),
        'section' => 'restaurant_zone_top_header',
        'setting' => 'restaurant_zone_phone_number_info',
        'type'  => 'text'
    ));

    $wp_customize->add_setting('restaurant_zone_email_info',array(
        'default' => '',
        'sanitize_callback' => 'sanitize_email'
    )); 
    $wp_customize->add_control('restaurant_zone_email_info',array(
        'label' => esc_html__('Email Address','restaurant-zone'),
        'section' => 'restaurant_zone_top_header',
        'setting' => 'restaurant_zone_email_info',
        'type'  => 'text'
    ));

    $wp_customize->add_setting('restaurant_zone_reservation_button',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    )); 
    $wp_customize->add_control('restaurant_zone_reservation_button',array(
        'label' => esc_html__('Reservation Page Link','restaurant-zone'),
        'section' => 'restaurant_zone_top_header',
        'setting' => 'restaurant_zone_reservation_button',
        'type'  => 'url'
    ));

    // Social Link
    $wp_customize->add_section('restaurant_zone_social_link',array(
        'title' => esc_html__('Social Links','restaurant-zone'),
        'description' => esc_html__('Social Contact','restaurant-zone'),
    ));

    $wp_customize->add_setting('restaurant_zone_facebook_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    )); 
    $wp_customize->add_control('restaurant_zone_facebook_url',array(
        'label' => esc_html__('Facebook Link','restaurant-zone'),
        'section' => 'restaurant_zone_social_link',
        'setting' => 'restaurant_zone_facebook_url',
        'type'  => 'url'
    ));

    $wp_customize->add_setting('restaurant_zone_twitter_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    )); 
    $wp_customize->add_control('restaurant_zone_twitter_url',array(
        'label' => esc_html__('Twitter Link','restaurant-zone'),
        'section' => 'restaurant_zone_social_link',
        'setting' => 'restaurant_zone_twitter_url',
        'type'  => 'url'
    ));

    $wp_customize->add_setting('restaurant_zone_intagram_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    )); 
    $wp_customize->add_control('restaurant_zone_intagram_url',array(
        'label' => esc_html__('Intagram Link','restaurant-zone'),
        'section' => 'restaurant_zone_social_link',
        'setting' => 'restaurant_zone_intagram_url',
        'type'  => 'url'
    ));

    $wp_customize->add_setting('restaurant_zone_linkedin_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    )); 
    $wp_customize->add_control('restaurant_zone_linkedin_url',array(
        'label' => esc_html__('Linkedin Link','restaurant-zone'),
        'section' => 'restaurant_zone_social_link',
        'setting' => 'restaurant_zone_linkedin_url',
        'type'  => 'url'
    ));

    $wp_customize->add_setting('restaurant_zone_pintrest_url',array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    )); 
    $wp_customize->add_control('restaurant_zone_pintrest_url',array(
        'label' => esc_html__('Pinterest Link','restaurant-zone'),
        'section' => 'restaurant_zone_social_link',
        'setting' => 'restaurant_zone_pintrest_url',
        'type'  => 'url'
    ));

    //Slider
    $wp_customize->add_section('restaurant_zone_top_slider',array(
        'title' => esc_html__('Slider Settings','restaurant-zone'),
        'description' => esc_html__('Here you have to add 3 different pages in below dropdown. Note: Image Dimensions 1200 x 550 px','restaurant-zone')
    ));

    for ( $count = 1; $count <= 3; $count++ ) {

        $wp_customize->add_setting( 'restaurant_zone_top_slider_page' . $count, array(
            'default'           => '',
            'sanitize_callback' => 'restaurant_zone_sanitize_dropdown_pages'
        ) );
        $wp_customize->add_control( 'restaurant_zone_top_slider_page' . $count, array(
            'label'    => __( 'Select Slide Page', 'restaurant-zone' ),
            'section'  => 'restaurant_zone_top_slider',
            'type'     => 'dropdown-pages'
        ) );
    }

    //Menu Items Section
    $wp_customize->add_section( 'restaurant_zone_menu_items_section' , array(
        'title'      => __( 'Popular item Settings ', 'restaurant-zone' ),
        'priority'   => null
    ) );

    $wp_customize->add_setting('restaurant_zone_title', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('restaurant_zone_title', array(
        'label' => __('Title', 'restaurant-zone'),
        'section' => 'restaurant_zone_menu_items_section',
        'type' => 'text',
    ));

    $wp_customize->add_setting( 'restaurant_zone_image_page', array(
        'default'           => '',
        'sanitize_callback' => 'restaurant_zone_sanitize_dropdown_pages'
    ) );
    $wp_customize->add_control( 'restaurant_zone_image_page', array(
        'label'    => __( 'Select Page', 'restaurant-zone' ),
        'description' => __('Image Size 575 x 415','restaurant-zone'),
        'section'  => 'restaurant_zone_menu_items_section',
        'type'     => 'dropdown-pages'
    ) );

    $categories = get_categories();
    $cat_post = array();
    $cat_post[]= 'select';
    $i = 0; 
    foreach($categories as $category){
        if($i==0){
            $default = $category->slug;
            $i++;
        }
        $cat_post[$category->slug] = $category->name;
    }

    $wp_customize->add_setting('restaurant_zone_menu_items',array(
        'default'   => 'select',
        'sanitize_callback' => 'restaurant_zone_sanitize_choices',
    ));
    $wp_customize->add_control('restaurant_zone_menu_items',array(
        'type'    => 'select',
        'choices' => $cat_post,
        'label' => __('Select Category to display Services','restaurant-zone'),
        'description' => __('Image Size 110 x 110','restaurant-zone'),
        'section' => 'restaurant_zone_menu_items_section',
    ));
    
    // Footer
    $wp_customize->add_section('restaurant_zone_site_footer_section', array(
        'title' => esc_html__('Footer', 'restaurant-zone'),
    ));

    $wp_customize->add_setting('restaurant_zone_footer_text_setting', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('restaurant_zone_footer_text_setting', array(
        'label' => __('Replace the footer text', 'restaurant-zone'),
        'section' => 'restaurant_zone_site_footer_section',
        'priority' => 1,
        'type' => 'text',
    ));
}
add_action('customize_register', 'restaurant_zone_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function restaurant_zone_customize_partial_blogname(){
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function restaurant_zone_customize_partial_blogdescription(){
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function restaurant_zone_customize_preview_js(){
    wp_enqueue_script('restaurant-zone-customizer', esc_url(get_template_directory_uri()) . '/assets/js/customizer.js', array('customize-preview'), '20151215', true);
}
add_action('customize_preview_init', 'restaurant_zone_customize_preview_js');