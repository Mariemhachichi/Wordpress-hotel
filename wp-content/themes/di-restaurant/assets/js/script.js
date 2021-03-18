( function( $ ) {
	$(document).ready(function() {
		
		//Add img-fluid class to all images
		$('body img').addClass("img-fluid");
		// Remove img-fluid class for elementor content.
		$('body.elementor-page .elementor img').removeClass('img-fluid');


		// Title in tooltip for top bar right icons
		$('.social-div a[title]').tooltip( {placement: "bottom"} );
		
			
	});
} )( jQuery );
