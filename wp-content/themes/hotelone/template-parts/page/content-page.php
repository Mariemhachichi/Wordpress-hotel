<?php 
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('blog_post'); ?>>
	
	<?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
	<div class="blog-mask">
		<div class="blog-image">
			<div class="blog-large-image">
				<?php the_post_thumbnail( 'full' ); ?>
			</div>
		</div>
	</div><!-- .blog-mask -->
	<?php endif; ?>
	
	<div class="blog-list-desc clearfix">
		<div class="blog-text">
			
			<?php			
			if ( is_sticky() && is_home() ) :
				
			endif;
			
			the_title( '<h4>', '</h4>' );
			?>				
		</div>
		<div class="post-content">
			<?php
				the_content();

					wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'hotelone' ),
					'after'  => '</div>',
				) );
				?>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->