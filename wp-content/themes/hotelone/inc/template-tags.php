<?php
if( !function_exists('hotelone_breadcrumbs') ) {
	function hotelone_breadcrumbs(){
		$disable_pageTitleBar = get_theme_mod('hotelone_page_title_bar_hide',false);
		if( is_page() && $disable_pageTitleBar == true ){
			return;
		}

		if( is_404() && $disable_pageTitleBar == true ){
			return;
		}

		if( is_single() && $disable_pageTitleBar == true ){
			return;
		}

		if( is_archive() && $disable_pageTitleBar == true ){
			return;
		}

		if( is_home() && $disable_pageTitleBar == true ){
			return;
		}

		$titleAlign = get_theme_mod('hotelone_page_cover_align','center');
	?>
	<div id="subheader" class="subheader" style="background-image: url(<?php header_image(); ?>);">
		<div id="subheaderInner" class="subheaderInner">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-<?php echo esc_attr( $titleAlign ); ?>">
						<div class="pageTitleArea wow animated fadeInDown">
							<?php 
							if( function_exists('bcn_display') ){
								bcn_display(); 
							}else{ 
								if( is_front_page() && is_home() ){?>
									<h1 class="pageTitle"><?php esc_html_e('Home','hotelone'); ?></h1>
								<?php }else if( is_archive() ){
									the_archive_title( '<h1 class="pageTitle">', '</h1>' );
								}else if( is_404() ){ ?>
									<h1 class="pageTitle"><?php esc_html_e('404','hotelone'); ?></h1>
								<?php }else{?>
									<h1 class="pageTitle"><?php single_post_title();?></h1>
								<?php }							 
							}
							?>							
						</div>
						<?php 
						if( is_archive() ){
							the_archive_description( '<div class="taxonomy-description">', '</div>' );
						}
						?>
					</div>
				</div>
			</div><!-- .container -->
		</div>
	</div><!-- .subheader -->
	<?php
	}
}

if ( ! function_exists('hotelone_header' ) ) {
	
	function hotelone_header(){
		
		$header_pos = sanitize_text_field(get_theme_mod('hotelone_header_position', 'top'));
		
		echo '<div class="header">';
		
				do_action('hotelone_header_section_start');
				
					if ($header_pos == 'below_slider' ) {
						do_action('hotelone_header_end');
					}
			
					do_action('hotelone_site_start');
				
					if ($header_pos != 'below_slider' ) {
						do_action('hotelone_header_end');
					}

				do_action('hotelone_header_section_end');
			
		echo '</div><!-- .header -->';
	}
	
}

if ( ! function_exists('hotelone_header_top') ) {
    function hotelone_header_top(){
		hotelone_load_section( 'header_top' );
    }
}
add_action( 'hotelone_header_section_start', 'hotelone_header_top' );

if ( ! function_exists('hotelone_navigation') ) {
    function hotelone_navigation(){
		hotelone_load_section( 'navigation' );
    }
}
add_action( 'hotelone_site_start', 'hotelone_navigation' );

if ( ! function_exists('hotelone_big_section') ) {
    function hotelone_big_section(){
		 
		if( is_front_page() && is_page_template('template-homepage.php') ){
			hotelone_load_section('slider');
		}else if( is_404() ){
			hotelone_breadcrumbs();
		}else{
			hotelone_breadcrumbs();
		}

    }
}
add_action( 'hotelone_header_end', 'hotelone_big_section' );

if ( ! function_exists( 'hotelone_logo' ) ) {
	function hotelone_logo(){
		$class = array();
		$html = '';
		
		if ( function_exists( 'has_custom_logo' ) ) {
			if ( has_custom_logo()) {
				$html .= get_custom_logo();
			}else{
				if ( is_front_page() && !is_home() ) {
					$html .= '<h1 class="site-title"><a href="'.esc_url( home_url( '/' ) ).'" rel="home">' . get_bloginfo('name') . '</a></h1>';
				}else{
					$html .= '<h1 class="site-title"><a href="'.esc_url( home_url( '/' ) ).'" rel="home">' . get_bloginfo('name') . '</a></h1>';
				}
				
				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) {
					$html .= '<p class="site-description">'.$description.'</p>';
				}
			}
		}
		?>
		<div class="navbar-brand <?php echo esc_attr( join( ' ', $class ) ); ?>"><?php echo wp_kses_post($html); ?></div>
		<?php
	}
}

