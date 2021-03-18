<?php
//=============================================================
// Color reset
//=============================================================
if ( ! function_exists( 'ours_restaurant_reset_colors' ) ) :

    function ours_restaurant_reset_colors($data) {


        set_theme_mod('ours_restaurant_top_footer_background_color','#1A1E21');


        set_theme_mod('ours_restaurant_primary_color','#ef4a2b');

        set_theme_mod('ours_restaurant_color_reset_option','do-not-reset');

    }

endif;
add_action( 'ours_restaurant_colors_reset','ours_restaurant_reset_colors', 10 );

