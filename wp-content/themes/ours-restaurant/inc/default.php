<?php
/**
 * Business agency default theme options.
 *
 * 
 * @subpackage Business agency
 */

if ( !function_exists('ours_restaurant_get_default_theme_options' ) ) :

    /**
     * Get default theme options.
     *
     * @since 1.0.0
     *
     * @return array Default theme options.
     */
    function ours_restaurant_get_default_theme_options()
    {

        $default = array();

        // Homepage Slider Section
        $default['ours_restaurant_homepage_slider_option'] = 'hide';
        $default['ours_restaurant_slider_cat_id'] = 0;
        $default['ours_restaurant_no_of_slider'] = 3;
        $default['ours_restaurant_slider_get_started_txt'] = esc_html__('Get Started!', 'ours-restaurant');
        $default['ours_restaurant_slider_get_started_link'] = '#';

        // footer copyright.
        $default['ours_restaurant_copyright'] = '';

        // Home Page Top header Info.
        $default['ours_restaurant_top_header_section'] = 'hide';
        $default['ours_restaurant_notice_title']= esc_html__('Notice', 'ours-restaurant');
        $default['ours_restaurant_news_cat_id']='';
        $default['ours_restaurant_no_of_news']=5;
        $default['ours_restaurant_social_link_hide_option'] = 1;

        $default['ours_restaurant_button']=esc_html__('Contact Us', 'ours-restaurant');
        $default['ours_restaurant_apply_button_link']='';

        // Blog.
        $default['ours_restaurant_sidebar_layout_option'] = 'right-sidebar';
        $default['ours_restaurant_blog_title_option'] = esc_html__('Latest Blog', 'ours-restaurant');
        $default['ours_restaurant_blog_excerpt_option'] = 'excerpt';
        $default['ours_restaurant_description_length_option'] = 80;
        $default['ours_restaurant_exclude_cat_blog_archive_option'] = '';
        

        // Details page.
        $default['ours_restaurant_show_feature_image_single_option'] = 'show';

        // Color Option.
        $default['ours_restaurant_primary_color'] = '#ff5809';
       
        $default['ours_restaurant_top_footer_background_color'] = '#252020';
        $default['ours_restaurant_front_page_hide_option'] = 0;
        $default['ours_restaurant_breadcrumb_setting_option'] = 'enable';
        $default['ours_restaurant_hide_breadcrumb_front_page_option'] = 0;
        $default['ours_restaurant_top_color']='#000';
        $default['ours_restaurant_color_reset_option'] = 'do-not-reset';

        //company info
        $default['ours_restaurant_top_header_section']='hide';
        $default['ours_restaurant_info_header_section_location_icon']='fa-map-marker';
        $default['ours_restaurant_info_header_location']='';
        $default['ours_restaurant_info_header_section_phone_number_icon']='fa-phone';
        $default['ours_restaurant_info_header_phone_no']='';
        $default['ours_restaurant_email_icon']='fa-envelope';
        $default['ours_restaurant_info_header_email']='';

        /*default value */

        $default['ours_restaurant_facebook_url']='';
        $default['ours_restaurant_youtube_url']='';
        $default['ours_restaurant_linkedin_url']='';
        $default['ours_restaurant_twitter_url']='';
        $default['ours_restaurant_instagram_url']='';
        $default['ours_restaurant_google_plus_url']='';
        $default['ours_restaurant_button']=  esc_html__('Book Table', 'ours-restaurant');
        $default['ours_restaurant_apply_button_link']='#';



        // Pass through filter.
        $default = apply_filters( 'ours_restaurant_get_default_theme_options', $default );
        return $default;
    }
endif;
