var mobile_media_query = "screen and (max-width:767px)";
function popup(url)
{
var width = 800;
var height = 600;
var left = (screen.width - width)/2;
var top = (screen.height - height)/2;
var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left +
	', resizable=1, scrollbars=1, menubar=1, toolbar=1';
	newwin = window.open(url,'', params);
	if (window.focus)
	{
		newwin.focus();
	}
}

function resize_carousel_title(carousel, _class)
{
    var is_mobile = window.matchMedia( mobile_media_query ).matches;
    var controls = jQuery(carousel).find(_class);
    var height = jQuery(controls).height();
    var border_width = parseInt(jQuery('.sub-page-carousel-frame').css('borderTopWidth'));
    if (height > 0 && border_width > 0)
    {
        if (is_mobile) {
            jQuery(controls).css({
                'top': 0,
                'bottom': 'auto',
                '-webkit-transform-origin': 'left top',
                'transform-origin': 'left top'
            });
            jQuery('.text').css({
                'top': 'auto',
                'bottom': 0,
            });   
        }
        else{
            jQuery('.text').css({
                'bottom': 'auto',
                'top': 0,
            }); 
            jQuery(controls).css({
                'bottom': -(border_width * 0.5) + 'px',
                'top': 'auto',
                '-webkit-transform-origin': 'left bottom',
                'transform-origin': 'left bottom'
            });
        }
        
        var scale = 'scale(' + border_width / height + ')';
        jQuery(controls).css({
            'z-index': 2000,
            '-webkit-transform': scale + ' translateX(-50%)',
            '-ms-transform': scale + ' translateX(-50%)',
            '-moz-transform': scale + ' translateX(-50%)',
            '-o-transform': scale + ' translateX(-50%)',
            'transform': scale + ' translateX(-50%)'
        });
    }
}

jQuery(document).ready(function(){

    // Configure scrollbar
    jQuery(".content").mCustomScrollbar({
        theme:"light-thick",
        autoDraggerLength:true,
        alwaysShowScrollbar: 1
    });
    /////////////////////////////

    // For all devices -- change carousel images through arrow clicks
    jQuery('.carousel-left-arrow').click(function(event){
        jQuery(event.target).closest('.carousel-controls').find('.anchor-prev-slide').trigger( "click" );
    });

    jQuery('.carousel-right-arrow').click(function(event){
        jQuery(event.target).closest('.carousel-controls').find('.anchor-next-slide').trigger( "click" );
    });
    /////////////////////////////

    // For touch devices -- change carousel images through swipe gestures
    jQuery('.sub-page-carousel-frame').swipe( {
        swipeLeft:function(event) {
            jQuery(event.target).parent().parent().parent().find('.anchor-prev-slide').trigger( "click" );
        },
        swipeRight: function(event) {
            jQuery(event.target).parent().parent().parent().find('.anchor-next-slide').trigger( "click" );
        },
        threshold:0
    });
    /////////////////////////////

    // Show navbar when the Show Navbar button is clicked
    jQuery('.my-navbar-toggle').click(function(){
        if (window.matchMedia( mobile_media_query ).matches) {
            jQuery('.my-navbar-toggle').show();
        }
        else
            jQuery('.my-navbar-toggle').toggle();
        jQuery('.navbar-fixed-top,.navbar-collapse').toggle();
    });
    /////////////////////////////

    // Manage navbar visibility
    jQuery(window).bind("load resize scroll",function(){
        if (window.matchMedia( mobile_media_query ).matches)
            return;
        if (jQuery(this).scrollTop() > 100) {
                jQuery('.navbar-fixed-top').fadeOut();
                jQuery('.my-navbar-toggle').fadeIn();
        }
        else {
                jQuery('.navbar-fixed-top').fadeIn();
                jQuery('.my-navbar-toggle').fadeOut();
        }
    });
    /////////////////////////////

    jQuery(window).bind("load",function(){
        // load images "right bebore" exposure to screen
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
        /////////////////////////////
    });

    jQuery(window).bind("load resize",function(){
        var is_mobile = window.matchMedia( mobile_media_query ).matches;
        // Apply mobile/desktop design changes
        if (is_mobile){
            jQuery('.my-navbar-toggle').show();
            jQuery('.navbar-fixed-top,.navbar-collapse').hide();
            jQuery('.sub-page-carousel-cover').height(window.innerHeight);//jQuery(window).height());
            jQuery('.sub-page-carousel-cover').width(jQuery(window).innerWidth());
            jQuery('.bg-border').hide();
        }
        else{
            jQuery('.sub-page-carousel-cover').height(jQuery(window).width() * .28);
            jQuery('.sub-page-carousel-cover').width(jQuery(window).width() * .42);
            jQuery('.bg-border').show();
        }
        /////////////////////////////

        jQuery('.fullscreen-cover').height(window.innerHeight);

        jQuery('.sub-page-carousel-frame').each(function() {
            var carousel = jQuery(this).closest('.carousel');
            var border_width = jQuery(carousel).width();
            border_width *= (is_mobile ? .03 : .04);
            var half_border_width = border_width * .5;
            if (is_mobile) {
                jQuery(this).css({
                    'border-left-width': 0,
                    'border-right-width': 0,
                    'border-bottom-width': 0,
                    'border-top-width': 2 * border_width + 'px',
                    'top': 0,
                    'left': 0,
                    'width': jQuery(carousel).width() + 'px',
                    'height': jQuery(carousel).height() +  'px'
                });
            }
            else{
                jQuery(this).css({
                    'border-width': border_width + 'px',
                    'top': -half_border_width + 'px',
                    'left': -half_border_width + 'px',
                    'width': jQuery(carousel).width() + border_width + 'px',
                    'height': jQuery(carousel).height() + border_width + 'px'
                });
            }

            resize_carousel_title(carousel, '.text-controls');
            resize_carousel_title(carousel, '.carousel-controls');
            jQuery('.bg-border').css({
                'top': -half_border_width + 'px',
                'height': border_width + 'px'
            });
        });
        if (jQuery(window).innerWidth() < jQuery(window).innerHeight())
            jQuery('body').hide();
        else
            jQuery('body').show();
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