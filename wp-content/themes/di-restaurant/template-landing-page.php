<?php
/**
 * Template Name: Landing Page for Page Builder
 *
 */
?>

<?php get_header( "landing-page" ); ?>

	<?php
	while( have_posts() ) : the_post();
		get_template_part( 'template-parts/content', 'landing-page' );
	endwhile;
	?>

	<?php
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
	?>

<?php get_footer( "landing-page" ); ?>
