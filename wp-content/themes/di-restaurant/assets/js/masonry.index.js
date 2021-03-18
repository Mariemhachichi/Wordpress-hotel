( function( $ ) {
	$(document).ready(function() {
		
		//masonry
		var $container = $('.dimasonry');
		$container.imagesLoaded(function(){
			$container.masonry({
			itemSelector: '.dimasonrybox'
			//percentPosition: true
			});
		});
			
	});
} )( jQuery );
