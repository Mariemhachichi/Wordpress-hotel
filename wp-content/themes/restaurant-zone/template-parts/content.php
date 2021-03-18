<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Restaurant Zone
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('article-box'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');?>
        <hr>
        <?php if ('post' === get_post_type()) : ?>
            <div class="entry-meta">
                <?php
                restaurant_zone_posted_on();
                ?>
            </div>
        <?php endif; ?>
    </header>
    <?php restaurant_zone_post_thumbnail(); ?>
    <div class="entry-summary">
        <?php
            the_excerpt();

            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'restaurant-zone'),
                'after' => '</div>',
            ));
        ?>
    </div>
    <footer class="entry-footer">
        <?php restaurant_zone_entry_footer(); ?>
    </footer>
</article>