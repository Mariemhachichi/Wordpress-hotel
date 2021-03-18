<?php
/**
 * Dynamic css
 *
 * @package Ample Themes
 * @subpackage Business agency
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('ours_restaurant_dynamic_css') ):
    function ours_restaurant_dynamic_css(){


        $ours_restaurant_top_footer_color = esc_attr( ours_restaurant_get_option('ours_restaurant_top_footer_background_color') );


        $ours_restaurant_primary_color = esc_attr( ours_restaurant_get_option('ours_restaurant_primary_color') );
        $ours_restaurant_top_background_color = esc_attr( ours_restaurant_get_option('ours_restaurant_top_color') );
        


        $custom_css = '';

        $custom_css .= ".top-header{
           background: " . $ours_restaurant_top_background_color . ";}
           
    ";

        $custom_css .= "#team .member, #faq #accordion .card:hover .card-header, .read-more-background,.search-button button, a.btn-get-started.scrollto,.section-header h3::after,#faq #accordion .card-header .btn[aria-expanded=\"true\"],#portfolio #portfolio-flters li:hover, #call-to-action .cta-btn:hover,.back-to-top{
           background: " . $ours_restaurant_primary_color .'!important'. ";}
           
    ";
        $custom_css .= ".nav-menu li:hover > a, .nav-menu > .menu-active > a,#services .icon i,#services .box:hover .title a,.contact-page-content ul li .fa,a:hover, a:active, a:focus,a{
    
           color: " . $ours_restaurant_primary_color . ";}
    ";



        $custom_css .= "#about .about-col .icon,#testimonials .owl-dot.active,.post-rating, .line > span, .service-icon div, .widget-ours-restaurant-theme-counter, .portfolioFilter .current, .portfolioFilter a:hover, .paralex-btn:hover, .view-more:hover, .features-slider .owl-theme .owl-controls .owl-page.active span, .widget-ours-restaurant-theme-testimonial .owl-theme .owl-controls .owl-page.active span, .read-more-background, .widget-ours-restaurant-theme-testimonial, .widget-ours-restaurant-theme-meetbutton, .footer-tags a:hover, .ample-inner-banner, .widget-search .search-submit:hover,  .pagination-blog .pagination > .active > a, .pagination-blog .pagination > li > a:hover, .scrollup, .widget_search .search-submit, posts-navigation .nav-previous,  .wpcf7-form input.wpcf7-submit

 {
    
           background-color: " . $ours_restaurant_primary_color .'!important'. ";}
           
    ";

        $custom_css .= "#footer .footer-top{
         background-color: " . $ours_restaurant_top_footer_color . ";}
    ";






        $custom_css .= "..icon-box--description .fa{
         border-color: " . $ours_restaurant_primary_color .'!important'. ";}
    ";
        

        $custom_css .= ".post-rating,.line > span, .service-icon div, .widget-ours-restaurant-theme-counter, .portfolioFilter .current, .portfolioFilter a:hover, .paralex-btn:hover, .view-more:hover, .features-slider .owl-theme .owl-controls .owl-page.active span, .widget-ours-restaurant-theme-testimonial .owl-theme .owl-controls .owl-page.active span, .read-more-background, .widget-ours-restaurant-theme-testimonial, .widget-ours-restaurant-theme-meetbutton, .footer-tags a:hover, .ample-inner-banner,  .widget-search .search-submit:hover,  .pagination-blog .pagination > .active > a, .pagination-blog .pagination > li > a:hover, .scrollup ,.widget_search .search-submit , .wpcf7-form input.wpcf7-submit
    
 {
    
           background-color: " . $ours_restaurant_primary_color . ";}
           
    ";
        $custom_css .= ".example_f,#portfolio .portfolio-wrap, #portfolio .portfolio-item .portfolio-info, a.contact-us,a.button.product_type_simple.add_to_cart_button.ajax_add_to_cart,a.added_to_cart.wc-forward, .error404 .content-area .search-form .search-submit  ,.button-course, .read-more-background:hover,a.viewcourse , .blog-event-date{
           background: " . $ours_restaurant_primary_color .'!important'. ";}
           
    ";



        /*------------------------------------------------------------------------------------------------- */

        /*custom css*/


        wp_add_inline_style('ours-restaurant-style', $custom_css);

    }
endif;
add_action('wp_enqueue_scripts', 'ours_restaurant_dynamic_css', 99);