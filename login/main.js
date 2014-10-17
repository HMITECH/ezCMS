$(document).ready(function(){
  $(window).resize(function() {
    equalHeight($(".homeDesc"));
    });
  
  $(window).resize();
  
  
  $('#menuLink').click(function(event) {
    event.preventDefault();
    event.stopPropagation();
    $('#menu').slideToggle('slow');
    $(document).click(function() {
        if ($('#menu').is(':visible') && $(window).width() < 751) {
            $('#menu').slideUp();
        }
    })
});
  
$(window).resize(function() {
    $('#menu').css('display', '');
})

$("#menu a").each(function() {   
    if (this.href == window.location.href) {
        $(this).addClass("menuSelected");
    }
});

 // The slider being synced must be initialized first
	$('#carousel').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: '240',
		itemMargin: 5,
		asNavFor: '#slider',
		direction: 'vertical',
		 minItems: 3
	});
   
	$('#slider').flexslider({
		animation: "fade",
		controlNav: false,
        video: true,
//        useCSS: false,
		//smoothHeight: true,
		animationLoop: false,
		slideshow: false,
		sync: "#carousel",
		controlsContainer: ".flex-container",
		start: function(slider) {
			$('.total-slides').text(slider.count);
			$('.current-slide').text(slider.currentSlide + 1);
		  },
		  after: function(slider) {
            var currentSlide = slider.currentSlide;
            
			$('.current-slide').text(currentSlide + 1);
          },
          before: function(slider) {
            $('#slider li iframe').eq(slider.currentSlide).attr('src',function () { return $(this).attr('src') });
          }
	});


$('.linkLightbox').rbox();
  
});
