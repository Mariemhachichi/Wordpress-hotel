<article id="post-<?php the_ID(); ?>" <?php post_class('blog_post'); ?>>
	
	<div class="blog-list-desc clearfix">
		<div class="blog-text">
			<h4><?php esc_html_e('Nothing Found', 'hotelone' ); ?></h4>
		</div>
		<div class="post-content">
			<?php
				if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

					<p><?php printf( sprintf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'hotelone' ), esc_url( admin_url( 'post-new.php' ) ) ) ); ?></p>

				<?php else : ?>

					<p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'hotelone' ); ?></p>
					<?php
						get_search_form();

				endif; ?>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->