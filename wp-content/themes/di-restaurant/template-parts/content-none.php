
<div class="post-inner">
	
	<div class="post-thumbnail-otr">
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?> /assets/images/no-posts-found.png" class="img-fluid">
	</div>

	<div class="post-category">
		<span class="post-category-span">
			<a href="javascript:void(0)"><?php esc_html_e( 'No Posts', 'di-restaurant' ); ?></a>
		</span>

	</div>

	<h3 class="the-title"><?php esc_html_e( 'Posts Not Found', 'di-restaurant' ); ?></h3>

	<p><?php esc_html_e( 'Maybe try a search?', 'di-restaurant' ); ?></p>

	<div class="nopostsfound">
		<?php get_search_form(); ?>
	</div>

	<div class="recents-nopostsfound">
		<?php
		the_widget( 'WP_Widget_Recent_Posts' );
		?>
	</div>

</div>

