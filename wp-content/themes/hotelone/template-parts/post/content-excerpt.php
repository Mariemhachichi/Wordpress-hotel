<?php 
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
?>
<?php 
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('blog_post'); ?>>

	<div class="blog-list-desc clearfix">
		<div class="blog-text">
			
			<?php			
			if ( is_front_page() && is_home() ) {
				the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
			} else {
				the_title( '<h4><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' );
			}
			?>				
			<div class="blog-icon"><i class="fa fa-file-text-o fa-lg"></i></div>
		</div>
		<div class="post-content">
			<?php the_excerpt(); ?>
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