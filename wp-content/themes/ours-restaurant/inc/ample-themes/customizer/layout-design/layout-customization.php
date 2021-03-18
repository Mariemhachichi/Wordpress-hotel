<?php
/**
 * Layout/Design Option
 *
 */
$wp_customize->add_panel(
    'ours_restaurant_design_layout_options',
    array(
        'priority' => 7,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__(' Layout/Design Option', 'ours-restaurant'),
    )
);

/*-------------------------------------------------------------------------------------------*/
/**
 * Sidebar Option
 *
 */
$wp_customize->add_section(
    'ours_restaurant_default_sidebar_layout_option',
    array(
        'title' => esc_html__('Default Sidebar Layout Option', 'ours-restaurant'),
        'panel' => 'ours_restaurant_design_layout_options',
        'priority' => 6,
    )
);

/**
 * Sidebar Option
 */
$wp_customize->add_setting(
    'ours_restaurant_sidebar_layout_option',
    array(
        'default' => $default['ours_restaurant_sidebar_layout_option'],
        'sanitize_callback' => 'ours_restaurant_sanitize_select',
    )
);


$layout = ours_restaurant_sidebar_layout();
$wp_customize->add_control('ours_restaurant_sidebar_layout_option',
    array(
        'label' => esc_html__('Default Sidebar Layout', 'ours-restaurant'),
        'description' => esc_html__('Home/front page does not have sidebar. Inner pages like blog, archive single page/post Sidebar Layout. However single page/post sidebar can be overridden.', 'ours-restaurant'),
        'section' => 'ours_restaurant_default_sidebar_layout_option',
        'type' => 'select',
        'choices' => $layout,
        'priority' => 10
    )
);


/*-------------------------------------------------------------------------------------------*/

/**
 * Blog/Archive Layout Option
 *
 * @since 1.0.0
 */
$wp_customize->add_section(
    'ours_restaurant_blog_archive_layout_option',
    array(
        'title' => esc_html__('Blog/Archive Layout Option', 'ours-restaurant'),
        'panel' => 'ours_restaurant_design_layout_options',
        'priority' => 7,
    )
);


/**
 * Blog Page Title
 */
$wp_customize->add_setting(
    'ours_restaurant_blog_title_option',
    array(
        'default' => $default['ours_restaurant_blog_title_option'],
        'sanitize_callback' => 'sanitize_text_field',
    )
);

$wp_customize->add_control('ours_restaurant_blog_title_option',
    array(
        'label' => esc_html__('Blog Page Title', 'ours-restaurant'),
        'section' => 'ours_restaurant_blog_archive_layout_option',
        'type' => 'text',
        'priority' => 11
    )
);

/**
 * Blog/Archive excerpt option
 */
$wp_customize->add_setting(
    'ours_restaurant_blog_excerpt_option',
    array(
        'default' => $default['ours_restaurant_blog_excerpt_option'],
        'sanitize_callback' => 'ours_restaurant_sanitize_select',
    )
);
$blogexcerpt = ours_restaurant_blog_excerpt();
$wp_customize->add_control('ours_restaurant_blog_excerpt_option',
    array(
        'choices' => $blogexcerpt,
        'label' => esc_html__('Show Description From', 'ours-restaurant'),
        'section' => 'ours_restaurant_blog_archive_layout_option',
        'type' => 'select',
        'priority' => 8
    )
);

/**
 * Description Length In Words
 */
$wp_customize->add_setting(
    'ours_restaurant_description_length_option',
    array(
        'default' => $default['ours_restaurant_description_length_option'],
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control('ours_restaurant_description_length_option',
    array(
        'label' => esc_html__('Description Length In Words', 'ours-restaurant'),
        'section' => 'ours_restaurant_blog_archive_layout_option',
        'type' => 'number',
        'priority' => 12
    )
);
/*-------------------------------------------------------------------------------------------*/
/**
 * Feature Image Option
 *
 */
$wp_customize->add_section(
    'ours_restaurant_feature_image_info_option',
    array(
        'title' => esc_html__('Feature Image Option Post / Page', 'ours-restaurant'),
        'panel' => 'ours_restaurant_design_layout_options',
        'priority' => 6,
    )
);


/**
 * Feature Image Option Single page
 */
$wp_customize->add_setting(
    'ours_restaurant_show_feature_image_single_option',
    array(
        'default' => $default['ours_restaurant_show_feature_image_single_option'],
        'sanitize_callback' => 'ours_restaurant_sanitize_select',
    )
);

$hide_show_feature_image_option = ours_restaurant_show_feature_image_option();
$wp_customize->add_control(
    'ours_restaurant_show_feature_image_single_option',
    array(
        'type' => 'radio',
        'label' => esc_html__('Show/Hide Image For Page/post', 'ours-restaurant'),
        'section' => 'ours_restaurant_feature_image_info_option',
        'choices' => $hide_show_feature_image_option,
        'priority' => 5
    )
);



  

	