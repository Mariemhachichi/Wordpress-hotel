(function( $ ) {

	// Site title.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-name-pr' ).text( to );
		});
	});

} )( jQuery );
