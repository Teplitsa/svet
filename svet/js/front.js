/* Top link Plugin by David Walsh (http://davidwalsh.name/jquery-top-link)
  rewritten by @foralien to be safely used in no-conflict mode */

(function($) {
 
	$.fn.topLink = function(settings) {
	    var config = {
	    	'min'       : 400,
	    	'fadeSpeed' : 200
	    };
 
		if (settings) $.extend(config, settings);
 
		this.each(function() {
       		//listen for scroll
			var el = $(this);
			el.hide(); //in case the user forgot
			
			$(window).scroll(function() {
				if($(window).scrollTop() >= settings.min){
					el.fadeIn(settings.fadeSpeed);
					
				} else {
					el.fadeOut(settings.fadeSpeed);
				}
			});			
    	});
 
    	return this; 
	};
 
})(jQuery);

jQuery(document).ready(function($){
    
	//has js
	$('html').removeClass('no-js').addClass('js');
	
    // Window width 
	var windowWidth = $(window).width();	

	// Top link	 
	var toplinkTrigger = $('#top-link');

	if( windowWidth > 600 ) {
		toplinkTrigger
		.topLink({ //appearance
			min: 400,
			fadeSpeed: 500
			
		})
		.on('click', function(event){ //smoth scroll
			event.preventDefault();
			var full_url = toplinkTrigger.find('a').attr('href');
			
			var parts = full_url.split("#");
			var trgt = parts[1];
			
			var target_offset = $("#"+trgt).offset();
			var target_top = target_offset.top;
			
				
			$('html, body').animate({scrollTop:target_top}, 900, function(){
				$('.title-wide').focus();
			});
		});    
	}
   

    // Resize all embed media iframes to fit the page width
    var resize_embed_media = function(){

        $('iframe').each(function(){

            var $iframe = $(this),
                $parent = $iframe.parent(),
                do_resize = false;
            if($parent.hasClass('embed-content'))
                do_resize = true;            
            else {                
                
                $parent = $iframe.parents('.entry-content');
                if($parent.length)
                    do_resize = true;
            }

            if(do_resize) {

                var change_ratio = 0.98*$parent.width()/$iframe.attr('width');
                $iframe.width(change_ratio*$iframe.attr('width'));
                $iframe.height(change_ratio*$iframe.attr('height'));
            }
        });
    };
    resize_embed_media(); // Initial page rendering
    $(window).resize(resize_embed_media);


    // Responsive nav
    var navCont = $('#site-navigation');
    $('#menu-trigger').on('click', function(e){

        e.preventDefault();
        if (navCont.hasClass('toggled')) { 
            //remove
            navCont.find('ul').slideUp('normal', function(){
				$(this).removeAttr('style');
				navCont.removeClass('toggled'); });
            
        }
        else { 
            //add
            navCont.find('ul').slideDown('normal', function(){ navCont.addClass('toggled'); });
            
        }
    });
    
    
    // Center logos 
	function logo_vertical_center() {
		
		var logos = $('.logo-frame'),
			logoH = logos.eq(0).parents('.logo').height() - 3;
			
		logos.find('span, a').css({'line-height' : logoH + 'px'});
	}
		
	
	imagesLoaded('#primary', function(){
		logo_vertical_center();
	});
		
	$(window).resize(function(){
		logo_vertical_center();
	});
	
	
	// Equal height blocks pb-item	
	imagesLoaded('#primary', function(){
		$('.pb-item').responsiveEqualHeightGrid();		
	});
	
	$(window).resize(function(){
		$('.pb-item').responsiveEqualHeightGrid();
	});
    
});