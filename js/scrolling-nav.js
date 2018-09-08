//jQuery to collapse the navbar on scroll
$(window).scroll(function() {
    if ($(".navbar").offset().top > 30) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
		$(".navbar-fixed-top").addClass("sombra2");
		$(".navbar-fixed-top").removeClass("sombra1");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
		$(".navbar").addClass("sombra1");
		$(".navbar").removeClass("sombra2");
    }
});

//jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

$(document).ready(function () {
	$('.dropdown').hover(function(){ 
		$('.dropdown-toggle', this).trigger('click'); 
	});
});