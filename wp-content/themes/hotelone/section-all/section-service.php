<?php 
$disable_service   = get_theme_mod( 'hotelone_services_hide', 0 );
$service_title    = get_theme_mod( 'hotelone_services_title', wp_kses_post('Our <span>Features</span>','hotelone') );
$service_subtitle    = get_theme_mod( 'hotelone_services_subtitle', wp_kses_post('Lorem ipsum dolor sit ame sed do eiusmod tempor incididunt ut labore et dolore','hotelone') );
$layout = intval( get_theme_mod( 'hotelone_service_layout', 6 ) );
$services_mbtn_text = get_theme_mod( 'hotelone_services_mbtn_text', wp_kses_post('View More <i class="fa fa-angle-double-right"></i>','hotelone') );
$services_mbtn_url = get_theme_mod( 'hotelone_services_mbtn_url', '' );
$page_ids =  hotelone_get_section_services_data();
if(empty($page_ids)){
	$disable_service = true;
}
if( ! $disable_service ){
if ( is_active_sidebar( 'front-page-service-top' ) ) { ?>
<div class="frontpage_siderbar">
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar( 'front-page-service-top' ); ?>
		</div>
	</div>
</div>
<?php } ?>
<div id="service" class="service_section section">

<?php do_action('hotelone_section_before_inner', 'services'); ?>

	<div class="container">
		
		<?php if( $service_title || $service_subtitle ){ ?>
		<div class="row">
			<div class="col-md-12 text-center">
				<?php if( $service_title ){ ?>
				<h2 class="section-title wow animated fadeInDown"><?php echo wp_kses_post($service_title); ?></h2>
				<?php } ?>
				
				<?php if( $service_subtitle ){ ?>
				<div class="seprator wow animated slideInLeft"></div>
				<p class="section-desc wow animated fadeInUp"><?php echo wp_kses_post($service_subtitle); ?></p>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		
		<div class="row">
			<?php  if ( ! empty( $page_ids ) ) { ?>
			
			<?php
			$columns = 2;
			switch ( $layout ) {
				case 12:
					$columns =  1;
					break;
				case 6:
					$columns =  2;
					break;
				case 4:
					$columns =  3;
					break;
				case 3:
					$columns =  4;
					break;
			} 
			
			$si = 0;
			
			$size = sanitize_text_field( get_theme_mod( 'hotelone_service_icon_size', '5x' ) );
			
			foreach ($page_ids as $settings) {
				$post_id = $settings['content_page'];
				$post_id = apply_filters( 'wpml_object_id', $post_id, 'page', true );
				$post = get_post($post_id);
				setup_postdata( $post );
				$settings['icon'] = trim($settings['icon']);
				
				$media = '';
				
				if ( $settings['icon'] && $settings['icon_type'] == 'icon' ) {
					$settings['icon'] = trim( $settings['icon'] );
					if ( $settings['icon'] != '' && strpos($settings['icon'], 'fa') !== 0) {
						$settings['icon'] = 'fa-' . $settings['icon'];
					}
					$media = '<a href="'.esc_url(get_the_permalink()).'" target="_blank"><i class="fa '.esc_attr( $settings['icon'] ).'"></i></a>';
				}
				if ( $layout == 12 ) {
					$classes = 'col-sm-12 col-lg-'.$layout;
				} else {
					$classes = 'col-sm-6 col-lg-'.$layout;
				}

				if ($si >= $columns) {
					$si = 1;
					$classes .= ' clearleft';
				} else {
					$si++;
				}
			?>
			<div class="<?php echo esc_attr( $classes ); ?> service-type-<?php echo esc_attr( $settings['icon_type'] ); ?>">
				<div class="card-service">
					<?php if ( $settings['icon'] && $settings['icon_type'] == 'icon' ) { ?>
					<div class="service-icon text-center <?php echo 'fa-' . esc_attr( $size ); ?>">
						<?php if ( $media != '' ) {
							echo wp_kses_post($media);
						} ?>
					</div>
					<?php } ?>
					<div class="service-content text-center">
					<?php 
					if ( $settings['icon_type'] == 'image' && $settings['image'] ){
						$url = hotelone_get_media_url( $settings['image'] );
						if ( $url ) {
							$media = '<div class="service-icon-image"><a href="'.esc_url(get_the_permalink()).'" target="_blank"><img src="'.esc_url( $url ).'" alt="'.esc_attr(get_the_title()).'"></a></div>';
						}
						echo wp_kses_post($media);
					}
					?>
						<?php if( $settings['enable_link'] == true ){ ?>
						<a href="<?php the_permalink(); ?>">
						<?php } ?>
							<h4 class="service_title"><?php echo get_the_title( $post ); ?></h4>
						<?php if( $settings['enable_link'] == true ){ ?>
						</a>
						<?php } ?>
						<div class="service_desc">
							<?php the_excerpt(); ?>
						</div>
					</div>
				</div>
			</div>
			<?php  } wp_reset_postdata(); ?>
			<?php  } ?>
			
		</div>	
		<?php if( $services_mbtn_url ){ ?>
		<div class="row text-center">
			<a class="hotel-btn hotel-primary service-btn" href="<?php echo esc_url( $services_mbtn_url); ?>"><?php printf( sprintf( wp_kses_post( $services_mbtn_text ) ) ); ?></a>
		</div><!-- .row -->
		<?php } ?>
	</div><!-- .container -->
	
<?php do_action('hotelone_section_after_inner', 'services'); ?>
</div><!-- .service_section -->
<?php if ( is_active_sidebar( 'front-page-service-bottom' ) ) { ?>
<div class="frontpage_siderbar">
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar( 'front-page-service-bottom' ); ?>
		</div>
	</div>
</div>
<?php } 
 } ?>