<?php 
$header_contained            = get_theme_mod( 'hotelone_header_width', 'contained' );
$header_container = 'container-fluid';
if( $header_contained == 'contained' ){
	$header_container = 'container';
}

$sticky_header_class = 'nav_sticky';
$sticky_header = get_theme_mod( 'hotelone_sticky_header_disable', 0 );
if( $sticky_header ){
	$sticky_header_class = '';
}

$vertical_logo_class = 'col-lg-3 col-md-12 col-sm-12 col-xs-12 align-self-center text-center text-sm-center text-lg-left';
$vertical_nav_class = 'col-lg-9 col-md-12 col-sm-12 col-xs-12';
$vertical_navbar_class = 'navbar-nav d-inline-block ml-auto w-auto';
$vertical_logo = get_theme_mod( 'hotelone_vertical_align_menu', 0 );
if( $vertical_logo ){
	$vertical_logo_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 align-self-center text-center text-sm-center text-lg-center';
	$vertical_nav_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
	$vertical_navbar_class = 'navbar-nav d-inline-block ml-auto mr-auto w-auto';
}

$header_scroll_logo_class = ' ';
$header_scroll_logo = get_theme_mod( 'hotelone_header_scroll_logo', 0 );
if( $header_scroll_logo ){
	$header_scroll_logo_class = ' header_scroll_logo';
}
?>
<div class="nav-spacer"></div>
<nav class="top-nav-area hotelone_nav <?php  echo esc_attr( $sticky_header_class ); ?>">
	<div class="<?php echo esc_attr( $header_container ); ?>">
		<div class="row">
			<div class="<?php echo esc_attr( $vertical_logo_class . $header_scroll_logo_class ); ?> navbar-header">
				<?php 
				// hotelone theme logo
				hotelone_logo();
				?>
			</div>
			<div class="<?php echo esc_attr( $vertical_nav_class ); ?> align-self-center">
				<nav class="navbar navbar-expand-lg nav-menus p-0">
	              <div class="collapse navbar-collapse main-menu w-100">
	                <?php 
	                wp_nav_menu( array( 
	                  'theme_location' => 'primary',
	                  'menu_class'=> $vertical_navbar_class,
	                  'container' => '',
	                  'container_class' => 'w-100',
	                  'fallback_cb' => 'Hotelone_fallback_page_menu',
					  'walker' => new Hotelone_bootstrap_navwalker()
	                ) );
	                ?>
	              </div>
	          	</nav>
			</div>
		</div>
	</div><!-- .container-fluid -->
	<div class="theme_mobile_menu">
	  <div class="theme_mobile_container">
	    <header>
	      <nav>
	        <?php 
	        wp_nav_menu( array( 
	          'theme_location' => 'primary',
	          'container' => '',
	          'menu_class'=> '',
	        ) );
	        ?>
	      </nav>
	    </header>
	  </div>
	</div><!-- End .theme_mobile_menu -->
</nav><!-- menu -->
<div class="clearfix"></div>