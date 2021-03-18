	<div id="footer" class="footer_section">
		<?php
		do_action('hotelone_before_footer_site'); 

		do_action('hotelone_footer_site'); 
		
		do_action('hotelone_after_footer_site'); 
		?>		
	</div><!-- .footer_section -->

	<?php do_action( 'hotelone_before_site_end' ); ?>	
</div>
<?php 
$disable_bt = get_theme_mod('hotelone_btt_hide', false); 
if( $disable_bt == false ){ ?>
<a class="bottomScrollBtn" href="#" title="Scroll Top">
	<i class="fa fa-angle-double-up"></i>
</a>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>