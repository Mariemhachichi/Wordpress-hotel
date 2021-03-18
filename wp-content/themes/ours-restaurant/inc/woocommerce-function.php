<?php
// To change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'ours_restaurant_custom_single_add_to_cart_text' );
function ours_restaurant_custom_single_add_to_cart_text() {
return __( 'Order Now', 'ours-restaurant' );
}

// To change add to cart text on product archives(Collection) page
add_filter( 'woocommerce_product_add_to_cart_text', 'ours_restaurant_custom_product_add_to_cart_text' );
function ours_restaurant_custom_product_add_to_cart_text() {
return __( 'Order Now', 'ours-restaurant' );
}


/*woo commerce */
function ours_restaurant_get_image_size( $name ) {
    global $_wp_additional_image_sizes;

    if ( isset( $_wp_additional_image_sizes[$name] ) )
        return $_wp_additional_image_sizes[$name];

    return false;
}

function ours_restaurant_woocommerce_placeholder_img_src( $image_size = '' ) {

    if($image_size == ''){
        return apply_filters( 'woocommerce_placeholder_img_src', get_template_directory_uri() . '/assest/images/placeholder.png' );
    } else {

        $size           = ours_restaurant_get_image_size($image_size);
        $size['width']  = isset( $size['width'] ) ? $size['width'] : '';
        $size['height'] = isset( $size['height'] ) ? $size['height'] : '';


        return apply_filters( 'woocommerce_placeholder_img_src', get_template_directory_uri() . '/assest/images/placeholder.-'.$size['width'].'x'.$size['height'].'.png' );
    }
}