( function( $ , api ) {

    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            $( '.site-title' ).text( to );
        } );
    } );
	
	wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            $( '.site-description' ).text( to );
        } );
    } );

} )( jQuery , wp.customize );