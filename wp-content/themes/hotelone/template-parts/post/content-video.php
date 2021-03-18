<?php 
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('blog_post'); ?>>
	<?php 
	$content = apply_filters( 'the_content', get_the_content() );
	$video = false;

	// Only get video from the content if a playlist isn't present.
	if ( false === strpos( $content, 'wp-playlist-script' ) ) {
		$video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
	}
	
	if ( ! is_single() ) {

			// If not a single post, highlight the video file.
			if ( ! empty( $video ) ) {
				foreach ( $video as $video_html ) {
					echo '<div class="entry-video">';
						echo wp_kses_post($video_html);
					echo '</div>';
				}
			};

		};
		
		?>
		
	<?php if ( '' !== get_the_post_thumbnail() ) : ?>
	<div class="blog-mask">
		<div class="blog-image">
			<div class="blog-large-image">
				<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'full' ); ?>
				</a>
				<a href="<?php the_permalink(); ?>" class="continue"><?php esc_html_e('Read More','hotelone'); ?></a>
			</div>
		</div>
	</div><!-- .blog-mask -->
	<?php endif; ?>
	
	<div class="blog-list-desc clearfix">
		<div class="blog-text">
			
			<?php			
			if ( is_sticky() && is_home() ) :
				
			endif;
			
			if ( is_single() ) {
				the_title( '<h4>', '</h4>' );
			}elseif ( is_front_page() && is_home() ) {
				the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
			} else {
				the_title( '<h4><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' );
			}
			?>				
			<div class="blog-icon"><i class="fa fa-file-video-o fa-lg"></i></div>
		</div>
		<div class="post-content">
			<?php
				
				if ( is_single() || empty( $video ) ) {
					
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
				}
				?>
		</div>
		<div class="blog-action">
			<ul class="clearfix">
				<li><i class="fa fa-user"></i><?php esc_html_e('Posted by :','hotelone') ?> 
			<strong>
				<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><?php echo get_the_author_link();?></a>
			</strong></li>
			
				<li><i class="fa fa-calendar"></i><?php the_time( get_option('date_format') ); ?></li>

				<?php 
				$separate_meta = __( ', ', 'hotelone' );
				$categories_list = get_the_category_list( $separate_meta );
					
				if( ( hotelone_categorized_blog() && $categories_list ) ){
				?>				
				<li><i class="fa fa-bookmark"></i>
					<?php echo wp_kses_post($categories_list); ?>
				</li>
				<?php } ?>			
			</ul>			
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->