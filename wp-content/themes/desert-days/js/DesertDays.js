function resize_carousel_title(carousel, _class)
{
    var controls = jQuery(carousel).find(_class);
    var height = jQuery(controls).height();
    var border_width = parseInt(jQuery('.sub-page-carousel-frame').css('borderLeftWidth'));
    if (height > 0 && border_width > 0)
    {
        var scale = 'scale(' + border_width / height + ')';
        jQuery(controls).css({
            'bottom': -(border_width * 0.5) + 'px',
            '-webkit-transform': scale + ' translateX(-50%)',
            '-ms-transform': scale + ' translateX(-50%)',
            '-moz-transform': scale + ' translateX(-50%)',
            '-o-transform': scale + ' translateX(-50%)',
            'transform': scale + ' translateX(-50%)'
        });
    }
}


jQuery(document).ready(function(){
        jQuery(".content").mCustomScrollbar({
            theme:"light-thick",
            autoDraggerLength:true,
            alwaysShowScrollbar: 1
        });
        
        jQuery('.carousel-left-arrow').click(function(event){
            jQuery(event.target).closest('.carousel-controls').find('.anchor-prev-slide').trigger( "click" );
	});
        
        jQuery('.carousel-right-arrow').click(function(event){
            jQuery(event.target).closest('.carousel-controls').find('.anchor-next-slide').trigger( "click" );
	});
        
        // For touch devices -- change carousel images through swipe gestures
        jQuery('.sub-page-carousel-frame').swipe( {
            //Generic swipe handler for all directions
            swipeLeft:function(event) {
                jQuery(event.target).parent().parent().parent().find('.anchor-prev-slide').trigger( "click" );
            },
            swipeRight: function(event) {
                jQuery(event.target).parent().parent().parent().find('.anchor-next-slide').trigger( "click" );
            },
            threshold:0
        });
        
       //Menu button
	jQuery(window).bind("load resize scroll",function(){
                if (jQuery(window).width() < 768) { // This is a mobile screen
			jQuery('.showNavbar').hide();
                        jQuery('.navbar-fixed-top').fadeIn();
                        return;
		}
		if (jQuery(this).scrollTop() > 100) {
			jQuery('.navbar-fixed-top').fadeOut();
			jQuery('.showNavbar').fadeIn();
		}
		else {
			jQuery('.navbar-fixed-top').fadeIn();
			jQuery('.showNavbar').fadeOut();
		}
	});

	jQuery('.showNavbar').click(function(){
            jQuery('.navbar-fixed-top').fadeIn();
            jQuery('.showNavbar').fadeOut();
	});
        
        //Set some elements to viewport size (css vw/vh does not work in too many cases)
	jQuery(window).bind("load resize",function(event){
            jQuery('.fullscreen-cover').height(jQuery(window).height());
            jQuery('.sub-page-carousel-cover').height(jQuery(window).width() * .28);
            jQuery('.sub-page-carousel-cover').width(jQuery(window).width() * .42);
            if (event.type === 'load')
            {
                jQuery(".lazy_backgrounds").lazyload({
                    skip_invisible : false,
                    threshold : 200,
                    load : function() {
                        var subPage = jQuery(this).closest('.sub-page-cover');
                        jQuery(subPage).find('.carousel').css("visibility", "visible");
                    }
                });

                jQuery(".lazy").lazyload({
                    skip_invisible : false,
                    threshold : 200
                });
            }
            
            jQuery('.sub-page-carousel-frame').each(function() {
                var carousel = jQuery(this).closest('.carousel');
                var border_width = jQuery(carousel).width();
                border_width *= .04;
                var half_border_width = border_width * .5;
                jQuery(this).css({
                    'border-width': border_width + 'px',
                    'top': -half_border_width + 'px',
                    'left': -half_border_width + 'px',
                    'width': jQuery(carousel).width() + border_width + 'px',
                    'height': jQuery(carousel).height() + border_width + 'px'
                });
                resize_carousel_title(carousel, '.text-controls');
                resize_carousel_title(carousel, '.carousel-controls');
                jQuery('.bg-border').css({
                    'top': -half_border_width + 'px',
                    'height': border_width + 'px'
                });
            });
        });
        
        // switch between text/image carousel views
	jQuery('.sub-page-show-text').click(function(event){
            jQuery(event.target).closest('.row').find('.text-area').fadeIn(200);
            jQuery(event.target).closest('.row').find('.carousel-controls').fadeOut(200);
            resize_carousel_title(jQuery(event.target).closest('.row').find('.carousel'), '.text-controls');
	});

	jQuery('.sub-page-show-carousel').click(function(event){
            jQuery(event.target).closest('.row').find('.carousel-controls').fadeIn(200);
            jQuery(event.target).closest('.row').find('.text-area').fadeOut(200);
            resize_carousel_title(jQuery(event.target).closest('.row').find('.carousel'), '.carousel-controls');
	});
});