<?php
// return if disabled in customize (globally disabled)
if( get_theme_mod( 'endis_ftr_wdgt', '0' )  == 0 ) {
	return;
}

// return if disabled using metabox (disabled on individual page || post)
if( is_home() ) {
	$di_post_id = get_option( 'page_for_posts' );
} else {
	$di_post_id = get_the_ID();
}

if( $di_post_id ) {
	if( get_post_meta( $di_post_id, '_di_restaurant_hide_footer_widgets', true ) == '1' ) {
		return;
	}
}

$layout = get_theme_mod( 'ftr_wdget_lyot', '3' );
?>

<div class="container-fluid footer-widgets">
	<div class="container">
		<div class="row">

			<?php
			if( $layout == 1 ) {
				?>
				<div class="col-md-12">
					<?php
					if( is_active_sidebar( 'footer_1' ) ) {
						dynamic_sidebar( 'footer_1' );
					}
					?>
				</div>
				<?php
			}
			?>

			<?php
			if( $layout == 2 ) {
				?>
				<div class="col-md-6">
					<?php
					if( is_active_sidebar( 'footer_1' ) ) {
						dynamic_sidebar( 'footer_1' );
					}
					?>
				</div>

				<div class="col-md-6">
					<?php
					if( is_active_sidebar( 'footer_2' ) ) {
						dynamic_sidebar( 'footer_2' );
					}
					?>
				</div>

				<?php
			}
			?>

			<?php
			if( $layout == 3 ) {
				?>
				<div class="col-md-4">
					<?php
					if( is_active_sidebar( 'footer_1' ) ) {
						dynamic_sidebar( 'footer_1' );
					}
					?>
				</div>

				<div class="col-md-4">
					<?php
					if( is_active_sidebar( 'footer_2' ) ) {
						dynamic_sidebar( 'footer_2' );
					}
					?>
				</div>

				<div class="col-md-4">
					<?php
					if( is_active_sidebar( 'footer_3' ) ) {
						dynamic_sidebar( 'footer_3' );
					}
					?>
				</div>

				<?php
			}
			?>


			<?php
			if( $layout == 4 ) {
				?>
				<div class="col-md-3">
					<?php
					if( is_active_sidebar( 'footer_1' ) ) {
						dynamic_sidebar( 'footer_1' );
					}
					?>
				</div>

				<div class="col-md-3">
					<?php
					if( is_active_sidebar( 'footer_2' ) ) {
						dynamic_sidebar( 'footer_2' );
					}
					?>
				</div>

				<div class="col-md-3">
					<?php
					if( is_active_sidebar( 'footer_3' ) ) {
						dynamic_sidebar( 'footer_3' );
					}
					?>
				</div>

				<div class="col-md-3">
					<?php
					if( is_active_sidebar( 'footer_4' ) ) {
						dynamic_sidebar( 'footer_4' );
					}
					?>
				</div>

				<?php
			}
			?>

			<?php
			if( $layout == 48 ) {
				?>
				<div class="col-md-4">
					<?php
					if( is_active_sidebar( 'footer_1' ) ) {
						dynamic_sidebar( 'footer_1' );
					}
					?>
				</div>

				<div class="col-md-8">
					<?php
					if( is_active_sidebar( 'footer_2' ) ) {
						dynamic_sidebar( 'footer_2' );
					}
					?>
				</div>

				<?php
			}
			?>

			<?php
			if( $layout == 84 ) {
				?>
				<div class="col-md-8">
					<?php
					if( is_active_sidebar( 'footer_1' ) ) {
						dynamic_sidebar( 'footer_1' );
					}
					?>
				</div>

				<div class="col-md-4">
					<?php
					if( is_active_sidebar( 'footer_2' ) ) {
						dynamic_sidebar( 'footer_2' );
					}
					?>
				</div>

				<?php
			}
			?>


		</div>
	</div>
</div>