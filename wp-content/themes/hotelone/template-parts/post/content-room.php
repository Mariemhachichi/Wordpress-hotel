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
	$meta = get_post_meta( get_the_ID(),'room_meta', true );
	$images = array();
	
	$thumbId = get_post_thumbnail_id();
    $thumbnailUrl = wp_get_attachment_url( $thumbId );
	
	if(!empty($thumbnailUrl)){
			$images[] = $thumbnailUrl;
		}
	
	for($i=1; $i<=6; $i++){
		if( $meta["room_image_$i"] != null ){
			$images[] = $meta["room_image_$i"];
		}
	}
	?>
	<div id="roomsingle_carousel" class="carousel slide " data-ride="carousel" data-interval="6000">
		<div class="carousel-inner" role="listbox">
			<?php $i=1; foreach( $images as $image ){ ?>
			<div class="item <?php if($i==1){ echo 'active';} $i++; ?>">
				<img src="<?php echo esc_url( $image ); ?>" alt="<?php the_title(); ?>">
			</div>
			<?php } ?>
		</div>
		
		<?php if( count( $images ) > 1 ){ ?>
		<a class="left carousel-control" href="#roomsingle_carousel" role="button" data-slide="prev">
			<span class="fa fa-angle-left" aria-hidden="true"></span>
			<span class="sr-only"><?php esc_html_e('Previous','hotelone'); ?></span>
		</a>
		<a class="right carousel-control" href="#roomsingle_carousel" role="button" data-slide="next">
			<span class="fa fa-angle-right" aria-hidden="true"></span>
			<span class="sr-only"><?php esc_html_e('Next','hotelone'); ?></span>
		</a>
		<?php } ?>
	</div>
	
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
		<?php
			the_content();
			?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->