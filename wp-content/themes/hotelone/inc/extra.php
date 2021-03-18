<?php
if ( ! function_exists( 'hotelone_get_layout' ) ) {
    function hotelone_get_layout( $default = 'right' ) {
        $layout = get_theme_mod( 'hotelone_layout', 'right' );
        return apply_filters( 'hotelone_get_layout', $layout, $default );
    }
}

if ( ! function_exists( 'hotelone_get_media_url' ) ) {
    function hotelone_get_media_url($media = array(), $size = 'full' )
    {
        $media = wp_parse_args( $media, array('url' => '', 'id' => ''));
        $url = '';
        if ($media['id'] != '') {
            if ( strpos( get_post_mime_type( $media['id'] ), 'image' ) !== false ) {
                $image = wp_get_attachment_image_src( $media['id'],  $size );
                if ( $image ){
                    $url = $image[0];
                }
            } else {
                $url = wp_get_attachment_url( $media['id'] );
            }
        }

        if ($url == '' && $media['url'] != '') {
            $id = attachment_url_to_postid( $media['url'] );
            if ( $id ) {
                if ( strpos( get_post_mime_type( $id ), 'image' ) !== false ) {
                    $image = wp_get_attachment_image_src( $id,  $size );
                    if ( $image ){
                        $url = $image[0];
                    }
                } else {
                    $url = wp_get_attachment_url( $id );
                }
            } else {
                $url = $media['url'];
            }
        }
        return $url;
    }
}


if ( ! function_exists( 'hotelone_custom_excerpt_length' ) ) :
/**
 * Custom excerpt length
 */
function hotelone_custom_excerpt_length( $length ) {
	
	if( is_admin() ){
		return $length;
	}
	return 30;
}
add_filter( 'excerpt_length', 'hotelone_custom_excerpt_length', 999 );
endif;


if ( ! function_exists( 'hotelone_new_excerpt_more' ) ) :
/**
 * Remove […]
 */
function hotelone_new_excerpt_more( $more ) {
	
	if( is_admin() ){
		return $more;
	}
	
	$textagign = 'center';	
	return sprintf(
		' ... <div class="text-'.esc_attr( $textagign ).'"><a class="more-link" href="%s">%1s <i class="fa fa-angle-double-right"></i></a></div>',
		esc_url( get_the_permalink() ),
		__('Read More','hotelone')
		);
}
add_filter('excerpt_more', 'hotelone_new_excerpt_more');
endif;