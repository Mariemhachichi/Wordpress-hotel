<?php

/**
 * Slider  options
 * @param null
 * @return array $ours_restaurant_slider_option
 *
 */
if (!function_exists('ours_restaurant_slider_option')) :
    function ours_restaurant_slider_option()
    {
        $ours_restaurant_slider_option = array(
            'show' => esc_html__('Show', 'ours-restaurant'),
            'hide' => esc_html__('Hide', 'ours-restaurant')
        );
        return apply_filters('ours_restaurant_slider_option', $ours_restaurant_slider_option);
    }
endif;


/**
 * Sanitize checkbox field
 *
 * @param $checked
 * @return Boolean
 */
if ( !function_exists('ours_restaurant_sanitize_checkbox') ) :
    function ours_restaurant_sanitize_checkbox( $checked ) {
        // Boolean check.
        return ( ( isset( $checked ) && true == $checked ) ? true : false );
    }
endif;