(function (jQuery) {
  "use strict";

  // inatate wow jQuery
  new WOW().init();


  jQuery('.nav-menu').superfish({
    delay:       1000,
    animation:   {opacity:'show',height:'show'},
    speed:       'fast',
    autoArrows:  false
  });


//Primary Nav in both scene

  var windowWidth = jQuery(window).width();
  var nav = ".main-nav";
  //    for sub menu arrow

  //    for sub menu arrow
  jQuery('.main-nav >li:has("ul")>a').each(function() {
    jQuery(this).addClass('below');
  });
  jQuery('ul:not(.main-nav)>li:has("ul")>a').each(function() {
    jQuery(this).addClass('side');
  });
  if (windowWidth > 991) {

    jQuery('#showbutton').off('click');
    jQuery('.im-hiding').css('display', 'block');
    jQuery(nav).off('mouseenter', 'li');
    jQuery(nav).off('mouseleave', 'li');
    jQuery(nav).off('click', 'li');
    jQuery(nav).off('click', 'a');
    jQuery(nav).on('mouseenter', 'li', function() {
      jQuery(this).children('ul').stop().hide();
      jQuery(this).children('ul').slideDown(400);
    });
    jQuery(nav).on('mouseleave', 'li', function() {
      jQuery(this).children('ul').stop().slideUp(350);
    });
  } else {

    jQuery('#showbutton').off('click');
    jQuery('.im-hiding').css('display', 'none');
    jQuery(nav).off('mouseenter', 'li');
    jQuery(nav).off('mouseleave', 'li');
    jQuery(nav).off('click', 'li');
    jQuery(nav).off('click', 'a');
    jQuery(nav).on('click', 'a', function(e) {
      jQuery(this).next('ul').attr('style=""');
      jQuery(this).next('ul').slideToggle();
      if (jQuery(this).next('ul').length !== 0) {
        e.preventDefault();
      }
    });
    // for hide menu
    jQuery('#showbutton').on('click', function() {

      jQuery('.im-hiding').slideToggle();
    });
  }
  jQuery(window).resize(function() {
    windowWidth = jQuery(window).width();
    jQuery(nav + ' ul').each(function() {
      jQuery(this).attr('style', '" "');
    });
    if (windowWidth > 991) {
      jQuery('#showbutton').off('click');
      jQuery('.im-hiding').css('display', 'block');
      jQuery(nav).off('mouseenter', 'li');
      jQuery(nav).off('mouseleave', 'li');
      jQuery(nav).off('click', 'li');
      jQuery(nav).off('click', 'a');
      jQuery(nav).on('mouseenter', 'li', function() {
        jQuery(this).children('ul').stop().hide();
        jQuery(this).children('ul').slideDown(400);
      });
      jQuery(nav).on('mouseleave', 'li', function() {
        jQuery(this).children('ul').stop().slideUp(350);
      });
    } else {
      jQuery('#showbutton').off('click');
      jQuery('.im-hiding').css('display', 'none');
      jQuery(nav).off('mouseenter', 'li');
      jQuery(nav).off('mouseleave', 'li');
      jQuery(nav).off('click', 'li');
      jQuery(nav).off('click', 'a');
      jQuery(nav).on('click', 'a', function(e) {
        jQuery(this).next('ul').attr('style=""');
        jQuery(this).next('ul').slideToggle();
        if (jQuery(this).next('ul').length !== 0) {
          e.preventDefault();
        }
      });
      // for hide menu
      jQuery('#showbutton').on('click', function() {

        jQuery('.im-hiding').slideToggle();
      });
    }
  });

  // scroll jQuery
  jQuery( "#parallex-counter" ).click(function() {
    jQuery('html, body').animate({
      scrollTop: jQuery("#ours-restaurant-theme-counter").offset().top
    }, 2000);
  });

  /* nav bar fixed */
  jQuery(window).scroll(function () {

    if (jQuery(window).scrollTop() > 100) {
      jQuery('#masthead-header').addClass('header-sticky');
    }
    else{
      jQuery('#masthead-header').removeClass('header-sticky');
    }

    if (jQuery(window).scrollTop() > 30) {
      jQuery('.main-header').addClass('navbar-fixed-top');
    }

    if (jQuery(window).scrollTop() < 31) {
      jQuery('.main-header').removeClass('navbar-fixed-top');
    }
  });




  /* scroll to top */
  jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > 400) {
      jQuery('.scrollup').fadeIn();
    } else {
      jQuery('.scrollup').fadeOut();
    }
  });

  jQuery('.scrollup').click(function () {
    jQuery("html, body").animate({
      scrollTop: 0
    }, 1500);
    return false;
  });



  //Slider Crousel

  /* owl carousl jQuery */

  jQuery("#owl-demo1").owlCarousel({

    slideSpeed : 500,
    items :1,
    autoPlay:true,
    paginationSpeed : 1000,
    singleItem:true,
    navigation : true,
    navigationText: [
      "<i class='fa fa-chevron-left'></i>",
      "<i class='fa fa-chevron-right'></i>"
    ],
    pagination : true



  });
  // Skills section
  jQuery('#skills').waypoint(function() {
    jQuery('.progress .progress-bar').each(function() {
      jQuery(this).css("width", jQuery(this).attr("aria-valuenow") + '%');
    });
  }, { offset: '80%'} );

  // jQuery counterUp (used in Facts section)

  jQuery('.counter-up').counterUp({
    delay: 10,
    time: 1000
  });


  /*testgesrasrfesdhyfuisdyigfgyeisd*/

