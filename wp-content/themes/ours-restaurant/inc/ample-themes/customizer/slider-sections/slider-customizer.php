<?php
/**
 * HomePage Settings Panel on customizer
 */
$wp_customize->add_panel(
    'ours_restaurant_homepage_settings_panel',
    array(
        'priority' => 5,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => esc_html__('HomePage Slider Settings', 'ours-restaurant'),
    )
);

/*-------------------------------------------------------------------------------------------------*/
/**
 * Slider Section
 *
 */
$wp_customize->add_section(
    'ours_restaurant_slider_section',
    array(
        'title' => esc_html__('Slider Section', 'ours-restaurant'),
        'panel' => 'ours_restaurant_homepage_settings_panel',
        'priority' => 6,
    )
);

/**
 * Show/Hide option for Homepage Slider Section
 *
 */

$wp_customize->add_setting(
    'ours_restaurant_homepage_slider_option',
    array(
        'default' => $default['ours_restaurant_homepage_slider_option'],
        'sanitize_callback' => 'ours_restaurant_sanitize_select',
    )
);
$hide_show_option = ours_restaurant_slider_option();
$wp_customize->add_control(
    'ours_restaurant_homepage_slider_option',
    array(
        'type' => 'radio',
        'label' => esc_html__('Slider Option', 'ours-restaurant'),
        'description' => esc_html__('Show/hide option for homepage Slider Section.', 'ours-restaurant'),
        'section' => 'ours_restaurant_slider_section',
        'choices' => $hide_show_option,
        'priority' => 7
    )
);


/**
 * List All Pages
 */
$slider_pages = array();
$slider_pages_obj = get_pages();
$slider_pages[''] = esc_html__('Select Page','ours-restaurant');
foreach ($slider_pages_obj as $page_id) {
    $slider_pages[$page_id->ID] = $page_id->post_title;
}


/*repeter call */
$wp_customize->add_setting('ours_restaurant_banner_all_sliders', array(
    'sanitize_callback' => 'ours_restaurant_sanitize_repeater',
    'default' => json_encode(array(
        array(
            'selectpage' => '',//field
            'button_text' => '',
            'button_url' => ''
        )
    ))
));

$wp_customize->add_control(new ours_restaurant_Repeater_Controler($wp_customize, 'ours_restaurant_banner_all_sliders', array(
        'label' =>esc_html__('Slider Settings Area', 'ours-restaurant'),
        'section' => 'ours_restaurant_slider_section',
        'settings' => 'ours_restaurant_banner_all_sliders',
        'ours_restaurant_box_label' =>esc_html__('Slider Settings Options', 'ours-restaurant'),
        'ours_restaurant_box_add_control' =>esc_html__('Add New Slider', 'ours-restaurant'),
    ),
        array(
            'selectpage' => array(
                'type' => 'select',
                'label' =>esc_html__('Select Slider Page', 'ours-restaurant'),
                'options' => $slider_pages//array
            ),
            'button_text' => array(
                'type' => 'text',
                'label' =>esc_html__('Enter Button Text', 'ours-restaurant'),
                'default' => ''
            ),
            'button_url' => array(
                'type' => 'text',
                'label' => esc_html__('Enter Button Url', 'ours-restaurant'),
                'default' => ''
            ),

        )
    )
);

	