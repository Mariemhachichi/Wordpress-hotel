<?php
/**
 * Sanitizing the select callback example
 *
 * @since 1.0.0
 *
 * @see sanitize_key()               https://developer.wordpress.org/reference/functions/sanitize_key/
 * @see $wp_customize->get_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/get_control/
 *
 * @param $input
 * @param $setting
 * @return sanitized output
 */
if ( !function_exists('ours_restaurant_sanitize_select') ) :
    function ours_restaurant_sanitize_select( $input, $setting ) {

        // Ensure input is a slug.
        $input = sanitize_text_field( $input );

        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control( $setting->id )->choices;

        // If the input is a valid key, return it; otherwise, return the default.
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
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

/*santized repeater */

function ours_restaurant_sanitize_repeater($input){

    $input_decoded = json_decode( $input, true );
    $allowed_html = array(
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'a' => array(
            'href' => array(),
            'class' => array(),
            'id' => array(),
            'target' => array()
        ),
        'button' => array(
            'class' => array(),
            'id' => array()
        )
    );


    if(!empty($input_decoded)) {
        foreach ($input_decoded as $boxes => $box ){
            foreach ($box as $key => $value){
                $input_decoded[$boxes][$key] = sanitize_text_field( $value );
            }
        }

        return json_encode($input_decoded);
    }

    return $input;
}
