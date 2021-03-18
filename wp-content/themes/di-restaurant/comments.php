<?php
//nothing to do if post_password_required
if( post_password_required() ) {
	return;
}

// If comments are not open or we have not any comment, do not load up the comment template.
if( ! have_comments() && ! comments_open() ) {
	return;
}
?>

<div class="comments-main-otr">

	<?php
	if ( have_comments() ) {
	?>
		<div id="comments" class="comments-main">
			<div class="comments-holder main-box">

				<h3 class="comment-title main-box-title">
					<?php
					printf( _nx( '%3$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'di-restaurant' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>', __( 'One', 'di-restaurant' ) );
					?>	
				</h3>

				<div class="main-box-inside content-inside">
					<ul class="comment-list">
						<?php
						wp_list_comments();
						?>
					</ul>
				</div>


				<?php
				// Are there comments to navigate through?
				if( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
					the_comments_navigation(
						array(
							'prev_text' => __( '&larr; Older Comments', 'di-restaurant' ),
							'next_text' => __( 'Newer Comments &rarr;', 'di-restaurant' ),
						)
					);
				}
				?>

			</div>
		</div>
	<?php
	}
	?>

	<div class="single-post-comment">
		<?php
		if( comments_open() ) {
			comment_form();
		}
		?>
	</div>
</div>
