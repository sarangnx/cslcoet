jQuery(document).ready(function($) {
    "use strict"
    // ------- Team Slider ------- //  
    jQuery('#team-slider').slick({
        dots: false,
        infinite: true,
        speed: 700,
        arrows: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [
            { breakpoint: 992, settings:{ slidesToShow: 3, slidesToScroll: 1}},
            { breakpoint: 1024, settings:{ slidesToShow: 3, slidesToScroll: 1}},
            { breakpoint: 768, settings:{ slidesToShow: 2, slidesToScroll: 1}},
            { breakpoint: 481, settings:{ slidesToShow: 1, slidesToScroll: 1}}
        ]
    });
    // ------- Team Slider ------- //

    // ------- Ticker ------- //  
    jQuery('#ticker').slick({
        dots: false,
        infinite: true,
        speed: 700,
        arrows: true,
        vertical: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000
    });
    // ------- Ticker ------- //
 
    // ------- Matches Detail Slider------- //  
    jQuery('#team-match-slider').slick({
        dots: false,
        infinite: true,
        speed: 700,
        arrows: true,
        slidesToShow: 1,
        slidesToScroll: 1
    });
    // ------- Matches Detail Slider------- // 

    // ---------- Responsive Slider menu ---------- //
    jQuery('.menu-link').bigSlide();
    // ---------- Responsive Slider menu ---------- //

    // ---------- Inner Slider ---------- //  
    jQuery('#animated-slider').carousel({
        interval:5000,
        pause: "false"
    });
    // ---------- Inner Slider ---------- //

    // ------- Scroll to Top ------- //
    jQuery('.scrollup').on("click", function () {
        jQuery("html, body").animate({
            scrollTop: 0
        }, 1000);
        return false;
    });
    // ------- Scroll to Top ------- //

    // ---------- Wow Animation ---------- //
    var wow = new WOW({
        boxClass:     'animate',  
        animateClass: 'animated', 
        offset:       0,          
        mobile:       true        
        });
    wow.init();
    // ---------- Wow Animation ---------- //

    // ------- Auto height function ------- //
    var setElementHeight = function () {
        var width = jQuery(window).width();
        /*if (jQuery('.tg-hero-slider li img') >= height) {*/
        var height = jQuery(window).height();
        jQuery('.fullscreen').css('height', (height));
        }

    jQuery(window).on("resize", function () {
        setElementHeight();
    }).resize();
    // ------- Auto height function ------- //

});
