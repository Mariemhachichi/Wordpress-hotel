<?php
/**
 * applybutton Info Section
 *
 * @since 1.0.0
 */
$wp_customize->add_section(
    'ours_restaurant_applybutton_info_section',
    array(
        'priority' => 7,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('Book Button Top Option', 'ours-restaurant'),
    )
);


$wp_customize->add_setting(
    'ours_restaurant_button',
    array(
        'default' => $default['ours_restaurant_button'],
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'ours_restaurant_button',
    array(
        'type' => 'text',
        'label' => esc_html__(' Button Text', 'ours-restaurant'),
        'section' => 'ours_restaurant_applybutton_info_section',
        'priority' => 8
    )
);

/**
 * Field for Get Started button Link
 *
 */
$wp_customize->add_setting(
    'ours_restaurant_apply_button_link',
    array(
        'default' => $default['ours_restaurant_apply_button_link'],
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control(
    'ours_restaurant_apply_button_link',
    array(
        'type' => 'url',
        'label' => esc_html__('Button Text Link', 'ours-restaurant'),
        'description' => esc_html__('Use full url link', 'ours-restaurant'),
        'section' => 'ours_restaurant_applybutton_info_section',
        'priority' => 9
    )
);


