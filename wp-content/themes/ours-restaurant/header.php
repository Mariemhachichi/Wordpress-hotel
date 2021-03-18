<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Ample_OnePage
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class('at-sticky-sidebar'); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}
?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ours-restaurant' ); ?></a>

<!--==========================
  Header
============================-->
<header id="masthead-header" class="site-header" role="banner">
    <?php
    $section_option_company_info = ours_restaurant_get_option('ours_restaurant_top_header_section');
    if ($section_option_company_info == 'show') { ?>
    <div class="top-header">
        <div class="container-fluid">
            <div class="row">
               <div class="col-sm-7 info-top">
                <div class="top-info">
                    <?php

                    $location_icon = ours_restaurant_get_option('ours_restaurant_info_header_section_location_icon');
                    $location = ours_restaurant_get_option('ours_restaurant_info_header_location');

                    $number_icon = ours_restaurant_get_option('ours_restaurant_info_header_section_phone_number_icon');
                    $number = ours_restaurant_get_option('ours_restaurant_info_header_phone_no');

                    $email_icon = ours_restaurant_get_option('ours_restaurant_email_icon');
                    $email = ours_restaurant_get_option('ours_restaurant_info_header_email');
                    $our_resto_button = ours_restaurant_get_option('ours_restaurant_button');
                    $our_resto_button_link = ours_restaurant_get_option('ours_restaurant_apply_button_link');



                        ?>
                        <div class="contact-info">
                            <ul>
                                <?php if (!empty( $location)){ ?>
                                    <li><i class="fa <?php echo esc_html($location_icon);?>"></i><?php echo esc_html( $location);?></li>
                                <?php }if (!empty($number)){ ?>
                                    <li><i class="fa <?php echo  esc_html( $number_icon);?>"></i> <?php echo esc_html( $number);?></li>
                                <?php } if (!empty( $email)){
                                    ?>
                                    <li><i class="fa <?php echo esc_html( $email_icon);?> "></i> <?php echo sanitize_email( $email);?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php
                    $social_menu = ours_restaurant_get_option('ours_restaurant_social_link_hide_option');
                    $social_fb = ours_restaurant_get_option('ours_restaurant_facebook_url');
                    $social_youtube = ours_restaurant_get_option('ours_restaurant_youtube_url');
                    $social_linkedin = ours_restaurant_get_option('ours_restaurant_linkedin_url');
                    $social_twitter = ours_restaurant_get_option('ours_restaurant_twitter_url');
                    $social_insta = ours_restaurant_get_option('ours_restaurant_instagram_url');
                    $social_google = ours_restaurant_get_option('ours_restaurant_google_plus_url');


                    ?>
                </div>
               </div>
                <div class="col-sm-5">
                    <?php if(!empty($our_resto_button_link)){?>

                        <a href="<?php echo esc_url($our_resto_button_link);?>"
                           target="_blank" class="contact-us" ><span><?php echo esc_html($our_resto_button);?></span>
                        </a>

                    <?php } ?>
                <div class="social">
            <?php
                if ($social_menu == 1) { ?>


                        <div class="social-links">


                            <?php if(!empty($social_twitter)){ ?>

                                <a href="<?php echo esc_url($social_twitter);?>" target="_blank" class="twitter"><i class="fab fa-twitter"></i></a>

                            <?php }if(!empty($social_fb)){?>

                                <a href="<?php echo esc_url(($social_fb));?>" target="_blank" class="facebook"><i class="fab fa-facebook-f"></i></a>

                            <?php }

                            if(!empty($social_youtube)){?>

                                <a href="<?php echo esc_url($social_youtube);?>" class="facebook" target="_blank"><i class="fab fa-youtube"></i></a>

                            <?php }

                            if(!empty($social_insta)){ ?>

                                <a href="<?php echo esc_url(($social_insta));?>" class="instagram" target="_blank"><i class="fab fa-instagram"></i></a>

                            <?php }
                            if(!empty( $social_linkedin)){ ?>

                                <a href="#" class="linkedin"  target="_blank"><i class="fab fa-linkedin-in" ></i></a>

                            <?php } ?>

                        </div>

                <?php } ?>
                </div>

              </div>

            </div>
        </div>
    </div>

<?php } ?>
    <!-- Start Top header Section -->
    <?php
    /**
     * The template for displaying all pages.
     *
     * This is the template that displays all pages by default.
     * Please note that this is the WordPress construct of pages
     * and that other 'pages' on your WordPress site may use a
     * different template.
     *
     * @link https://codex.wordpress.org/Template_Hierarchy
     *
     * @subpackage Business Epic
     */
    // retrieving Customizer Value
    ?>
    <!-- Start logo and menu Section -->
    <div class="main-header">
        <div class="container-fluid">
            <div class="site-branding-wrap">
                <!-- Start Site title Section -->
                <div class="site-identity">
                    <!-- <img src="images/logo.png" alt=""> -->
                    <h1 class="site-title">
                        <!-- <img src="images/logo.png" alt=""> -->
                        <?php
                        if (has_custom_logo()) { ?>
                            <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                                <?php the_custom_logo(); ?>
                            </a>
                        <?php } else {
                        if (is_front_page() && is_home()) : ?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>"
                                   rel="home"><?php bloginfo('name'); ?></a>
                            </h1>
                        <?php else : ?>
                            <h1  class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>"
                                   rel="home"><?php bloginfo('name'); ?></a>
                            </h1 >
                            <?php
                        endif;
                        ?>
                    </h1>
                    <?php
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) : ?>
                        <p class="site-description"><?php echo esc_html($description); /* WPCS: xss ok. */ ?></p>
                        <?php
                    endif;
                    } ?>
                </div>



                <!-- for toogle menu -->
                    <span id="showbutton">
                        <a href="#">
                        <i class="fas fa-align-right"></i>
                        </a>
                    </span>
            </div>


            <!-- End Site title Section -->
            <!-- Start Menu Section -->
            <div class="menu">
                <!--<nav id="site-navigation" class="main-navigation" role="navigation"> -->
                <div class="nav-wrapper">


                    <nav class="column-12 im-hiding">
                        <?php
                        if (has_nav_menu('primary')) {
                            wp_nav_menu(array(
                                    'theme_location' => 'primary',
                                    'menu_class' => 'main-nav',
                                    'depth' => 6,

                                )
                            );
                        }
                        ?>



                    </nav>

                    <!-- / main nav -->
                </div>
                <!-- </nav> -->
            </div>
            <!-- End Menu Section -->

        </div>
    </div>
    <!-- End logo and menu Section -->



</header><!-- #header -->



