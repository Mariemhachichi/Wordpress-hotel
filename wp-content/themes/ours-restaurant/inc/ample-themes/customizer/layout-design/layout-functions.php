<?php
if (!function_exists('ours_restaurant_sidebar_layout')) :
    function ours_restaurant_sidebar_layout()
    {
        $ours_restaurant_sidebar_layout = array(
            'right-sidebar' => esc_html__('Right Sidebar', 'ours-restaurant'),
            'left-sidebar' => esc_html__('Left Sidebar', 'ours-restaurant'),
            'no-sidebar' => esc_html__('No Sidebar', 'ours-restaurant'),
        );
        return apply_filters('ours_restaurant_sidebar_layout', $ours_restaurant_sidebar_layout);
    }
endif;

/**
 * Blog/Archive Description Option
 *
 * @since Business agency 1.0.0
 *
 *
 * 
 * @param null
 * @return array $ours_restaurant_blog_excerpt
 *
 */
if (!function_exists('ours_restaurant_blog_excerpt')) :
    function ours_restaurant_blog_excerpt()
    {
        $ours_restaurant_blog_excerpt = array(
            'excerpt' => esc_html__('Excerpt', 'ours-restaurant'),
            'content' => esc_html__('Content', 'ours-restaurant'),

        );
        return apply_filters('ours_restaurant_blog_excerpt', $ours_restaurant_blog_excerpt);
    }
endif;

/**
 * Show/Hide Feature Image  options
 *
 * @since Business agency 1.0.0
 *
 * @param null
 * @return array $ours_restaurant_show_feature_image_option
 *
 */
if (!function_exists('ours_restaurant_show_feature_image_option')) :
    function ours_restaurant_show_feature_image_option()
    {
        $ours_restaurant_show_feature_image_option = array(
            'show' => esc_html__('Show', 'ours-restaurant'),
            'hide' => esc_html__('Hide', 'ours-restaurant')
        );
        return apply_filters('ours_restaurant_show_feature_image_option', $ours_restaurant_show_feature_image_option);
    }
endif;