if ( ! function_exists( 'hotelone_load_section' ) ) {
	function hotelone_load_section( $Section_Id ){
		
		do_action('hotelone_before_section_' . $Section_Id);
        do_action('hotelone_before_section_part', $Section_Id);

        get_template_part('section-all/section', $Section_Id );

        do_action('hotelone_after_section_part', $Section_Id);
        do_action('hotelone_after_section_' . $Section_Id);
	}
}

if( ! function_exists('hotelone_footer_widget')){
	function hotelone_footer_widget(){
		$column = absint( get_theme_mod( 'footer_column_layout' , 4 ) );
		$max_cols = 12;
        $layouts = 12;
        if ( $column > 1 ){
            $default = "12";
            switch ( $column ) {
                case 4:
                    $default = '3+3+3+3';
                    break;
                case 3:
                    $default = '4+4+4';
                    break;
                case 2:
                    $default = '6+6';
                    break;
            }
            $layouts = sanitize_text_field( get_theme_mod( 'footer_custom_'.$column.'_columns', $default ) );
        }

        $layouts = explode( '+', $layouts );
        foreach ( $layouts as $k => $v ) {
            $v = absint( trim( $v ) );
            $v =  $v >= $max_cols ? $max_cols : $v;
            $layouts[ $k ] = $v;
        }

        $have_widgets = false;

        for ( $count = 0; $count < $column; $count++ ) {
            $id = 'footer-' . ( $count + 1 );
            if ( is_active_sidebar( $id ) ) {
                $have_widgets = true;
            }
        }
		
		if ( $column > 0 && $have_widgets ) {
		?>
		<div class="footer_top">
			<div class="container">
				<div class="row">	
					<?php
					 for ( $count = 0; $count < $column; $count++ ) {
                     $col = isset( $layouts[ $count ] ) ? $layouts[ $count ] : '';
                     $id = 'footer-' . ( $count + 1 );
                     if ( $col ) {
					?>
					<div id="hotelone-footer-<?php echo esc_attr( $count + 1 ) ?>" class="col-md-<?php echo esc_attr( $col ); ?> col-sm-12">
                        <?php dynamic_sidebar( $id ); ?>
                    </div>
					<?php 
						}
					} 
					?>
				</div><!-- .row -->	
			</div><!-- .container -->
		</div>
		<?php
		}
	}
}
add_action('hotelone_footer_site','hotelone_footer_widget', 10 );

if( ! function_exists('hotelone_footer_copyright')){
	function hotelone_footer_copyright(){
		$html = get_theme_mod( 'footer_copyright_text', wp_kses_post('&copy; 2020, Hotelone WordPress Theme by <a href="'.esc_url('http://britetechs.com').'">Britetechs</a>','hotelone' ));
		?>
		<div class="footer_bottom copy_right">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">						
						<p class="wow animated fadeInUp"><?php echo wp_kses_post( $html ); ?></p>
					</div>
				</div>
			</div>
		</div>		
		<?php		
	}
}
add_action('hotelone_footer_site','hotelone_footer_copyright', 15 );


