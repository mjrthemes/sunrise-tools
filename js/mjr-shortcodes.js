jQuery(document).ready(function($) {

	"use strict";

	/* SHORTCODES AND WIDGETS */

	// Enable tabs

	function mjr_shortcodes_init() {
		$('.mjr-tabs').each( function() {
			$(this).tabs();
			$('br', this).remove();
			$(this).show();
		});

		// Toggle
		$(".toggle-container").hide(); 
		$(".trigger").toggle(function(){
			$(this).addClass("active");
			}, function () {
			$(this).removeClass("active");
		});
		$(".trigger").click(function(){
			$(this).next(".toggle-container").slideToggle();
		});

		// Accordion
		$('.trigger-button').click(function() {
			$('.trigger-button').removeClass('active')
		 	$('.accordion').slideUp('normal');
			if($(this).next().is(':hidden') == true) {
				$(this).next().slideDown('normal');
				$(this).addClass('active');
			 } 
		 });
		$('.accordion').hide();

		// Slideshow generator
		$('.mjr-slider').each( function() {
			$(this).flexslider({
				slideshowSpeed: $(this).data('slideshowspeed'),
				animationSpeed: $(this).data('animationspeed'),
				animation: $(this).data('animation'),
				direction: $(this).data('direction'),
				slideshow: $(this).data('autostart'),
				keyboard: true,
				touch: true,
				pauseOnHover: true,
				prevText: "",
				nextText: "",
				start: renderPreview,	//render preview on start
				before: renderPreview //render preview before moving to the next slide
			});
		});
	}

	$(document).ajaxComplete(function() {
		mjr_shortcodes_init();
	});

	mjr_shortcodes_init();

	function renderPreview(slider) {
	
		 var sl = $(slider);
		 var prevWrapper = sl.find('.flex-prev');
		 var nextWrapper = sl.find('.flex-next');		 
		 
		 //calculate the prev and curr slide based on current slide
		 var curr = slider.animatingTo;
		 var prev = (curr == 0) ? slider.count - 1 : curr - 1;
		 var next = (curr == slider.count - 1) ? 0 : curr + 1;		 

		 //add prev and next slide details into the directional nav
		 prevWrapper.find('.preview, .arrow').remove();
		 nextWrapper.find('.preview, .arrow').remove();		 		 
		 prevWrapper.append(grabContent(sl.find('li:eq(' + prev + ')')));
		 nextWrapper.append(grabContent(sl.find('li:eq(' + next + ')')));		 

	}
	
	// grab the data and render in HTML
	function grabContent(img) {		
		var tn = img.data('image');
		var html = '';
		//var alt = img.find('h3').html();
		
		html = '<div class="preview" style="background-image: url(' + tn + ');"></div>';	
		return html;
	}

});