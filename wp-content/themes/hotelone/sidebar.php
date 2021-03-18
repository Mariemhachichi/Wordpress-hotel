<?php 
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */
 
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<div class="col-md-4 col-sm-4 secondary">
	<div class="">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div>
</div><!-- .secondary -->