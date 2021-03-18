( function( $ ) {
	$(document).ready(function() {
		
		// Nav Main DD Toggle
		$( ".navbarprimary .dropdowntoggle" ).click( function() {
			if( $(this).parent('li').hasClass('navbarprimary-open') ) {
				$(this).parent('li').removeClass('navbarprimary-open');
			} else {
				$(this).parent('li').addClass('navbarprimary-open');
			}

			if( $(this).children('a').children('span').hasClass('fa-chevron-circle-down') ) {
				$(this).children('a').children('span').removeClass('fa-chevron-circle-down');
				$(this).children('a').children('span').addClass('fa-chevron-circle-right');
			} else {
				$(this).children('a').children('span').removeClass('fa-chevron-circle-right');
				$(this).children('a').children('span').addClass('fa-chevron-circle-down');
			}
			
			return false;
		});

		// Sticky nav bar
		var h = $('.navbarprimary').offset().top;
		$(window).scroll(function () {
			if( $(this).scrollTop() > h ) {
				$('.navbarprimary').addClass('sticky_menu_top');
			} else {
				$('.navbarprimary').removeClass('sticky_menu_top');
			}
			
		});

		// Make accessible menu
		var links = $( '.navbarprimary .navbar-collapse a' );

		// Each time a menu link is focused or blurred, toggle focus.
		for ( i = 0, len = links.length; i < len; i++ ) {
			links[i].addEventListener( 'focus', toggleFocus, true );
			links[i].addEventListener( 'blur', toggleFocus, true );
		}
		
		/**
		 * Sets or removes .focus class on an element.
		 */
		function toggleFocus() {
			var self = this;

			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( -1 === self.className.indexOf( 'navbar-nav' ) ) {

				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					
					if ( -1 !== self.className.indexOf( 'focus' ) ) {
						self.className = self.className.replace( ' focus', '' );
					} else {
						self.className += ' focus';
					}
				}
				self = self.parentElement;
			}
		}

	});
} )( jQuery );
