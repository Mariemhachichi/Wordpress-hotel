<?php
/**
 * Copyright Info Section
 *
 * @since 1.0.0
 */
$wp_customize->add_section(
    'ours_restaurant_copyright_info_section',
    array(
        'priority' => 10,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Footer Option', 'ours-restaurant'),
    )
);

/**
 * Field for Copyright
 *
 * @since 1.0.0
 */
$wp_customize->add_setting(
    'ours_restaurant_copyright',
    array(
        'default' => $default['ours_restaurant_copyright'],
        'sanitize_callback' => 'wp_kses_post',
    )
);
$wp_customize->add_control(
    'ours_restaurant_copyright',
    array(
        'type' => 'text',
        'label' => esc_html__('Copyright', 'ours-restaurant'),
        'section' => 'ours_restaurant_copyright_info_section',
        'priority' => 8
    )
);

