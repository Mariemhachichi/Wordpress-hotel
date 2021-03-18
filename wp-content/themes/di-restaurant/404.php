<?php get_header(); ?>

<div class="col-md-8 container-box-left">
	<div  class="post-inner">

		<div class="post-thumbnail-otr">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/no-posts-found.png" class="img-fluid">
		</div>
		
		<br /><br />

		<h3 class="the-title"><?php esc_html_e( 'Error 404', 'di-restaurant' ); ?></h3>

		<p><?php esc_html_e( 'Page does not exist, Maybe try a search?', 'di-restaurant' ); ?></p>

		<div class="nopostsfound">
			<?php get_search_form(); ?>
		</div>

		<div class="recents-nopostsfound">
			<?php
			the_widget( 'WP_Widget_Recent_Posts' );
			?>
		</div>

	</div>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
