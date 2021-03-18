<?php
/**
 * Business Header Info Section
 *
 */
$wp_customize->add_section(
    'ours_restaurant_top_header_info_section',
    array(
        'priority' => 6,
        'capability' => 'edit_theme_options',
        'title' => esc_html__('Top Header Info', 'ours-restaurant'),
    )
);

$wp_customize->add_setting(
    'ours_restaurant_top_header_section',
    array(
        'default' => $default['ours_restaurant_top_header_section'],
        'sanitize_callback' => 'ours_restaurant_sanitize_select',
    )
);

$hide_show_top_header_option = ours_restaurant_slider_option();
$wp_customize->add_control(
    'ours_restaurant_top_header_section',
    array(
        'type' => 'radio',
        'label' => esc_html__('Top Header Info Option', 'ours-restaurant'),
        'description' => esc_html__('Show/hide Option for Top Header Info Section.', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'choices' => $hide_show_top_header_option,
        'priority' => 5
    )
);






/**
 * Field for icon location
 *
 */
$wp_customize->add_setting(
    'ours_restaurant_info_header_section_location_icon',
    array(
        'default' => $default['ours_restaurant_info_header_section_location_icon'],
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    'ours_restaurant_info_header_section_location_icon',
    array(
        'type' => 'text',
        'description' => esc_html__('Insert Font Awesome Icon For Location', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 6
    )
);

/**
 * Field for Company Info Phone Number
 *
 */
$wp_customize->add_setting(
    'ours_restaurant_info_header_location',
    array(
        'default' => $default['ours_restaurant_info_header_location'],
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'ours_restaurant_info_header_location',
    array(
        'type' => 'text',
        'label' => esc_html__('Address', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 7
    )
);




/**
 * Field for Font Awesome Icon Icon
 *
 */
$wp_customize->add_setting(
    'ours_restaurant_info_header_section_phone_number_icon',
    array(
        'default' => $default['ours_restaurant_info_header_section_phone_number_icon'],
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    'ours_restaurant_info_header_section_phone_number_icon',
    array(
        'type' => 'text',
        'description' => esc_html__('Insert Font Awesome Icon For Phone', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 8
    )
);

/**
 * Field for Company Info Phone Number
 *
 */
$wp_customize->add_setting(
    'ours_restaurant_info_header_phone_no',
    array(
        'default' => $default['ours_restaurant_info_header_phone_no'],
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'ours_restaurant_info_header_phone_no',
    array(
        'type' => 'text',
        'label' => esc_html__('Phone Number', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 9
    )
);

/**
 * Field for Fonsome Icon
 *
 */
$wp_customize->add_setting(
    'ours_restaurant_email_icon',
    array(
        'default' => $default['ours_restaurant_email_icon'],
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control(
    'ours_restaurant_email_icon',
    array(
        'type' => 'text',
        'description' => esc_html__('Insert Font Awesome Icon Class For Email', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 10
    )
);

/**
 * Field for Company Info Email Address
 *
 */
$wp_customize->add_setting(
    'ours_restaurant_info_header_email',
    array(
        'default' => $default['ours_restaurant_info_header_email'],
        'sanitize_callback' => 'sanitize_email',
    )
);
$wp_customize->add_control(
    'ours_restaurant_info_header_email',
    array(
        'type' => 'email',
        'label' => esc_html__('Email Address', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 10
    )
);






/**
 *   Show/Hide Social Link
 */
$wp_customize->add_setting(
    'ours_restaurant_social_link_hide_option',
    array(
        'default' => $default['ours_restaurant_social_link_hide_option'],
        'sanitize_callback' => 'ours_restaurant_sanitize_checkbox',
    )
);
$wp_customize->add_control('ours_restaurant_social_link_hide_option',
    array(
        'label' => esc_html__('Hide/Show Social Menu', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'type' => 'checkbox',
        'priority' => 11
    )
);





/**
 * Field for Get Started facebook Link
 *
 */
$wp_customize->add_setting(
    'ours_restaurant_facebook_url',
    array(
        'default' => $default['ours_restaurant_facebook_url'],
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control(
    'ours_restaurant_facebook_url',
    array(
        'type' => 'url',
        'label' => esc_html__('Facebook Url Link', 'ours-restaurant'),
        'description' => esc_html__('Use full url link', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 9
    )
);

/**
 * Field for Get Started facebook Link
 *
 */
$wp_customize->add_setting(
    'ours_restaurant_facebook_url',
    array(
        'default' => $default['ours_restaurant_facebook_url'],
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control(
    'ours_restaurant_facebook_url',
    array(
        'type' => 'url',
        'label' => esc_html__('Facebook Url Link', 'ours-restaurant'),
        'description' => esc_html__('Use full url link', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 11
    )
);

$wp_customize->add_setting(
    'ours_restaurant_youtube_url',
    array(
        'default' => $default['ours_restaurant_youtube_url'],
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control(
    'ours_restaurant_youtube_url',
    array(
        'type' => 'url',
        'label' => esc_html__('Youtube Url Link', 'ours-restaurant'),
        'description' => esc_html__('Use full url link', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 12
    )
);


$wp_customize->add_setting(
    'ours_restaurant_linkedin_url',
    array(
        'default' => $default['ours_restaurant_linkedin_url'],
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control(
    'ours_restaurant_linkedin_url',
    array(
        'type' => 'url',
        'label' => esc_html__('linkedin Url Link', 'ours-restaurant'),
        'description' => esc_html__('Use full url link', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 13
    )
);



$wp_customize->add_setting(
    'ours_restaurant_twitter_url',
    array(
        'default' => $default['ours_restaurant_twitter_url'],
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control(
    'ours_restaurant_twitter_url',
    array(
        'type' => 'url',
        'label' => esc_html__('twitter Url Link', 'ours-restaurant'),
        'description' => esc_html__('Use full url link', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 14
    )
);



$wp_customize->add_setting(
    'ours_restaurant_instagram_url',
    array(
        'default' => $default['ours_restaurant_instagram_url'],
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control(
    'ours_restaurant_instagram_url',
    array(
        'type' => 'url',
        'label' => esc_html__('Instagram Url Link', 'ours-restaurant'),
        'description' => esc_html__('Use full url link', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 14
    )
);


$wp_customize->add_setting(
    'ours_restaurant_google_plus_url',
    array(
        'default' => $default['ours_restaurant_google_plus_url'],
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control(
    'ours_restaurant_google_plus_url',
    array(
        'type' => 'url',
        'label' => esc_html__('Google Plus Url Link', 'ours-restaurant'),
        'description' => esc_html__('Use full url link', 'ours-restaurant'),
        'section' => 'ours_restaurant_top_header_info_section',
        'priority' => 14
    )
);
