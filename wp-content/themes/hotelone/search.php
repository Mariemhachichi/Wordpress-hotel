<?php 
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 */
 
get_header(); 

$layout = hotelone_get_layout();
$col = '8';
if($layout=='none'){
	$col = '12';
}
?>

<div id="site-content" class="site-content">
	<div class="container">		
		<div class="row">
			<?php 
				if ( $layout != 'none' && $layout=='left' ) {
					get_sidebar(); 
				} 
				?>
				
			<div class="col-md-<?php echo esc_attr( $col ); ?> col-sm-<?php echo esc_attr( $col ); ?> primary">
				
				<header class="page-header">
					<?php if ( have_posts() ) : ?>
						<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: <span>%s</span>', 'hotelone' ), esc_html( get_search_query() ) ); ?></h1>
					<?php else : ?>
						<h1 class="page-title"><?php esc_html_e('Nothing Found', 'hotelone' ); ?></h1>
					<?php endif; ?>
				</header><!-- .page-header -->
					
				<?php
				if ( have_posts() ) :
					
					/* Start the Loop */
					while ( have_posts() ) : the_post();
					
						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/post/content', 'excerpt' );
						
					endwhile;
					
					the_posts_pagination( array(
							'prev_text' => '<i class="fa fa-angle-double-left"></i>',
							'next_text' => '<i class="fa fa-angle-double-right"></i>',
						) );
				
				else :
					
					get_template_part( 'template-parts/post/content', 'none' );
					
				endif;
				?>			
				
			</div>
			
			<?php 
				if ( $layout != 'none' && $layout=='right' ) {
					get_sidebar(); 
				} 
				?>
		</div>
	</div>
</div><!-- .site-content -->
	
<?php get_footer(); ?>