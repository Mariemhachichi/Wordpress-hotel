<?php 
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('blog_post'); ?>>
	
	<?php if ( '' !== get_the_post_thumbnail() ) : ?>
	<div class="blog-mask">
		<div class="blog-image">
			<div class="blog-large-image">
				<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'full' ); ?>
				</a>
			</div>
		</div>
	</div><!-- .blog-mask -->
	<?php endif; ?>
	
	<div class="blog-list-desc clearfix">
		<div class="blog-text">
			
			<?php			
			if ( is_single() ) {
				the_title( '<h4>', '</h4>' );
			}elseif ( is_front_page() && is_home() ) {
				the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
			} else {
				the_title( '<h4><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' );
			}
			?>				
		</div>
		<div class="post-content">
			<?php
				/* translators: %s: Name of current post */
				the_content( sprintf(
					__( 'Read More', 'hotelone' ),
					get_the_title()
				) );

				wp_link_pages( array(
					'before'      => '<div class="page-links">' . __( 'Pages:', 'hotelone' ),
					'after'       => '</div>',
					'link_before' => '<span class="page-number">',
					'link_after'  => '</span>',
				) );
				?>
		</div>
	</div>
</article>