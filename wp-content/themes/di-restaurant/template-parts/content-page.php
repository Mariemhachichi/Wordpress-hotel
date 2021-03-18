<div id="post-<?php the_ID(); ?>" <?php post_class( 'post-inner' ); ?> itemscope itemtype="http://schema.org/CreativeWork">

	<?php
		Di_Restaurant_Methods::the_thumbnail();
	?>

	<h1 class="the-title entry-title" itemprop="headline"><?php the_title(); ?></h1>

	<div class="entry-content" itemprop="text">
		<?php
		the_content();
		?>
	</div>

	<?php
	wp_link_pages(
		array(
		'before' => '<div class="page-links">' . __( 'Pages:', 'di-restaurant' ),
		'after'  => '</div>',
		)
	);
	?>

</div>
