<?php 
$disable_tb       = get_theme_mod( 'disable_header_tb', 0 );

$facebook_icon       = get_theme_mod( 'hide_facebook_icon', 0 );
$facebook_url     = get_theme_mod( 'facebook_url', '#' );

$twitter_icon       = get_theme_mod( 'hide_twitter_icon', 0 );
$twitter_url      = get_theme_mod( 'twitter_url', '#' );

$google_plus_icon       = get_theme_mod( 'hide_google_plus_icon', 0 );
$google_plus_url  = get_theme_mod( 'google_plus_url', '#' );

$houzz_icon       = get_theme_mod( 'hide_houzz_icon', 0 );
$houzz_url        = get_theme_mod( 'houzz_url', '#' );

$social_target    = get_theme_mod( 'social_target', 0 );
$phone            = get_theme_mod( 'phone', ' +33 555 66 777' );
$phone_url        = get_theme_mod( 'phone_url', 'tel: +33 555 66 777' );
$email            = get_theme_mod( 'email', 'info@example.com' );
$email_url        = get_theme_mod( 'email_url', 'mailto:info@example.com' );
$header_contained = get_theme_mod( 'hotelone_header_width', 'contained' );

$header_container = 'container-fluid';
if( $header_contained == 'contained' ){
	$header_container = 'container';
}

$header_pos = sanitize_text_field(get_theme_mod('hotelone_header_position', 'top'));
if ($header_pos == 'below_slider' ) {
	$disable_tb = true;
}
if( !$disable_tb ){
?>
<div class="header-top">
	<div class="<?php echo esc_attr( $header_container ); ?>">
		<div class="row">
			<div class="col-md-6">
				<ul class="header-social">
					<?php if( $facebook_icon == false ){ ?>
					<li class="facebook"><a href="<?php echo esc_url( $facebook_url ); ?>" <?php if($social_target){ echo 'target="_blank"'; }?>><i class="fa fa-facebook"></i></a></li>
					<?php } ?>
					<?php if( $twitter_icon == false ){ ?>
					<li class="twitter"><a href="<?php echo esc_url( $twitter_url ); ?>" <?php if($social_target){ echo 'target="_blank"'; }?>><i class="fa fa-twitter"></i></a></li>
					<?php } ?>
					<?php if( $google_plus_icon == false ){ ?>
					<li class="google-plus"><a href="<?php echo esc_url( $google_plus_url ); ?>" <?php if($social_target){ echo 'target="_blank"'; }?>><i class="fa fa-google-plus"></i></a></li>
					<?php } ?>
					<?php if( $houzz_icon == false ){ ?>
					<li class="houzz"><a href="<?php echo esc_url( $houzz_url ); ?>" <?php if($social_target){ echo 'target="_blank"'; }?>><i class="fa fa-houzz"></i></a></li>
					<?php } ?>
				</ul>
			</div>
			<div class="col-md-6">
				<ul class="header-info">
					<?php if( $phone ){ ?>
					<li><a href="<?php echo esc_url( $phone_url ); ?>"><i class="fa fa-phone"></i> <?php echo esc_html( $phone ); ?></a></li>
					<?php } ?>
					<?php if( $email ){ ?>
					<li><a href="<?php echo esc_url( $email_url ); ?>"><i class="fa fa-envelope"></i> <?php echo esc_html( $email ); ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div><!-- .container -->
</div><!-- .header-top -->
<?php } ?>