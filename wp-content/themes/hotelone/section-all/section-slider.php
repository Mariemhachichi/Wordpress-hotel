<?php 
$_images = get_theme_mod('hotelone_slider_images');
if (is_string($_images)) {
	$_images = json_decode($_images, true);
}

if ( empty( $_images ) || !is_array( $_images ) ) {
    $_images = array();
}

$images = array();

foreach ( $_images as $m ) {
	$m  = wp_parse_args( $m, array('image' => '' ) );
	$_u = hotelone_get_media_url( $m['image'] );
	if ( $_u ) {
		$images[] = $_u;
	}
}

if ( empty( $images ) ){
	$images = array( get_template_directory_uri().'/images/slider/slide1.jpg' );
}

$disable_slider       = get_theme_mod( 'hotelone_slider_disable', 0 );
$disable_slider_rating= get_theme_mod( 'hotelone_slider_rating__hide', 0 );
$slider_rating= get_theme_mod( 'hotelone_slider_rating', 5 );
$slider_bigtitle= get_theme_mod( 'hotelone_slider_bigtitle', wp_kses_post( 'Welcome to Hotelone Theme', 'hotelone') );
$slider_subtitle= get_theme_mod( 'hotelone_slider_subtitle', wp_kses_post( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit labore et dolore magna aliqua.', 'hotelone') );
$hotelone_pbtn_text= get_theme_mod( 'hotelone_pbtn_text', esc_html__( 'Download Now', 'hotelone') );
$hotelone_pbtn_link= get_theme_mod( 'hotelone_pbtn_link', '#' );
$hotelone_sbtn_text= get_theme_mod( 'hotelone_sbtn_text', esc_html__( 'View Demo', 'hotelone') );
$hotelone_sbtn_link= get_theme_mod( 'hotelone_sbtn_link', '#' );
if( ! $disable_slider ){
if ( is_active_sidebar( 'front-page-slider-top' ) ) { ?>
<div class="frontpage_siderbar">
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar( 'front-page-slider-top' ); ?>
		</div>
	</div>
</div>
<?php } ?>
<div id="slider" class="big_section">
		<div id="hero_carousel" class="carousel slide " data-ride="carousel" data-interval="6000">
			<?php if( count($images) > 1 ){ ?>
			<?php $i = 1; ?>
			<ol class="carousel-indicators">
			<?php foreach($images as $index => $image){ ?>
			 
				<li data-target="#hero_carousel" data-slide-to="<?php echo esc_attr( $index ); ?>" class="<?php if( $i == 1 ){ echo 'active'; } $i++; ?>"></li>		  
			
			<?php } ?>
			</ol>
			<?php } ?>
			
		<div class="carousel-inner" role="listbox">
			
			<?php $i = 1; ?>
			<?php foreach($images as $image){ ?>
			<div class="carousel-item <?php if( $i == 1 ){ echo 'active'; } $i++; ?>">
				<img class="slide_image d-block" src="<?php echo esc_url($image); ?>">
				<div class="slider_overlay">
					<div class="slider_overlay_inner text-center">
						<div class="container">
							<div class="row">
								<div class="col-md-12">
									<div class="slider_overlay_bg">
										<?php if( !$disable_slider_rating ){ ?>
										<div class="slide_rate">
											<?php for( $i=1; $i<=5; $i++){ ?>
											
												<?php if($i<=$slider_rating){ ?>
												<i class="fa fa-star star_yellow"></i>
												<?php }else{ ?>
												<i class="fa fa-star"></i>
												<?php } ?>
											
											<?php } ?>
										</div>
										<?php } ?>
										
										<?php if(!empty( $slider_bigtitle )){ ?>
										<h2 class="big_title animated fadeInDown"><?php echo wp_kses_post( $slider_bigtitle ); ?> </h2>
										<?php } ?>
										
										<?php if(!empty( $slider_subtitle )){ ?>
										<p class="slider_content animated fadeInDown"><?php echo wp_kses_post( $slider_subtitle ); ?></p>
										<?php } ?>
										
										<?php if(!empty( $hotelone_pbtn_link )){ ?>
										<a class="hotel-btn hotel-primary animated fadeInDown" href="<?php echo esc_url( $hotelone_pbtn_link ); ?>"><?php echo wp_kses_post( $hotelone_pbtn_text ); ?></a>
										<?php } ?>
										
										<?php if(!empty( $hotelone_sbtn_link )){ ?>
										<a class="hotel-btn hotel-secondry animated fadeInDown" href="<?php echo esc_url( $hotelone_sbtn_link ); ?>"><?php echo wp_kses_post( $hotelone_sbtn_text ); ?></a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div><!-- .slider_overlay -->
			</div>
			<?php } ?>	
			
		</div><!-- .carousel-inner -->
		
		<?php if( count($images) > 1 ){ ?>
		<a class="carousel-control-prev" href="#hero_carousel" role="button" data-slide="prev">
			<span class="fa fa-angle-left" aria-hidden="true"></span>
			<span class="sr-only"><?php _e('Previous','hotelone'); ?></span>
		</a>
		<a class="carousel-control-next" href="#hero_carousel" role="button" data-slide="next">
			<span class="fa fa-angle-right" aria-hidden="true"></span>
			<span class="sr-only"><?php _e('Next','hotelone'); ?></span>
		</a>
		<?php } ?>
	</div>
</div><!-- .big_section -->
<?php if ( is_active_sidebar( 'front-page-slider-bottom' ) ) { ?>
<div class="frontpage_siderbar">
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar( 'front-page-slider-bottom' ); ?>
		</div>
	</div>
</div>
<?php } 
 } ?>