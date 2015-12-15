   /* ====================================
    * main nav
    * =======================================
    */
jQuery(document).ready(function($) {
    "use strict";
    var mainNavbar  =   $('.navbar'),
        mainNav     =   mainNavbar.find('.nav'),
        barPosition =   $(window).scrollTop(),
        mainNavlist =   mainNavbar.find('.nav>li'),
        scOffset    =   mainNavbar.outerHeight();

    $('.nav').onePageNav({
        currentClass: 'active',
        changeHash: false,
        easing: 'easeInOutExpo',
        scrollThreshold: 0.2, 
        scrollOffset: scOffset,
        scrollSpeed: 1300
    });
    if (barPosition > 400) {
        mainNavbar.addClass('fadeInDown navbar-after-scroll fadeIn').removeClass('drop-nav fadeOut fadeOutUp').width();             
    }
    $('.navbar-toggle').click(function(e) {
        if (barPosition <100) {
            mainNavbar.toggleClass('navbar-after-scroll fadeInDown drop-nav');
        };
    });
    $(window).scroll(function(e) {
        barPosition =$(this).scrollTop();
        if (barPosition > 600) {
            mainNavbar.addClass('fadeInDown navbar-after-scroll fadeIn').removeClass('drop-nav fadeOut fadeOutUp').width();                
        }else {
            mainNavbar.addClass('fadeOutUp').removeClass('fadeInDown fadeIn').width();
            if (barPosition < 300) {
                mainNavbar.addClass('drop-nav').removeClass('navbar-after-scroll fadeOutUp');
                 $('.navbar-collapse').attr('aria-expanded', 'false').removeClass('in');
            };
        }
    });
    $('.goto').click(function(e) {
        e.preventDefault();
        var positonPoint = $(this).attr('href');
        $('html,body').animate({
            scrollTop:$(positonPoint).offset().top-scOffset
        }, 800);
    });
});