if ( ! function_exists( 'hotelone_get_section_services_data' ) ) {
	
	function hotelone_get_section_services_data(){
		$services = get_theme_mod('hotelone_services');
		if (is_string($services)) {
            $services = json_decode($services, true);
        }
		$page_ids = array();
		if (!empty($services) && is_array($services)) {
            foreach ($services as $k => $v) {
                if (isset ($v['content_page'])) {
                    $v['content_page'] = absint($v['content_page']);
                    if ($v['content_page'] > 0) {
                        $page_ids[] = wp_parse_args($v, array(
                            'icon_type' => 'icon',
                            'image' => '',
                            'icon' => 'gg',
                            'enable_link' => 0
                        ));
                    }
                }
            }
        }
        return $page_ids;
	}
	
}
if ( ! function_exists( 'hotelone_get_section_rooms_data' ) ) {
	
	function hotelone_get_section_rooms_data(){
		$rooms = get_theme_mod('hotelone_room');
		if (is_string($rooms)) {
            $rooms = json_decode($rooms, true);
        }
		$page_ids = array();
		if (!empty($rooms) && is_array($rooms)) {
            foreach ($rooms as $k => $v) {
                if (isset ($v['content_page'])) {
                    $v['content_page'] = absint($v['content_page']);
                    if ($v['content_page'] > 0) {
                        $page_ids[] = wp_parse_args($v, array(
                            'rating' => '5',
                            'person' => 2,
                            'price' => '$100 / Per Night',
                            'enable_link' => 0
                        ));
                    }
                }
            }
        }
        return $page_ids;
	}
	
}
add_action('wp_head','hotelone_primary_color');
function hotelone_primary_color(){
	
	$custom_color_enable = get_theme_mod( 'custom_color_enable', false );
	$theme_color = get_theme_mod( 'theme_color', '#d8c46c' );
	$custom_color_scheme = get_theme_mod( 'custom_color_scheme', '#d8c46c' );
	if($custom_color_enable==true){
		$color = $custom_color_scheme;
	}else{
		$color = $theme_color;
	}
	
	echo '<style id="hotelone-color">';
		hotelone_set_color($color);
	echo '</style>';
}

