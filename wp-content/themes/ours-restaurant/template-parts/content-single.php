<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bussiness agency
 */

$hide_show_feature_image = ours_restaurant_get_option('ours_restaurant_show_feature_image_single_option');

?>
<article id="post-<?php the_ID(); ?>"
         class="post type-post status-publish has-post-thumbnail hentry" <?php post_class(); ?>>


    <figure>
        <div class="view hm-zoom">
            <a href="<?php the_permalink(); ?>">


                <?php

                if (has_post_thumbnail() && $hide_show_feature_image == 'show') {
                    the_post_thumbnail('full', array('class' => 'img-fluid'));
                }
                ?>

                <div class="mask flex-center">

                </div>
            </a>
        </div>
    </figure>
    <header class="entry-header">
        <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    </header>
    <div class="entry-meta meta-data">
        <span class="posted-on">
            <a href=""><i class="fa fa-calendar"></i><time class="enty-date posted-date" datetime=""><?php echo get_the_date(); ?></time></a>
        </span>
        <span class="posted-by">
            <a href="">
                <i class="fa fa-user"></i>
                <?php the_author(); ?>
            </a>
        </span>
        <span class="category-tag">
            <i class="fa fa-folder"></i>
            <a href="">
                <?php ours_restaurant_entry_footer(); ?>
            </a>
        </span>
    </div>

    <div class="entry-content">
        <?php


        the_content();

        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'ours-restaurant'),
            'after' => '</div>',
        ));

        ?>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->


