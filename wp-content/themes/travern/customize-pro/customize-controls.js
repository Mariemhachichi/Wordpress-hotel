( function( api ) {

	// Extends our custom "travern" section.
	api.sectionConstructor['travern'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );