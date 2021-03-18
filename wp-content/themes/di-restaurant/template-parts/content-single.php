<div id="post-<?php the_ID(); ?>" <?php post_class( 'post-inner' ); ?> itemscope itemtype="http://schema.org/CreativeWork">

	<?php
		Di_Restaurant_Methods::the_thumbnail();
	?>

	<div class="post-category">
		<?php
		the_category( ' ' );
		?>
	</div>

	<h1 class="the-title post-headline-typog entry-title" itemprop="headline"><?php the_title(); ?></h1>

	<?php
	if( get_theme_mod( 'dispostdt', 'published' ) == 'published' ) {
		?>
		<div class="post-time post-date updated" itemprop="dateModified"><?php the_date(); ?></div>
		<?php
	} elseif( get_theme_mod( 'dispostdt', 'published' ) == 'updated' ) {
		?>
		<div class="post-time post-date updated" itemprop="dateModified"><?php the_modified_date(); ?></div>
		<?php
	} else {
		echo ''; // Nothing to print.
	}
	?>

	<div class="excerpt-or-content-typog entry-content" itemprop="text">
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

	<?php
	if( get_theme_mod( 'singl_tgs_endis', '1' )  == 1 ) {
		if( has_tag() ) { ?>
			<div class="widgets_sidebar widget_tag_cloud"><div class="tagcloud"><?php the_tags( '', ' ', '' ); ?></div></div>
		<?php
		}
	}
	?>

</div>