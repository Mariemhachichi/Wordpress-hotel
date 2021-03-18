( function( api ) {

	// Extends our custom "ours-restaurant" section.
	api.sectionConstructor['ours-restaurant'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );
