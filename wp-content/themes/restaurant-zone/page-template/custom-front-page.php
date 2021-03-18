<?php
/**
 * Template Name: Custom Front Page
 */

get_header(); ?>

<main id="skip-content">
  <section id="top-slider">
    <?php $restaurant_zone_slide_pages = array();
      for ( $count = 1; $count <= 3; $count++ ) {
        $mod = intval( get_theme_mod( 'restaurant_zone_top_slider_page' . $count ));
        if ( 'page-none-selected' != $mod ) {
          $restaurant_zone_slide_pages[] = $mod;
        }
      }
      if( !empty($restaurant_zone_slide_pages) ) :
        $args = array(
          'post_type' => 'page',
          'post__in' => $restaurant_zone_slide_pages,
          'orderby' => 'post__in'
        );
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) :
          $i = 1;
    ?>
    <div class="owl-carousel" role="listbox">
      <?php  while ( $query->have_posts() ) : $query->the_post(); ?>
        <div class="slider-box">
          <img src="<?php esc_url(the_post_thumbnail_url('full')); ?>"/>
          <div class="slider-inner-box">
            <h2><?php the_title(); ?></h2>
            <p><?php $restaurant_zone_excerpt = get_the_excerpt(); echo esc_html( restaurant_zone_string_limit_words( $restaurant_zone_excerpt, 15 ) ); ?></p>
            <div class="slide-btn"><a href="<?php the_permalink(); ?>"><?php esc_html_e('VIEW MORE','restaurant-zone'); ?></a></div>
          </div>
        </div>
      <?php $i++; endwhile; 
      wp_reset_postdata();?>
    </div>
    <?php else : ?>
      <div class="no-postfound"></div>
    <?php endif;
    endif;?>
  </section>
  <section id="items-section">
    <div class="container">
      <?php if(get_theme_mod('restaurant_zone_title') != ''){ ?>
        <h3><?php echo esc_html(get_theme_mod('restaurant_zone_title','')); ?></h3>
      <?php }?>
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <?php $restaurant_zone_image_page = array();
            $mod = intval( get_theme_mod( 'restaurant_zone_image_page' ));
            if ( 'page-none-selected' != $mod ) {
              $restaurant_zone_image_page[] = $mod;
            }
            if( !empty($restaurant_zone_image_page) ) :
              $args = array(
                'post_type' => 'page',
                'post__in' => $restaurant_zone_image_page,
                'orderby' => 'post__in'
              );
              $query = new WP_Query( $args );
              if ( $query->have_posts() ) :
          ?>
          <?php  while ( $query->have_posts() ) : $query->the_post(); ?>
            <div class="item-image">
              <a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url('full'); ?>"/></a>
            </div>
          <?php $i++; endwhile; 
          wp_reset_postdata();?>
          <?php else : ?>
            <div class="no-postfound"></div>
          <?php endif;
          endif;?>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="item-list">
            <?php
            $restaurant_zone_catData = get_theme_mod('restaurant_zone_menu_items','');
            if($restaurant_zone_catData){
              $restaurant_zone_page_query = new WP_Query(array( 'category_name' => esc_html($restaurant_zone_catData,'restaurant-zone')));
              while( $restaurant_zone_page_query->have_posts() ) : $restaurant_zone_page_query->the_post(); ?>
                <div class="serv-box row">
                  <div class="col-lg-3 col-md-4">
                    <?php the_post_thumbnail(); ?>
                  </div>
                  <div class="col-lg-9 col-md-8">
                    <h4><?php the_title(); ?></h4>
                    <p><?php $restaurant_zone_excerpt = get_the_excerpt(); echo esc_html( restaurant_zone_string_limit_words( $restaurant_zone_excerpt, 16 ) ); ?></p>
                    <a href="<?php the_permalink(); ?>"><?php esc_html_e('VIEW MORE','restaurant-zone'); ?></a>
                  </div>
                </div>
              <?php endwhile;
              wp_reset_postdata();
            } ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>