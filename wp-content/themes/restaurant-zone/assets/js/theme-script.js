function restaurant_zone_openNav() {
  jQuery(".sidenav").addClass('show');
}
function restaurant_zone_closeNav() {
  jQuery(".sidenav").removeClass('show');
}

( function( window, document ) {
  function restaurant_zone_keepFocusInMenu() {
    document.addEventListener( 'keydown', function( e ) {
      const restaurant_zone_nav = document.querySelector( '.sidenav' );

      if ( ! restaurant_zone_nav || ! restaurant_zone_nav.classList.contains( 'show' ) ) {
        return;
      }

      const elements = [...restaurant_zone_nav.querySelectorAll( 'input, a, button' )],
        restaurant_zone_lastEl = elements[ elements.length - 1 ],
        restaurant_zone_firstEl = elements[0],
        restaurant_zone_activeEl = document.activeElement,
        tabKey = e.keyCode === 9,
        shiftKey = e.shiftKey;

      if ( ! shiftKey && tabKey && restaurant_zone_lastEl === restaurant_zone_activeEl ) {
        e.preventDefault();
        restaurant_zone_firstEl.focus();
      }

      if ( shiftKey && tabKey && restaurant_zone_firstEl === restaurant_zone_activeEl ) {
        e.preventDefault();
        restaurant_zone_lastEl.focus();
      }
    } );
  }

  restaurant_zone_keepFocusInMenu();
} )( window, document );

jQuery(document).ready(function() {
	var owl = jQuery('#top-slider .owl-carousel');
		owl.owlCarousel({
			margin: 25,
			nav: true,
			autoplay:true,
			autoplayTimeout:500,
			autoplayHoverPause:true,
			loop: true,
			navText : ['<i class="fa fa-lg fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-lg fa-chevron-right" aria-hidden="true"></i>'],
			responsive: {
			  0: {
			    items: 1
			  },
			  600: {
			    items: 1
			  },
			  1024: {
			    items: 1
			}
		}
	})
})