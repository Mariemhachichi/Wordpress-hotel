<?php get_header(); ?>


<?php
$blog_single_layout = get_theme_mod( 'blog_single_layout', 'rights' );

if( $blog_single_layout == 'rights' ) {
	$single_layout = 'col-md-8 container-box-left';
} elseif( $blog_single_layout == 'lefts' ) {
	$single_layout = 'col-md-8 container-box-left layoutleftsidebar';
} else {
	$single_layout = 'col-md-12 container-box-left';
}
?>

<!-----##### post  section start ######------>
<div class="<?php echo esc_attr( $single_layout ); ?>">

	<?php
	while( have_posts() ) : the_post();
		get_template_part( 'template-parts/content', 'single' );
	endwhile;
	?>

	<?php
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
	?>

	<?php
	if( get_theme_mod( 'singl_ftr_nav_endis', '1' ) == 1 ) {
		the_post_navigation(
			array(
				'prev_text'	=> '&larr; %title',
				'next_text'	=> '%title &rarr;',
			)
		);
	}
	?>

</div>
<!-----##### post section end ######------>

<?php
if( $blog_single_layout ==  'rights' || $blog_single_layout ==  'lefts' ) {
	get_sidebar();
}
?>

<?php get_footer(); ?>
