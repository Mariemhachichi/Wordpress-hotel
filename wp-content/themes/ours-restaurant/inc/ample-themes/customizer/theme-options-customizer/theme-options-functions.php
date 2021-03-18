<?php
/**
 * Breadcrumb  display option options
 * @param null
 * @return array $ours_restaurant_show_breadcrumb_option
 *
 */
if (!function_exists('ours_restaurant_show_breadcrumb_option')) :
    function ours_restaurant_show_breadcrumb_option()
    {
        $ours_restaurant_show_breadcrumb_option = array(
            'enable' => esc_html__('Enable', 'ours-restaurant'),
            'disable' => esc_html__('Disable', 'ours-restaurant')
        );
        return apply_filters('ours_restaurant_show_breadcrumb_option', $ours_restaurant_show_breadcrumb_option);
    }
endif;


/**
 * Reset Option
 *
 *
 * @param null
 * @return array $ours_restaurant_reset_option
 *
 */
if (!function_exists('ours_restaurant_reset_option')) :
    function ours_restaurant_reset_option()
    {
        $ours_restaurant_reset_option = array(
            'do-not-reset' => esc_html__('Do Not Reset', 'ours-restaurant'),
            'reset-all' => esc_html__('Reset All', 'ours-restaurant'),
        );
        return apply_filters('ours_restaurant_reset_option', $ours_restaurant_reset_option);
    }
endif;



/**
 * Sanitize Multiple Category
 * =====================================
 */

if ( !function_exists('ours_restaurant_sanitize_multiple_category') ) :

    function ours_restaurant_sanitize_multiple_category( $values )
    {

        $multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;

        return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
    }

endif;
