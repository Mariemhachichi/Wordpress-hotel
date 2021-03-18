<?php
/**
 * Theme Option
 *
 * @since 1.0.0
 */
$wp_customize->add_panel(
    'ours_restaurant_theme_options',
    array(
        'priority' => 7,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Theme Option', 'ours-restaurant'),
    )
);


/*----------------------------------------------------------------------------------------------*/
/**
 * Color Options
 *
 * @since 1.0.0
 */

$wp_customize->add_setting(
    'ours_restaurant_top_color',
    array(
        'default' => $default['ours_restaurant_top_color'],
        'sanitize_callback' => 'sanitize_hex_color',
    )
);

$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ours_restaurant_top_color', array(
    'label' => esc_html__('Top Bar Background Color', 'ours-restaurant'),
    'description' => esc_html__('We recommend choose  different  background color but not to choose similar to font color', 'ours-restaurant'),
    'section' => 'colors',
    'priority' => 14,

)));


$wp_customize->add_setting(
    'ours_restaurant_primary_color',
    array(
        'default' => $default['ours_restaurant_primary_color'],
        'sanitize_callback' => 'sanitize_hex_color',
    )
);

$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ours_restaurant_primary_color', array(
    'label' => esc_html__('Primary Color', 'ours-restaurant'),
    'description' => esc_html__('We recommend choose  different  background color but not to choose similar to font color', 'ours-restaurant'),
    'section' => 'colors',
    'priority' => 14,

)));


/*-----------------------------------------------------------------------------*/
/**
 * Top Footer Background Color
 *
 * @since 1.0.0
 */

$wp_customize->add_setting(
    'ours_restaurant_top_footer_background_color',
    array(
        'default' => $default['ours_restaurant_top_footer_background_color'],
        'sanitize_callback' => 'sanitize_hex_color',

    )
);

$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ours_restaurant_top_footer_background_color', array(
    'label' => esc_html__('Top Footer Background Color', 'ours-restaurant'),
    'description' => esc_html__('We recommend choose  different  background color but not to choose similar to font color', 'ours-restaurant'),
    'section' => 'colors',
    'priority' => 14,

)));


/*-------------------------------------------------------------------------------------------*/
/**
 * Hide Static page in Home page
 *
 * @since 1.0.0
 */
$wp_customize->add_section(
    'ours_restaurant_front_page_option',
    array(
        'title' => esc_html__('Front Page Options', 'ours-restaurant'),
        'panel' => 'ours_restaurant_theme_options',
        'priority' => 6,
    )
);

/**
 *   Show/Hide Static page/Posts in Home page
 */
$wp_customize->add_setting(
    'ours_restaurant_front_page_hide_option',
    array(
        'default' => $default['ours_restaurant_front_page_hide_option'],
        'sanitize_callback' => 'ours_restaurant_sanitize_checkbox',
    )
);
$wp_customize->add_control('ours_restaurant_front_page_hide_option',
    array(
        'label' => esc_html__('Hide Blog Posts or Static Page on Front Page', 'ours-restaurant'),
        'section' => 'ours_restaurant_front_page_option',
        'type' => 'checkbox',
        'priority' => 10
    )
);


/*-------------------------------------------------------------------------------------------*/
/**
 * Breadcrumb Options
 *
 * @since 1.0.0
 */
$wp_customize->add_section(
    'ours_restaurant_breadcrumb_option',
    array(
        'title' => esc_html__('Breadcrumb Options', 'ours-restaurant'),
        'panel' => 'ours_restaurant_theme_options',
        'priority' => 6,
    )
);

/**
 * Breadcrumb Option
 */
$wp_customize->add_setting(
    'ours_restaurant_breadcrumb_setting_option',
    array(
        'default' => $default['ours_restaurant_breadcrumb_setting_option'],
        'sanitize_callback' => 'ours_restaurant_sanitize_select',

    )
);
$hide_show_breadcrumb_option = ours_restaurant_show_breadcrumb_option();
$wp_customize->add_control('ours_restaurant_breadcrumb_setting_option',
    array(
        'label' => esc_html__('Breadcrumb/header Image Options', 'ours-restaurant'),
        'section' => 'ours_restaurant_breadcrumb_option',
        'choices' => $hide_show_breadcrumb_option,
        'type' => 'select',
        'priority' => 10
    )
);



/*-------------------------------------------------------------------------------------------*/

/**
 * Reset Options
 *
 * @since 1.0.0
 */
$wp_customize->add_section(
    'ours_restaurant_reset_option',
    array(
        'title' => esc_html__('Color Reset Options', 'ours-restaurant'),
        'panel' => 'ours_restaurant_theme_options',
        'priority' => 14,
    )
);

/**
 * Reset Option
 */
$wp_customize->add_setting(
    'ours_restaurant_color_reset_option',
    array(
        'default' => $default['ours_restaurant_color_reset_option'],
        'sanitize_callback' => 'ours_restaurant_sanitize_select',
        'transport' => 'postMessage'
    )
);
$reset_option = ours_restaurant_reset_option();
$wp_customize->add_control('ours_restaurant_color_reset_option',
    array(
        'label' => esc_html__('Reset Options', 'ours-restaurant'),
        'description' => sprintf( esc_html__('Caution: Reset theme settings according to the given options. Refresh the page after saving to view the effects', 'ours-restaurant')),
        'section' => 'ours_restaurant_reset_option',
        'type' => 'select',
        'choices' => $reset_option,
        'priority' => 20
    )
);

