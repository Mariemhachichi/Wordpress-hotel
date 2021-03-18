
/* Page Scroll JS
----------------------------------------------------------------*/
jQuery(document).ready(function ($) {
	
	$(window).scroll(function () {
		if ($(this).scrollTop() > 100) {
			$('.bottomScrollBtn').fadeIn();
		} else {
			$('.bottomScrollBtn').fadeOut();
		}
	});
	
	if($('.nav_sticky').length > 0 ){
		var stickyOffset = $('.nav_sticky').offset().top;
		var navH = $('.nav_sticky').outerHeight();
		$(window).scroll(function () {
			if ($(window).scrollTop() >= stickyOffset){
				$('.nav_sticky').addClass('fixed animated fadeInDown');
				$('.nav-spacer').addClass('animated fadeInUp').attr('hieght', navH + 'px'); 
			}else {
				$('.nav_sticky').removeClass('fixed animated fadeInDown');
				$('.nav-spacer').addClass('animated fadeInUp').attr('hieght','0px');
			}
		});
	}
	

	$('.bottomScrollBtn').click(function () {
		$("html, body").animate({
			scrollTop: 0
		}, 600);
		return false;
	});
	
	if( hotelone_settings.is_frontpage == true ){
		$('.header_scroll_logo .custom-logo-link, .site-title').click(function () {
			$("html, body").animate({
				scrollTop: 0
			}, 600);
			return false;
		});
	}
	
	/* Bootstrap Tool Tip
	---------------------*/
	$( '[data-toggle="tooltip"], [rel="tooltip"]' ).tooltip();
});	