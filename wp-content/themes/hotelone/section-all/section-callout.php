<?php 
$disable_callout   = get_theme_mod( 'hotelone_calltoaction_hide', 1 );
$callout_title    = get_theme_mod( 'hotelone_calltoaction_title', wp_kses_post('WordPress Theme For Hotels','hotelone') );
$callout_subtitle    = get_theme_mod( 'hotelone_calltoaction_subtitle', wp_kses_post('Lorem ipsum dolor sit ame sed do eiusmod tempor incididunt ut labore et dolore','hotelone') );
$callout_button_text    = get_theme_mod( 'hotelone_calltoaction_btn_text', wp_kses_post('Download Now','hotelone') );
$callout_button_url    = get_theme_mod( 'hotelone_calltoaction_btn_URL', '#' );

$bgcolor    = get_theme_mod( 'hotelone_calltoaction_bgcolor', '#333');
$bgimage    = get_theme_mod( 'hotelone_calltoaction_bgimage', '');

$class = '';
if( !empty( $bgimage ) ){
	$class = 'section-overlay';
}

if( ! $disable_callout ){
if ( is_active_sidebar( 'front-page-calltoaction-top' ) ) { ?>
<div class="frontpage_siderbar">
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar( 'front-page-calltoaction-top' ); ?>
		</div>
	</div>
</div>
<?php } ?>
<div id="callout" class="callout_section section <?php echo esc_attr( $class ); ?>" style="background-color: <?php echo esc_attr( $bgcolor ); ?>; background-image: url(<?php echo esc_url( $bgimage ); ?>);">
	
	<?php do_action('hotelone_section_before_inner', 'callout'); ?>
	
	<?php if( !empty( $bgimage ) ){ ?>
	<div class="sectionOverlay">
	<?php } ?>
	
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					
					<?php if( !empty( $callout_title ) ) { ?>
					<h2 class="callout-title wow animated fadeInDown"><?php echo wp_kses_post( $callout_title ); ?></h2>
					<?php } ?>
					
					<?php if( !empty( $callout_subtitle ) ){ ?>
					<p class="callout-subtitle wow animated fadeInDown"><?php echo wp_kses_post( $callout_subtitle ); ?></p>
					<?php } ?>
					
					<?php if(!empty($callout_button_url)){ ?>
					<a class="callout-btn wow animated fadeInUp" href="<?php echo esc_url( $callout_button_url ); ?>"><?php echo esc_html( $callout_button_text ); ?></a>
					<?php } ?>
				</div>
			</div>		
		</div><!-- .container -->
		
	<?php if( !empty( $bgimage ) ){ ?>
	</div>
	<?php } ?>
	
	<?php do_action('hotelone_section_after_inner', 'callout'); ?>
	
</div><!-- .callout_section --> 
<?php if ( is_active_sidebar( 'front-page-calltoaction-bottom' ) ) { ?>
<div class="frontpage_siderbar">
	<div class="container">
		<div class="row">
			<?php dynamic_sidebar( 'front-page-calltoaction-bottom' ); ?>
		</div>
	</div>
</div>
<?php } 
 } ?>