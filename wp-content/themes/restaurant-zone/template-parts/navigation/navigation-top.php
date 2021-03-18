<?php
/**
 * Displays top navigation
 *
 * @package Restaurant Zone
 */
?>

<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-9 col-9">
            <div class="navbar-brand">
                <?php if ( has_custom_logo() ) : ?>
                    <div class="site-logo"><?php the_custom_logo(); ?></div>
                <?php endif; ?>
                <?php $blog_info = get_bloginfo( 'name' ); ?>
                    <?php if ( ! empty( $blog_info ) ) : ?>
                        <?php if ( is_front_page() && is_home() ) : ?>
                            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                        <?php else : ?>
                            <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php
                        $description = get_bloginfo( 'description', 'display' );
                        if ( $description || is_customize_preview() ) :
                    ?>
                    <p class="site-description"><?php echo esc_html($description); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-8 col-md-3 col-3">
            <div class="toggle-nav mobile-menu">
                <?php if(has_nav_menu('primary')){ ?>
                    <button onclick="restaurant_zone_openNav()"><i class="fas fa-th"></i></button>
                <?php }?>
            </div>
            <div id="mySidenav" class="nav sidenav">
                <nav id="site-navigation" class="main-navigation navbar navbar-expand-xl" aria-label="<?php esc_attr_e( 'Top Menu', 'restaurant-zone' ); ?>">
                    <?php if(has_nav_menu('primary')){
                        wp_nav_menu(
                            array(
                                'theme_location' => 'primary',
                                'menu_class'     => 'menu',
                                'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            )
                        );
                    } ?>
                </nav>
                <a href="javascript:void(0)" class="closebtn mobile-menu" onclick="restaurant_zone_closeNav()"><i class="far fa-times-circle"></i></a>
            </div>
        </div>
    </div>
</div>