// init Isotope
  var $grid = jQuery('.portfolio-container').isotope({
    itemSelector: '.portfolio-item',
    layoutMode: 'fitRows'
  });
// filter functions
  var filterFns = {
    // show if number is greater than 50
    numberGreaterThan50: function() {
      var number = jQuery(this).find('.number').text();
      return parseInt( number, 10 ) > 50;
    },
    // show if name ends with -ium
    ium: function() {
      var name = jQuery(this).find('.name').text();
      return name.match( /ium$/ );
    }
  };
// bind filter button click
  jQuery("#portfolio-flters li").click(function(){
    var filterValue = jQuery( this ).attr('data-filter');
    // use filterFn if matches value
    filterValue = filterFns[ filterValue ] || filterValue;
    $grid.isotope({ filter: filterValue });
  });
// change is-checked class on buttons
  jQuery('.portfolio').each( function( i, buttonGroup ) {
    var $buttonGroup = jQuery( buttonGroup );
    $buttonGroup.on( 'click', 'li', function() {
      $buttonGroup.find('.current').removeClass('current');
      jQuery( this ).addClass('current');
    });
  });
  /*testgesrasrfesdhyfuisdyigfgyeisd*/


  // Clients carousel (uses the Owl Carousel library)
  jQuery(".clients-carousel").owlCarousel({
    slideSpeed : 300,
    autoPlay:true,
    paginationSpeed : 400,
    singleItem:true,
    navigation : false,
    pagination : true

  });

  // Testimonials carousel (uses the Owl Carousel library)
  jQuery(".testimonials-carousel").owlCarousel({
    loop: true,
    slideSpeed:2000,
    pagination:true,
    autoplay: true,
    
    nav:true,
    dots:true,
    items :1,
    itemsDesktop : [1199,2],
    itemsDesktopSmall : [980,2],
    itemsMobile : [479,1]
  });
  //sticky sidebar
  var at_body = jQuery("body");
  var at_window = jQuery(window);

  if(at_body.hasClass('at-sticky-sidebar')){
    if(at_body.hasClass('right-sidebar')){
      jQuery('#secondary, #primary').theiaStickySidebar();
    }
    else{
      jQuery('#secondary, #primary').theiaStickySidebar();
    }
  }

  /*faq*/
  jQuery('.faq-header').click(function () {
    jQuery(this).closest('#accordion').find('.faq-content').hide();
    jQuery(this).next().show();
  })



  /*AFTER LAST MENU CLOSED */
  jQuery('.last-menu').focusout(function (e) {
    jQuery('.im-hiding').slideToggle();
    e.preventDefault();
  })



})(jQuery);

