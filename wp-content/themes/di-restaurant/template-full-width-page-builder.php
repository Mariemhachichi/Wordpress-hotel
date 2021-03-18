<?php
/**
 * Template Name: Full Width for Page Builder
 *
 */
?>

<?php get_header( "full-width-page-builder" ); ?>

<div class="">

	<?php
	while( have_posts() ) : the_post();
		get_template_part( 'template-parts/content', 'full-width-page-builder' );
	endwhile;
	?>

	<?php
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
	?>

</div>

<?php get_footer( "full-width-page-builder" ); ?>