function hotelone_set_color( $color = '#d8c46c' ){
	list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
	
	$option = wp_parse_args(  get_option( 'hotelone_option', array() ), hotelone_reset_data() );
	$color = isset($option['theme_color'])?$option['theme_color']:$color;
	?>
	:root{
		--primary-color: <?php echo esc_attr( $color ); ?>;
		--theme-primary-color: <?php echo esc_attr($color); ?>;
		--r: <?php echo esc_attr( $r ); ?>;
		--g: <?php echo esc_attr( $g ); ?>;
		--b: <?php echo esc_attr( $b ); ?>;
		--header-top-bg-color: <?php echo (get_theme_mod('header_top_bg_color')?esc_attr(get_theme_mod('header_top_bg_color')):$color); ?>;
		--header-top-text-color: <?php echo (get_theme_mod('header_top_text_color')?esc_attr(get_theme_mod('header_top_text_color')):'#ffffff'); ?>;
		--site-title-color: <?php echo (get_theme_mod('site_title_color')?esc_attr(get_theme_mod('site_title_color')):'#27282D'); ?>;
		--site-tagline-color: <?php echo (get_theme_mod('site_tagline_color')?esc_attr(get_theme_mod('site_tagline_color')):'#a2a2a2'); ?>;
		--navbar-bg-color: <?php echo (get_theme_mod('navbar_bg_color')?esc_attr(get_theme_mod('navbar_bg_color')):'#ffffff'); ?>;
		--navbar-link-color: <?php echo (get_theme_mod('navbar_link_color')?esc_attr(get_theme_mod('navbar_link_color')):'#27282D'); ?>;
		--navbar-link-hover-color: <?php echo (get_theme_mod('navbar_link_hover_color')?esc_attr(get_theme_mod('navbar_link_hover_color')):$color); ?>;
		--dropdown-link-bg-color: <?php echo (get_theme_mod('dropdown_link_bg_color')?esc_attr(get_theme_mod('dropdown_link_bg_color')):'#0c0c0c'); ?>;
		--footer-widget-bg-color: <?php echo (get_theme_mod('footer_widget_bg_color')?esc_attr(get_theme_mod('footer_widget_bg_color')):'#171717'); ?>;
		--footer-widget-text-color: <?php echo (get_theme_mod('footer_widget_text_color')?esc_attr(get_theme_mod('footer_widget_text_color')):'#999'); ?>;
		--footer-widget-link-hover-color: <?php echo (get_theme_mod('footer_widget_link_hover_color')?esc_attr(get_theme_mod('footer_widget_link_hover_color')):'#999'); ?>;
		--footer-widget-title-color: <?php echo (get_theme_mod('footer_widget_title_color')?esc_attr(get_theme_mod('footer_widget_title_color')):'#ffffff'); ?>;
		--footer-copyright-bg-color: <?php echo (get_theme_mod('footer_copyright_bg_color')?esc_attr(get_theme_mod('footer_copyright_bg_color')):'#0f0f0f'); ?>;
		--footer-copyright-text-color: <?php echo (get_theme_mod('footer_copyright_text_color')?esc_attr(get_theme_mod('footer_copyright_text_color')):'#999'); ?>;
		--footer-copyright-link-color: <?php echo (get_theme_mod('footer_copyright_link_color')?esc_attr(get_theme_mod('footer_copyright_link_color')):'#6d6d6d'); ?>;
		--footer-copyright-link-hover-color: <?php echo (get_theme_mod('footer_copyright_link_hover_color')?esc_attr(get_theme_mod('footer_copyright_link_hover_color')):'#999'); ?>;
		--service-title-color: <?php echo (get_theme_mod('service_title_color')?esc_attr(get_theme_mod('service_title_color')):'#27282D'); ?>;
		--service-subtitle-color: <?php echo (get_theme_mod('service_subtitle_color')?esc_attr(get_theme_mod('service_subtitle_color')):'#828282'); ?>;
		--room-title-color: <?php echo (get_theme_mod('room_title_color')?esc_attr(get_theme_mod('room_title_color')):'#27282D'); ?>;
		--room-subtitle-color: <?php echo (get_theme_mod('room_subtitle_color')?esc_attr(get_theme_mod('room_subtitle_color')):'#828282'); ?>;
		--testimonial-title-color: <?php echo (get_theme_mod('testimonial_title_color')?esc_attr(get_theme_mod('testimonial_title_color')):'#ffffff'); ?>;
		--testimonial-subtitle-color: <?php echo (get_theme_mod('testimonial_subtitle_color')?esc_attr(get_theme_mod('testimonial_subtitle_color')):'#ffffff'); ?>;
		--team-title-color: <?php echo (get_theme_mod('team_title_color')?esc_attr(get_theme_mod('team_title_color')):'#27282D'); ?>;
		--team-subtitle-color: <?php echo (get_theme_mod('team_subtitle_color')?esc_attr(get_theme_mod('team_subtitle_color')):'#828282'); ?>;
		--news-title-color: <?php echo (get_theme_mod('news_title_color')?esc_attr(get_theme_mod('news_title_color')):'#27282D'); ?>;
		--news-subtitle-color: <?php echo (get_theme_mod('news_subtitle_color')?esc_attr(get_theme_mod('news_subtitle_color')):'#828282'); ?>;
		--calltoaction-title-color: <?php echo (get_theme_mod('calltoaction_title_color')?esc_attr(get_theme_mod('calltoaction_title_color')):'#ffffff'); ?>;
		--calltoaction-subtitle-color: <?php echo (get_theme_mod('calltoaction_subtitle_color')?esc_attr(get_theme_mod('calltoaction_subtitle_color')):'#ffffff'); ?>;
		--client-title-color: <?php echo (get_theme_mod('client_title_color')?esc_attr(get_theme_mod('client_title_color')):'#27282D'); ?>;
		--client-subtitle-color: <?php echo (get_theme_mod('client_subtitle_color')?esc_attr(get_theme_mod('client_subtitle_color')):'#828282'); ?>;
		--counter-title-color: <?php echo (get_theme_mod('counter_title_color')?esc_attr(get_theme_mod('counter_title_color')):'#ffffff'); ?>;
		--counter-subtitle-color: <?php echo (get_theme_mod('counter_subtitle_color')?esc_attr( get_theme_mod('counter_subtitle_color') ):'#ffffff'); ?>;
		--video-title-color: <?php echo (get_theme_mod('video_title_color')?esc_attr( get_theme_mod('video_title_color')):'#ffffff'); ?>;
	}
	.navbar-nav > li > a{
		padding: 12px <?php echo absint( get_theme_mod('hotelone_menu_padding','8') ); ?>px;
	}
	.subheader .subheaderInner{
		padding-top:<?php echo absint( get_theme_mod('hotelone_page_cover_pd_top',50) ); ?>px;
		padding-bottom:<?php echo absint( get_theme_mod('hotelone_page_cover_pd_bottom',50) ); ?>px;
	}
	.subheader .subheaderInner{ background-color: <?php echo esc_attr( get_theme_mod('hotelone_page_cover_overlay','rgba(255,255,255,.8)') ); ?>; }
	.subheader .subheaderInner .pageTitle{ color: <?php echo esc_attr( get_theme_mod('hotelone_page_cover_color','#27282D') ); ?>; }

	input:focus, 
	textarea:focus, 
	select:focus,
	.wpcf7 input[type='text']:focus, 
	.wpcf7 input[type='email']:focus, 
	.wpcf7 input[type='tel']:focus, 
	.wpcf7 input[type='url']:focus, 
	.wpcf7 input[type='number']:focus, 
	.wpcf7 input[type='date']:focus,
	.wpcf7 textarea:focus { border-color: <?php echo esc_attr( $color ); ?>; }

	input[type='submit'], 
	.wpcf7-submit,
	.carousel-control,
	.big_title:after,
	.card-service .service-icon,
	.callout_section .callout-btn,
	.video_section .video-icon,
	.video_section .video-icon:hover,
	.card-room .overlay-btn,
	.rooms-tabs li a:hover, 
	.rooms-tabs li a:focus,
	.rooms-tabs li.active a,
	.nav-pills .nav-link.active, 
	.nav-pills .show > .nav-link
	#testimonial_carousel .carousel-indicators .active,
	.news_overlay_icon,
	.more-link,
	.count .count_icon,
	.team_social_icons,
	.contact_icons li span,
	.gallery-area .gallery-icon,
	.continue,
	a.continue:hover, 
	a.continue:focus,
	.blog-tags li span,
	.page-numbers.current,
	.secondary .widget .widget_title:before,
	.footer_section .widget .widget_title:after,
	.footer-addr .footer-cont-icon,
	.more-link:hover,
	.more-link:focus,
	.hotel-primary,
	.hotel-secondry:hover,
	.owl-carousel .owl-dot,
	.news_date span:first-child  { background-color: <?php echo esc_attr( $color ); ?>; }

	.hotel-primary:hover,
	.hotel-primary:focus,
	.section .section-title span,
	.card-service .service-content a:hover h4,
	.card-service .service-content a:focus h4,
	.card-room .overlay-btn:hover, 
	.card-room .overlay-btn:focus,
	.card-room .room-content .room_title:hover,
	.card-room .room-content .room_title:focus,
	.news .news_title:hover h3,
	.news .news_title:focus h3,
	.news .news_details a,
	.team .team_title:hover h3,
	.team .team_title:focus h3,
	.team_designation,
	.aboutPage_section h2 span,
	.service_section h2 span,
	.contactPage a,
	.contactPage a:hover,
	.contactPage a:focus,
	.contactPageTitle,
	.galleryPage .galleryTitle span,
	.error-page h2,
	.widget ul li a:hover,
	.widget ul li a:focus,
	.author-block .social-links ul li a:hover,
	.author-block .social-links ul li a:focus,
	.event_thumbnial:hover .event-icon,
	.event_date i,
	.comments-area a,
	.author-block .social-links a,
	.post-content a { color: <?php echo esc_attr( $color ); ?>; }

	.card-room .overlay-btn,
	.rooms-tabs li a:hover, 
	.rooms-tabs li a:focus,
	.rooms-tabs li.active a,
	.team_social_icons,
	.gallery-area .gallery-icon,
	.hotel-primary,
	.hotel-primary:focus,
	.hotel-primary:hover,
	.hotel-primary:focus,
	.hotel-secondry:hover,
	.hotel-secondry:focus{ border: 1px solid <?php echo esc_attr( $color ); ?>; }
	
	blockquote{ border-left: 5px solid <?php echo esc_attr( $color ); ?>; }

	.testimonial .media-left img,
	.bypostauthor > .comment-body > .comment-meta > .comment-author .avatar,
	.author-block img.avatar{ border-color: <?php echo esc_attr( $color ); ?>;}

	.event_thumbnial:after{ background: linear-gradient(to top right, <?php echo esc_attr( $color ); ?> 0%, #020202 100%); }
	
	<?php	
	$b_fontfamily = $option['typo_p_fontfamily'];
	$m_fontfamily = $option['typo_m_fontfamily'];
	$h_fontfamily = $option['typo_h_fontfamily'];

	$page_background_color = get_theme_mod('page_bg_color','#E5E5E5');
	?>
	body{
		<?php if($b_fontfamily){ ?>
		font-family: '<?php echo esc_attr($b_fontfamily); ?>' !important;
		<?php } ?>
	}

	.navbar-nav > li > a,
	.dropdown-menu > li > a{
		<?php if($m_fontfamily){ ?>
		font-family: '<?php echo esc_attr($m_fontfamily); ?>' !important;
		<?php } ?>
	}
	
	h1,
	h2,
	h3,
	h4,
	h5,
	h6{ 
	<?php if($h_fontfamily){ ?>
	font-family: '<?php echo esc_attr($h_fontfamily); ?>';
	<?php } ?> 
	}

	/* default page bg color */
	.page-template-default,
	.page-template-default .site-content,
	.post-template-default,
	.post-template-default .site-content,
	.blog .site-content,
	.archive .site-content{
		background-color: <?php echo esc_attr( $page_background_color );?>;
	}
	<?php
}


/**
 * Flush out the transients
 */
function hotelone_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'hotelone_categories' );
}
add_action( 'edit_category', 'hotelone_category_transient_flusher' );
add_action( 'save_post',     'hotelone_category_transient_flusher' );
function hotelone_categorized_blog() {
	$category_count = get_transient( 'hotelone_categories' );

	if ( false === $category_count ) {
		$categories = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			'number'     => 2,
		) );


		$category_count = count( $categories );

		set_transient( 'hotelone_categories', $category_count );
	}

	
	if ( is_preview() ) {
		return true;
	}

	return $category_count > 1;
}


