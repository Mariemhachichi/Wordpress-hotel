<?php 
$disable_room   = get_theme_mod( 'hotelone_room_hide', 0 );
$room_title    = get_theme_mod( 'hotelone_room_title', wp_kses_post('Our <span>Rooms</span>','hotelone') );
$room_subtitle    = get_theme_mod( 'hotelone_room_subtitle', wp_kses_post('Lorem ipsum dolor sit ame sed do eiusmod tempor incididunt ut labore et dolore','hotelone') );
$roomlayout    = get_theme_mod( 'hotelone_room_layout', '6' );
$page_ids =  hotelone_get_section_rooms_data();
if(empty($page_ids)){
	$disable_room = true;
}
if( ! $disable_room ){
if ( is_active_sidebar( 'front-page-room-top' ) ) { ?>
<div class="frontpage_siderbar">
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar( 'front-page-room-top' ); ?>
		</div>
	</div>
</div>
<?php } ?>
<div id="room" class="room_section section">
	
	<?php do_action('hotelone_section_before_inner', 'room'); ?>
	
	<div class="container">
		<?php if( $room_title || $room_subtitle ){ ?>
		<div class="row">
			<div class="col-md-12 text-center">
				<?php if( $room_title ){ ?>
				<h2 class="section-title wow animated fadeInDown"><?php echo wp_kses_post($room_title); ?></h2>
				<?php } ?>
				
				<?php if( $room_subtitle ){ ?>
				<div class="seprator wow animated slideInLeft"></div>
				<p class="section-desc wow animated fadeInUp"><?php echo wp_kses_post($room_subtitle); ?></p>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		
		<div class="row">
			<?php  if ( ! empty( $page_ids ) ) { ?>
			
			<?php 
			$columns = 2;
			switch ( $roomlayout ) {
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
			
			if ( $roomlayout == 12 ) {
				$classes = 'col-sm-12 col-lg-'.$roomlayout;
			} else {
				$classes = 'col-sm-6 col-lg-'.$roomlayout;
			}
				
			if ($si >= $columns) {
				$si = 1;
				$classes .= ' clearleft';
			} else {
				$si++;
			}
			
				foreach ($page_ids as $settings) { 
				$post_id = $settings['content_page'];
				$post_id = apply_filters( 'wpml_object_id', $post_id, 'page', true );
				$post = get_post($post_id);
				setup_postdata( $post );
				$settings['enable_link'] = true;
			?>
			<div class="<?php echo esc_attr( $classes ); ?> wow animated rollIn">
				<div class="card-room">
					
					<?php if( has_post_thumbnail() ) { ?>
					<div class="room_thumbnial">
						<?php the_post_thumbnail('full'); ?>
						<div class="room_overlay">
							<div class="room_overlay_inner">
								<?php 
								  $thumbId = get_post_thumbnail_id();
								  $thumbnailUrl = wp_get_attachment_url( $thumbId );
								  ?>								
								<?php if( $settings['enable_link'] == true ){ ?>
								<a class="overlay-btn" href="<?php the_permalink(); ?>"><i class="fa fa-link"></i></a>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php } ?>
					
					<div class="room_detail_info text-left">
						<span><?php echo esc_html( $settings['price'] ); ?></span>
						<span>
							<?php for($i=1; $i<=$settings['person']; $i++){ ?>
							<i class="fa fa-male"></i>							
							<?php } ?>
						</span>
					</div>
					<div class="room-content text-center">
						<div class="room_rate">
							<?php for($r=1; $r<=5; $r++){ ?>
								<?php if($r<=$settings['rating']){ ?>
								<i class="fa fa-star star_yellow"></i>
								<?php }else{ ?>
								<i class="fa fa-star"></i>
								<?php } ?>
							<?php } ?>
						</div>
						
						<?php if( $settings['enable_link'] == true ){ ?>
						<a href="<?php the_permalink(); ?>">
						<?php } ?>
							<?php the_title('<h4 class="room_title">','</h4>'); ?>
						<?php if( $settings['enable_link'] == true ){ ?>
						</a>
						<?php } ?>
						<div class="service_desc">
							<?php
								the_excerpt();
							?>
						</div>
					</div>
					
					<?php if( $settings['enable_link'] == true ){ ?>
					<div class="text-center">
						<a class="hotel-btn hotel-primary hotel-small room-btn" href="<?php the_permalink(); ?>"><?php esc_html_e('Book Now','hotelone'); ?> <i class="fa fa-arrow-right"></i></a>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<?php } ?>
		</div><!-- .row -->
		
	</div><!-- .container -->
	
	<?php do_action('hotelone_section_after_inner', 'room'); ?>
	
</div><!-- .room_section -->
<?php if ( is_active_sidebar( 'front-page-room-bottom' ) ) { ?>
<div class="frontpage_siderbar">
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar( 'front-page-room-bottom' ); ?>
		</div>
	</div>
</div>
<?php } 
 } ?>