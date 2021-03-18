<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bussiness_agency
 */
$description_from = esc_html(ours_restaurant_get_option('ours_restaurant_blog_excerpt_option')) ;
$description_length = esc_html(ours_restaurant_get_option('ours_restaurant_description_length_option'));

$i = 0;

?>


    <article id="post-<?php the_ID(); ?>" class="post type-post status-publish has-post-thumbnail hentry" <?php post_class(); ?>>

        <div class="container">
            <div class="row ">
                <?php if (has_post_thumbnail()) { ?>
                <div class="col-sm-6 img-blog">
                    <a class="post-thumbnail" href="#" aria-hidden="true" tabindex="-1">
                        <?php

                            $image_id = get_post_thumbnail_id();
                            $image_url = wp_get_attachment_image_src($image_id, 'large', true);
                            ?>
                            <img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php the_title_attribute(); ?>">

                    </a>

                </div>


                <div class="col-sm-6 blog-write">
                    <header class="entry-header">
                        <h3 class="entry-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h3>
                        <div class="entry-meta">
											<span class="posted-on"><?php esc_html_e('Posted on', 'ours-restaurant'); ?>
           <a href="<?php the_permalink();?>" rel="bookmark">
               <time class="entry-date published updated"><?php echo get_the_date(); ?></time>
           </a>
											</span>
												<span class="byline"> <?php esc_html_e('By', 'ours-restaurant'); ?>
													<span class="author vcard">
													<a class="url fn n"
                                                       href="<?php the_permalink(); ?>"><?php the_author(); ?></a>
												</span>
											</span>
                        </div><!-- .entry-meta -->
                    </header><!-- .entry-header -->


                    <div class="entry-content">
                        <p>    <?php


                            if ($description_from == 'content') {
                                echo esc_html(wp_trim_words(get_the_content(), $description_length));

                            } else {
                                echo esc_html(wp_trim_words(get_the_excerpt(), $description_length));

                            }
                            ?>
                        </p>
                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'ours-restaurant'),
                            'after' => '</div>',
                        ));
                        ?>

                        <a href="<?php the_permalink(); ?>"
                           class="continue-link"><?php esc_html_e('Continue Reading', 'ours-restaurant'); ?></a>

                    </div><!-- .entry-content -->
                </div>
                <?php }else{?>
                    <div class="col-sm-12 full-tem">
                        <header class="entry-header">
                            <h3 class="entry-title">
                                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                            </h3>
                            <div class="entry-meta">
											<span class="posted-on"><?php esc_html_e('Posted on', 'ours-restaurant'); ?>
                                                <a href="<?php the_permalink();?>" rel="bookmark">
                                                    <time class="entry-date published updated"><?php echo get_the_date(); ?></time>
                                                </a>
											</span>
												<span class="byline"> <?php esc_html_e('By', 'ours-restaurant'); ?>
                                                    <span class="author vcard">
													<a class="url fn n"
                                                       href="<?php the_permalink(); ?>"><?php the_author(); ?></a>
												</span>
											</span>
                            </div><!-- .entry-meta -->
                        </header><!-- .entry-header -->


                        <div class="entry-content">
                            <p>    <?php


                                if ($description_from == 'content') {
                                    echo esc_html(wp_trim_words(get_the_content(), $description_length));

                                } else {
                                    echo esc_html(wp_trim_words(get_the_excerpt(), $description_length));

                                }
                                ?>
                            </p>
                            <?php
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'ours-restaurant'),
                                'after' => '</div>',
                            ));
                            ?>

                            <a href="<?php the_permalink(); ?>"
                               class="continue-link"><?php esc_html_e('Continue Reading', 'ours-restaurant'); ?></a>

                        </div><!-- .entry-content -->
                    </div>

                <?php } ?>

            </div>
        </div>

</article>





