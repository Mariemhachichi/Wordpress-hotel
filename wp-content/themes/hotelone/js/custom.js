
/* Custom JS
----------------------------------------------------------------*/
jQuery(document).ready(function ($) {

	'use strict';

	$('.theme_mobile_container nav').meanmenu({
		meanScreenWidth: 990,
        meanMenuContainer: ".theme_mobile_container",
        meanMenuOpen: "<span></span><span></span><span></span>",
        onePage: true,
    });
	
	/* Menu dropdown on hover
	----------------------------------------------------------------*/
	$('.nav li.dropdown').hover(function() {
		   $(this).addClass('open');
	   }, function() {
		   $(this).removeClass('open');
	   });
	$('li.dropdown').on('click', function() {
			var $el = $(this);
			if ($el.hasClass('open')) {
				var $a = $el.children('a.dropdown-toggle');
				if ($a.length && $a.attr('href')) {
					location.href = $a.attr('href');
				}
			}
		});
	
});	

if( hotelone_settings.disable_animations != true ){
			new WOW().init();
		}