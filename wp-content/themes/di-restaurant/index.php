<?php get_header(); ?>

<?php
$blog_archive_layout = get_theme_mod( 'blog_archive_layout', 'rights' );

if( $blog_archive_layout == 'rights' ) {
	$archive_layout = 'col-md-8 container-box-left';
} elseif( $blog_archive_layout == 'lefts' ) {
	$archive_layout = 'col-md-8 container-box-left layoutleftsidebar';
} else {
	$archive_layout = 'col-md-12 container-box-left';
}
?>
<div class="<?php echo esc_attr( $archive_layout ); ?>">

	<?php
	$blog_list_grid_indehef = get_theme_mod( 'blog_list_grid', 'list' );
	?>
	<div class="<?php
	if( $blog_list_grid_indehef == 'list' || $blog_list_grid_indehef == 'grid2c' || $blog_list_grid_indehef == 'grid3c' ) {
		echo 'row';
	}
	?>">

		<?php
		if( have_posts() ) {

			if( $blog_list_grid_indehef == 'msry2c' || $blog_list_grid_indehef == 'msry3c' ) {
				echo '<div class="dimasonry">';
			}

				while( have_posts() ) : the_post();
					get_template_part( 'template-parts/content', 'loop' );
				endwhile;

			if( $blog_list_grid_indehef == 'msry2c' || $blog_list_grid_indehef == 'msry3c' ) {
				echo '</div>';
			}
			

			if( get_theme_mod( 'archive_ftr_nav_endis', '1' ) == 1 ) {
				?>
				<div class="container posts_pagination">
					<?php
					the_posts_pagination( array(
						'prev_text' => __( '&laquo;', 'di-restaurant' ),
						'next_text' => __( '&raquo;', 'di-restaurant' ),
					) );
					?>
				</div>
				<?php
			}

		} else {
			get_template_part( 'template-parts/content', 'none' );
		}
		?>
	</div>
</div>

<?php
if( $blog_archive_layout ==  'rights' || $blog_archive_layout ==  'lefts' ) {
	get_sidebar();
}
?>

<?php get_footer(); ?>
