var $ = jQuery.noConflict();

$('.multiple-items-offrs1').slick({
  infinite: true,
  slidesToShow: 1,
    autoplay: true,
	arrows:false,
	dots: true,
	autoplaySpeed: 6000,
	centerMode: true,
	focusOnSelect: true,
		responsive: [{
			breakpoint: 992,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}]
});

$('.multiple-items-offrs').slick({
		autoplay: true,
		adaptiveHeight: false,
		autoplaySpeed: 6000,
		slidesToShow: 1,
		centerMode: false,
		arrows: false,
		dots: true,
		prevArrow: '<span class="cr-navigation cr-navigation-prev"><i class="fa fa-angle-left"></i></span>',
		nextArrow: '<span class="cr-navigation cr-navigation-next"><i class="fa fa-angle-right"></i></span>',
		focusOnSelect: true,
		responsive: [{
			breakpoint: 992,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}]
	});
	
$('.multiple-items-offrs-rtl').slick({
		autoplay: true,
		adaptiveHeight: false,
		autoplaySpeed: 6000,
		slidesToShow: 1,
		centerMode: false,
		arrows: false,
		dots: true,
		rtl: true,
		prevArrow: '<span class="cr-navigation cr-navigation-prev"><i class="fa fa-angle-left"></i></span>',
		nextArrow: '<span class="cr-navigation cr-navigation-next"><i class="fa fa-angle-right"></i></span>',
		focusOnSelect: true,
		responsive: [{
			breakpoint: 992,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}]
	});	
	 