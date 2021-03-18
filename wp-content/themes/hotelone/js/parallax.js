
/* Custom JS
----------------------------------------------------------------*/
jQuery(document).ready(function ($) {
	
	/* Menu dropdown on hover
	----------------------------------------------------------------*/
	$('.nav li.dropdown').hover(function() {
		   $(this).addClass('open');
	   }, function() {
		   $(this).removeClass('open');
	   });
	   
	   
	/* client section
	----------------------------------------------------------------*/
	
	$('.owl-carousel').owlCarousel({
		loop:true,
		margin:10,
		nav:true,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:3
			},
			1000:{
				items:5
			}
		}
	});

	/* video section
	----------------------------------------------------------------*/
    $(".video_lightbox").lightGallery({
		selector: 'a'
	});
	
	/* Gallery Page
	----------------------------------------------------------------*/
    $(".galleryPage").lightGallery({
		selector: '.gallerythumb'
	});
	
	/* Room Page
	----------------------------------------------------------------*/
    $(".room_section").lightGallery({
		selector: '.roomimage'
	});
	
	/* Counter
	----------------------------------------------------------------*/
    $('.counter_count').counterUp({
        delay: 10,
        time: 1000
    });
	
});	


if( hotelone_settings.disable_animations != true ){
			new WOW().init();
		}

jQuery(document).ready(function ($) {
	var h;
	window.current_nav_item = false;
	h = $('.hotelone_nav').height();
	
	var $window     = $(window);
    var $document = $(document);
	var hotelone_js_settings = ['hotelone_disable_sticky_header'];
	
    // Navigation click to section.
    $('.navbar-nav li a[href*="#"]').on('click', function(event){
        event.preventDefault();
        smoothScroll( $( this.hash ) );
    });
	
	function inViewPort( $element, offset_top ){
        if ( ! offset_top ) {
            offset_top = 0
        }
        var view_port_top = jQuery( window ).scrollTop();
        if ( $('#wpadminbar' ).length > 0 ) {
            view_port_top -= $('#wpadminbar' ).outerHeight() - 1;
            offset_top += $('#wpadminbar' ).outerHeight() - 1;
        }
        var view_port_h = $( 'body' ).outerHeight();

        var el_top = $element.offset().top;
        var eh_h = $element.height();
        var el_bot = el_top + eh_h;
        var view_port_bot = view_port_top + view_port_h;

        var all_height = $( 'body' )[0].scrollHeight;
        var max_top = all_height - view_port_h;


        var in_view_port = false;
        // If scroll maximum
        if ( view_port_top >= max_top ) {

            if ( ( el_top < view_port_top &&  el_top > view_port_bot ) || ( el_top > view_port_top && el_bot < view_port_top  ) ) {
                in_view_port = true;
            }

        } else {
            if ( el_top <= view_port_top + offset_top ) {
                //if ( eh_bot > view_port_top &&  eh_bot < view_port_bot ) {
                if ( el_bot > view_port_top  ) {
                    in_view_port = true;
                }
            }
        }
        return in_view_port;
    }
	
	// Add active class to menu when scroll to active section.
    var _scroll_top = $window.scrollTop();
    jQuery( window ).scroll(function() {
        var currentNode = null;

        if ( ! window.current_nav_item ) {
            var current_top = $window.scrollTop();

            if ( hotelone_js_settings.hotelone_disable_sticky_header != '1' ) {
                h = jQuery('#wpadminbar').height() + jQuery('.hotelone_nav').height();
            } else {
                h = jQuery('#wpadminbar').height();
            }
			h = jQuery('.hotelone_nav').height();
			console.log(h);

            if( _scroll_top < current_top )
            {
                jQuery('.section').each( function ( index ) {
                    var section = jQuery( this );
                    var currentId = section.attr('id') || '';

                    var in_vp = inViewPort( section , h + 10) ;
                    if ( in_vp ) {
                        currentNode = currentId;
                    }
                });

            } else {
                var ns = jQuery('.section').length;
                for ( var i = ns - 1; i >= 0; i-- ) {
                    var section = jQuery('.section').eq( i );
                    var currentId = section.attr('id') || '';
                    var in_vp = inViewPort( section , h + 10) ;
                    if ( in_vp ) {
                        currentNode = currentId;
                    }

                }
            }
            _scroll_top = current_top;

        } else {
            currentNode = window.current_nav_item.replace('#', '');
        }

        setNavActive( currentNode );
    });
	
	function setNavActive( currentNode ){
        if ( currentNode ) {
            currentNode = currentNode.replace('#', '');
			console.log(currentNode)
            if (currentNode)
                jQuery('.navbar-nav li').removeClass('active');
            if (currentNode) {
                jQuery('.navbar-nav li').find('a[href$="#' + currentNode + '"]').parent().addClass('active');
            }
        }
    }
	
	// Move to the right section on page load.
    jQuery(window).load(function(){
        var urlCurrent = location.hash;
        if ( jQuery( urlCurrent ).length > 0 ) {
            smoothScroll( urlCurrent );
        }
    });

    // Smooth scroll animation
    function smoothScroll( element ) {
        if ( element.length <= 0 ) {
            return false;
        }
        $("html, body").animate({
            scrollTop: ( $( element ).offset().top - h) + "px"
        }, {
            duration: 800,
            easing: "swing",
            complete: function(){
                window.current_nav_item = false;
            }
        });
    }
	
});