/**
 * Author Profile
 *
 */
/* add_filter( 'user_contactmethods', 'hotelone_author_profile', 10, 1);
function hotelone_author_profile(){
	$contactmethods['author_facebook'] = __('Author Facebook URL','hotelone');
	$contactmethods['author_twitter'] = __('Author Twitter URL','hotelone');
	$contactmethods['author_linkedin'] = __('Author Linkedin URL','hotelone');
	$contactmethods['author_googleplus'] = __('Author Google Plus URL','hotelone');
	$contactmethods['author_youtube'] = __('Author Youtube URL','hotelone');
return $contactmethods;
} */

if ( ! function_exists( 'hotelone_author_detail' ) ) :
function hotelone_author_detail(){
?>
<div class="author-block clearfix">
	<?php echo get_avatar( get_the_author_meta( 'ID') , 100 ); ?>									
	<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>" class="author_title">
			<h3><?php the_author(); ?></h3>
		</a>
	<p><?php the_author_meta( 'description' ); ?></p>
	<div class="social-links">
			
		<?php 
		$author_facebook = get_the_author_meta( 'author_facebook' );
		$author_twitter = get_the_author_meta( 'author_twitter' );
		$author_linkedin = get_the_author_meta( 'author_linkedin' );
		$author_googleplus = get_the_author_meta( 'author_googleplus' );
		$author_youtube = get_the_author_meta( 'author_youtube' );
		?>
		<ul>
			<?php if($author_facebook && $author_facebook!=''): ?>
			<li class="Facebook"><a href="<?php echo esc_url($author_facebook); ?>" title="Facebook" rel="tooltip"><i class="fa fa-facebook-f"></i></a></li>
			<?php endif; ?>
			
			<?php if($author_twitter && $author_twitter!=''): ?>
			<li class="Twitter"><a href="<?php echo esc_url($author_twitter); ?>" title="Twitter" rel="tooltip"><i class="fa fa-twitter"></i></a></li>
			<?php endif; ?>
			
			<?php if($author_googleplus && $author_googleplus!=''): ?>
			<li class="Google-Plus"><a href="<?php echo esc_url($author_googleplus); ?>" title="Google Plus" rel="tooltip"><i class="fa fa-google-plus"></i></a></li>
			<?php endif; ?>
			
			<?php if($author_linkedin && $author_linkedin!=''): ?>
			<li class="Linked-In"><a href="<?php echo esc_url($author_linkedin); ?>" title="Linked In" rel="tooltip"><i class="fa fa-linkedin"></i></a></li>
			<?php endif; ?>
			
			<?php if($author_youtube && $author_youtube!=''): ?>
			<li class="Youtube"><a href="<?php echo esc_url($author_youtube); ?>" title="Youtube" rel="tooltip"><i class="fa fa-youtube-play"></i></a></li>										
			<?php endif; ?>
			
		</ul>
	</div>
		
</div>
<?php 
}
endif;